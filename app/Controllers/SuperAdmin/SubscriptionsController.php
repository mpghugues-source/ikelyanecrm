<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\SubscriptionPlanModel;

class SubscriptionsController extends BaseController
{
    private SubscriptionPlanModel $planModel;

    public function __construct()
    {
        $this->planModel = new SubscriptionPlanModel();
    }

    /** Liste des tenants avec abonnements + liste des plans */
    public function index(): string
    {
        $db = \Config\Database::connect();

        $tenants = $db->query("
            SELECT t.*,
                (SELECT COUNT(*) FROM users u WHERE u.tenant_id = t.id) as nb_users,
                (SELECT COUNT(*) FROM crm_contacts c WHERE c.tenant_id = t.id) as nb_contacts
            FROM tenants t
            ORDER BY t.plan DESC, t.expire_le ASC
        ")->getResultArray();

        $stats = [
            'starter'  => count(array_filter($tenants, fn($t) => $t['plan'] === 'starter')),
            'pro'      => count(array_filter($tenants, fn($t) => $t['plan'] === 'pro')),
            'enterprise' => count(array_filter($tenants, fn($t) => $t['plan'] === 'enterprise')),
            'expires_soon' => count(array_filter($tenants, function($t) {
                if (!$t['expire_le']) return false;
                $diff = (new \DateTime($t['expire_le']))->diff(new \DateTime())->days;
                return $diff <= 30 && $t['actif'];
            })),
        ];

        $plans = $this->planModel->orderBy('ordre')->findAll();

        return view('superadmin/subscriptions/index', [
            'title'   => lang('SuperAdmin.subscriptions_title'),
            'tenants' => $tenants,
            'stats'   => $stats,
            'plans'   => $plans,
        ]);
    }

    /** Formulaire création plan */
    public function create(): string
    {
        return view('superadmin/subscriptions/create', [
            'title' => lang('SuperAdmin.new_plan_title'),
        ]);
    }

    /** Enregistrement nouveau plan */
    public function store()
    {
        $rules = [
            'nom'          => 'required|min_length[2]|max_length[100]',
            'slug'         => 'required|min_length[2]|max_length[50]|is_unique[subscription_plans.slug]',
            'prix_mensuel' => 'required|decimal',
            'prix_annuel'  => 'required|decimal',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $features = array_filter(array_map('trim', explode("\n", $this->request->getPost('features') ?? '')));

        $this->planModel->insert([
            'nom'          => $this->request->getPost('nom'),
            'slug'         => $this->request->getPost('slug'),
            'prix_mensuel' => (float)$this->request->getPost('prix_mensuel'),
            'prix_annuel'  => (float)$this->request->getPost('prix_annuel'),
            'max_medecins' => (int)$this->request->getPost('max_medecins'),
            'max_patients' => (int)$this->request->getPost('max_patients'),
            'features'     => json_encode(array_values($features)),
            'is_active'    => (int)$this->request->getPost('is_active'),
            'ordre'        => (int)$this->request->getPost('ordre'),
        ]);

        return redirect()->to('/superadmin/subscriptions')->with('success', 'Plan créé avec succès.');
    }

    /** Formulaire modification plan */
    public function edit(int $id): string
    {
        $plan = $this->planModel->find($id);
        if (!$plan) {
            return redirect()->to('/superadmin/subscriptions')->with('error', 'Plan introuvable.');
        }

        // Décoder les features pour les afficher en textarea
        $features = '';
        if ($plan['features']) {
            $decoded = is_string($plan['features']) ? json_decode($plan['features'], true) : $plan['features'];
            $features = implode("\n", $decoded ?? []);
        }

        return view('superadmin/subscriptions/edit', [
            'title'    => lang('SuperAdmin.edit_plan_title') . ' : ' . $plan['nom'],
            'plan'     => $plan,
            'features' => $features,
        ]);
    }

    /** Mise à jour plan */
    public function update(int $id)
    {
        $plan = $this->planModel->find($id);
        if (!$plan) {
            return redirect()->to('/superadmin/subscriptions')->with('error', 'Plan introuvable.');
        }

        $rules = [
            'nom'          => 'required|min_length[2]|max_length[100]',
            'prix_mensuel' => 'required|decimal',
            'prix_annuel'  => 'required|decimal',
        ];
        // Validation slug unique sauf pour le plan courant
        $slugPost = $this->request->getPost('slug');
        if ($slugPost !== $plan['slug']) {
            $rules['slug'] = 'required|min_length[2]|max_length[50]|is_unique[subscription_plans.slug]';
        }
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $features = array_filter(array_map('trim', explode("\n", $this->request->getPost('features') ?? '')));

        $this->planModel->update($id, [
            'nom'          => $this->request->getPost('nom'),
            'slug'         => $slugPost,
            'prix_mensuel' => (float)$this->request->getPost('prix_mensuel'),
            'prix_annuel'  => (float)$this->request->getPost('prix_annuel'),
            'max_medecins' => (int)$this->request->getPost('max_medecins'),
            'max_patients' => (int)$this->request->getPost('max_patients'),
            'features'     => json_encode(array_values($features)),
            'is_active'    => (int)$this->request->getPost('is_active'),
            'ordre'        => (int)$this->request->getPost('ordre'),
        ]);

        return redirect()->to('/superadmin/subscriptions')->with('success', 'Plan mis à jour.');
    }

    /** Suppression plan */
    public function delete(int $id)
    {
        $plan = $this->planModel->find($id);
        if (!$plan) {
            return redirect()->to('/superadmin/subscriptions')->with('error', 'Plan introuvable.');
        }
        $this->planModel->delete($id);
        return redirect()->to('/superadmin/subscriptions')->with('success', 'Plan supprimé.');
    }

    /** Activer / désactiver un plan */
    public function toggle(int $id)
    {
        $plan = $this->planModel->find($id);
        if (!$plan) {
            return redirect()->to('/superadmin/subscriptions')->with('error', 'Plan introuvable.');
        }
        $this->planModel->update($id, ['is_active' => $plan['is_active'] ? 0 : 1]);
        return redirect()->to('/superadmin/subscriptions')->with('success', 'Statut du plan modifié.');
    }
}
