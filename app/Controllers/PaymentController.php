<?php

namespace App\Controllers;

use App\Libraries\FlutterwaveService;
use App\Models\PaymentModel;
use App\Models\SubscriptionPlanModel;
use App\Models\TenantModel;
use App\Models\UserModel;

class PaymentController extends BaseController
{
    private FlutterwaveService    $flw;
    private PaymentModel          $paymentModel;
    private TenantModel           $tenantModel;
    private UserModel             $userModel;
    private SubscriptionPlanModel $planModel;

    public function __construct()
    {
        $this->flw          = new FlutterwaveService();
        $this->paymentModel = new PaymentModel();
        $this->tenantModel  = new TenantModel();
        $this->userModel    = new UserModel();
        $this->planModel    = new SubscriptionPlanModel();
    }

    /** Charge les plans payants depuis la DB (résultat mis en cache sur l'instance). */
    private function getPlans(): array
    {
        return $this->planModel->getPlansForCheckout();
    }

    /**
     * Page de checkout — affichée après inscription pour les plans payants.
     */
    public function checkout(): string
    {
        $pending = session()->get('pending_payment');

        if (!$pending) {
            return redirect()->to('/register');
        }

        $planSlug = $pending['plan_slug'];
        $plans    = $this->getPlans();

        if (!isset($plans[$planSlug])) {
            session()->remove('pending_payment');
            return redirect()->to('/register');
        }

        $plan  = $plans[$planSlug];
        $cycle = session()->get('billing_cycle') ?? 'monthly';

        // Créer ou récupérer l'enregistrement de paiement
        $payment = $this->paymentModel->where('tenant_id', $pending['tenant_id'])
                                      ->where('status', 'pending')
                                      ->orderBy('id', 'DESC')
                                      ->first();

        if (!$payment) {
            return redirect()->to('/register')->with('error', 'Session expirée. Veuillez recommencer.');
        }

        $amount = $cycle === 'annual' ? $plan['annuel'] : $plan['mensuel'];

        return view('payment/checkout', [
            'title'       => 'Finaliser votre abonnement — IkelyaneMed',
            'plan'        => $plan,
            'plan_slug'   => $planSlug,
            'cycle'       => $cycle,
            'amount'      => $amount,
            'payment'     => $payment,
            'tenant_nom'  => $pending['tenant_nom'],
            'email'       => $pending['email'],
            'public_key'  => $this->flw->getPublicKey(),
        ]);
    }

    /**
     * Mise à jour du cycle de facturation (AJAX ou form).
     */
    public function setCycle(): string
    {
        $cycle = $this->request->getPost('cycle');
        if (in_array($cycle, ['monthly', 'annual'])) {
            session()->set('billing_cycle', $cycle);

            $pending = session()->get('pending_payment');
            if ($pending) {
                $plans  = $this->getPlans();
                $plan   = $plans[$pending['plan_slug']] ?? null;
                $amount = $plan ? ($cycle === 'annual' ? $plan['annuel'] : $plan['mensuel']) : 0;

                // Mettre à jour le montant dans payments
                $payment = $this->paymentModel->where('tenant_id', $pending['tenant_id'])
                                              ->where('status', 'pending')
                                              ->first();
                if ($payment) {
                    $this->paymentModel->update($payment['id'], [
                        'billing_cycle' => $cycle,
                        'amount'        => $amount,
                    ]);
                }
            }
        }
        return redirect()->to('/payment/checkout');
    }

