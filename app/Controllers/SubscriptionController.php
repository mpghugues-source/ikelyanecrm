<?php

namespace App\Controllers;

use App\Libraries\FlutterwaveService;
use App\Models\PaymentModel;
use App\Models\SubscriptionPlanModel;
use App\Models\TenantModel;
use App\Models\UserModel;

class SubscriptionController extends BaseController
{
    private function getPlans(): array
    {
        return (new SubscriptionPlanModel())->getPlansForCheckout();
    }

    /**
     * Page affichée quand l'abonnement est expiré (après la période de grâce).
     */
    public function expired(): string
    {
        $tenantId = (int) session()->get('tenant_id');
        $db       = \Config\Database::connect();
        $tenant   = $db->table('tenants')
                       ->where('id', $tenantId)
                       ->get()
                       ->getRowArray();

        return view('subscription/expired', [
            'title'  => 'Abonnement expiré — IkelyaneMed',
            'tenant' => $tenant,
            'plans'  => $this->getPlans(),
        ]);
    }

    /**
     * Page affichée quand le compte est désactivé manuellement.
     */
    public function inactive(): string
    {
        return view('subscription/inactive', [
            'title' => 'Compte désactivé — IkelyaneMed',
        ]);
    }

    /**
     * Lance le flux de renouvellement : crée un paiement pending et redirige vers checkout.
     */
    public function renew()
    {
        $tenantId = (int) session()->get('tenant_id');
        $planSlug = $this->request->getPost('plan') ?? 'basic';
        $cycle    = $this->request->getPost('cycle') ?? 'monthly';

        $plans = $this->getPlans();

        if (! in_array($planSlug, array_keys($plans))) {
            $planSlug = 'basic';
        }
        if (! in_array($cycle, ['monthly', 'annual'])) {
            $cycle = 'monthly';
        }

        $tenantModel  = new TenantModel();
        $userModel    = new UserModel();
        $paymentModel = new PaymentModel();

        $tenant = $tenantModel->find($tenantId);
        $user   = $userModel->where('tenant_id', $tenantId)->where('role', 'admin')->first();

        if (! $tenant || ! $user) {
            return redirect()->back()->with('error', 'Erreur lors du renouvellement.');
        }

        $plan   = $plans[$planSlug];
        $amount = $cycle === 'annual' ? $plan['annuel'] : $plan['mensuel'];
        $txRef  = FlutterwaveService::generateTxRef($tenantId);

        // Annuler les paiements pending précédents pour ce tenant
        $paymentModel->where('tenant_id', $tenantId)
                     ->where('status', 'pending')
                     ->set(['status' => 'cancelled'])
                     ->update();

        $paymentModel->insert([
            'tenant_id'      => $tenantId,
            'plan_slug'      => $planSlug,
            'billing_cycle'  => $cycle,
            'amount'         => $amount,
            'currency'       => 'EUR',
            'status'         => 'pending',
            'tx_ref'         => $txRef,
            'customer_email' => $user['email'],
            'customer_name'  => $tenant['nom'],
        ]);

        session()->set('pending_payment', [
            'tenant_id'  => $tenantId,
            'plan_slug'  => $planSlug,
            'tenant_nom' => $tenant['nom'],
            'email'      => $user['email'],
        ]);
        session()->set('billing_cycle', $cycle);

        return redirect()->to('/payment/checkout');
    }
}