    /**
     * Callback Flutterwave après paiement réussi (redirect depuis popup JS).
     */
    public function verify()
    {
        $transactionId = $this->request->getGet('transaction_id');
        $txRef         = $this->request->getGet('tx_ref');

        if (!$transactionId || !$txRef) {
            return redirect()->to('/payment/failed?reason=missing_params');
        }

        // Récupérer le paiement en attente
        $payment = $this->paymentModel->findByTxRef($txRef);

        if (!$payment || $payment['status'] !== 'pending') {
            return redirect()->to('/payment/failed?reason=invalid_ref');
        }

        // Vérifier avec l'API Flutterwave
        $transaction = $this->flw->verifyTransaction($transactionId);

        if (!$transaction) {
            $this->paymentModel->update($payment['id'], ['status' => 'failed']);
            return redirect()->to('/payment/failed?reason=verification_failed');
        }

        // Vérifications de sécurité — montant arrondi à 2 décimales pour
        // éviter les écarts de virgule flottante, et comparaison stricte (==)
        // pour rejeter aussi bien les sous-paiements que les sur-paiements.
        $flwAmount      = round((float)$transaction['amount'],   2);
        $expectedAmount = round((float)$payment['amount'],       2);

        $isValid = $transaction['status']                          === 'successful'
                && $flwAmount                                      === $expectedAmount
                && strtoupper($transaction['currency'])            === strtoupper($payment['currency'])
                && $transaction['tx_ref']                          === $txRef;

        if (!$isValid) {
            $this->paymentModel->update($payment['id'], ['status' => 'failed']);
            log_message('warning', sprintf(
                'Flutterwave payment validation failed — tx_ref=%s | status=%s | amount=%.2f (expected %.2f) | currency=%s (expected %s)',
                $txRef,
                $transaction['status']  ?? 'n/a',
                $flwAmount,
                $expectedAmount,
                strtoupper($transaction['currency'] ?? ''),
                strtoupper($payment['currency'])
            ));
            return redirect()->to('/payment/failed?reason=payment_validation_failed');
        }

        // Tout est bon → activer le tenant et l'utilisateur
        $this->paymentModel->update($payment['id'], [
            'status'             => 'paid',
            'flw_transaction_id' => $transactionId,
            'flw_payment_type'   => $transaction['payment_type'] ?? null,
        ]);

        $cycle      = $payment['billing_cycle'];
        $expireDate = $cycle === 'annual'
            ? date('Y-m-d', strtotime('+1 year'))
            : date('Y-m-d', strtotime('+1 month'));

        $this->tenantModel->update($payment['tenant_id'], [
            'actif'      => 1,
            'abonnement' => $payment['plan_slug'],
            'expire_le'  => $expireDate,
        ]);

        $this->userModel->where('tenant_id', $payment['tenant_id'])
                        ->set(['actif' => 1])
                        ->update();

        // Connecter l'utilisateur
        $user   = $this->userModel->where('tenant_id', $payment['tenant_id'])->first();
        $tenant = $this->tenantModel->find($payment['tenant_id']);

        if ($user && $tenant) {
            session()->set([
                'logged_in'      => true,
                'user_id'        => $user['id'],
                'tenant_id'      => $tenant['id'],
                'nom'            => $user['nom'],
                'prenom'         => $user['prenom'],
                'email'          => $user['email'],
                'role'           => 'admin',
                'avatar'         => $user['avatar'] ?? null,
                'tenant_nom'     => $tenant['nom'],
                'tenant_couleur' => $tenant['couleur'] ?? '#1a56db',
            ]);
        }

        session()->remove('pending_payment');
        session()->remove('billing_cycle');

        return redirect()->to('/payment/success');
    }

    /**
     * Webhook Flutterwave (appel serveur → serveur, sécurisé par signature).
     */
    public function webhook()
    {
        $signature = $this->request->getHeaderLine('verif-hash');

        if (!$this->flw->validateWebhook($signature)) {
            return $this->response->setStatusCode(401)->setBody('Unauthorized');
        }

        $payload = json_decode($this->request->getBody(), true);

        if (($payload['event'] ?? '') !== 'charge.completed') {
            return $this->response->setStatusCode(200)->setBody('OK');
        }

        $data  = $payload['data'] ?? [];
        $txRef = $data['tx_ref'] ?? '';

        $payment = $this->paymentModel->findByTxRef($txRef);

        if (!$payment || $payment['status'] === 'paid') {
            return $this->response->setStatusCode(200)->setBody('OK');
        }

        if ($data['status'] === 'successful') {
            $transaction = $this->flw->verifyTransaction($data['id']);

            $flwAmount      = round((float)($transaction['amount']   ?? 0), 2);
            $expectedAmount = round((float)$payment['amount'],              2);

            $webhookValid = $transaction
                && $flwAmount === $expectedAmount
                && strtoupper($transaction['currency'] ?? '') === strtoupper($payment['currency']);

            if (! $webhookValid) {
                log_message('warning', sprintf(
                    'Flutterwave webhook validation failed — tx_ref=%s | amount=%.2f (expected %.2f) | currency=%s (expected %s)',
                    $txRef,
                    $flwAmount,
                    $expectedAmount,
                    strtoupper($transaction['currency'] ?? 'n/a'),
                    strtoupper($payment['currency'])
                ));
            }

            if ($webhookValid) {
                $this->paymentModel->update($payment['id'], [
                    'status'             => 'paid',
                    'flw_transaction_id' => (string)$data['id'],
                    'flw_payment_type'   => $data['payment_type'] ?? null,
                ]);

                $cycle      = $payment['billing_cycle'];
                $expireDate = $cycle === 'annual'
                    ? date('Y-m-d', strtotime('+1 year'))
                    : date('Y-m-d', strtotime('+1 month'));

                $this->tenantModel->update($payment['tenant_id'], [
                    'actif'      => 1,
                    'abonnement' => $payment['plan_slug'],
                    'expire_le'  => $expireDate,
                ]);

                $this->userModel->where('tenant_id', $payment['tenant_id'])
                                ->set(['actif' => 1])
                                ->update();
            }
        }

        return $this->response->setStatusCode(200)->setBody('OK');
    }

    /**
     * Page de succès.
     */
    public function success(): string
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('payment/success', ['title' => 'Paiement confirmé — IkelyaneMed']);
    }

    /**
     * Page d'échec.
     */
    public function failed(): string
    {
        $reason = $this->request->getGet('reason') ?? 'unknown';
        return view('payment/failed', [
            'title'  => 'Paiement échoué — IkelyaneMed',
            'reason' => $reason,
        ]);
    }
}
