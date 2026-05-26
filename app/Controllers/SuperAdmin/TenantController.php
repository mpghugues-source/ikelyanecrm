<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\TenantModel;
use App\Models\UserModel;
use App\Models\SubscriptionPlanModel;

class TenantController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $tenants = $db->query("
            SELECT t.*,
                (SELECT COUNT(*) FROM users u WHERE u.tenant_id = t.id) as nb_users,
                (SELECT COUNT(*) FROM crm_contacts c WHERE c.tenant_id = t.id) as nb_contacts,
                (SELECT COUNT(*) FROM crm_leads l WHERE l.tenant_id = t.id) as nb_leads
            FROM tenants t
            ORDER BY t.created_at DESC
        ")->getResultArray();

        return view('superadmin/tenants/index', [
            'title'   => lang('SuperAdmin.orgs_manage'),
            'tenants' => $tenants,
        ]);
    }

    public function view(int $id): string
    {
        $tenantModel = new TenantModel();
        $userModel   = new UserModel();
        $db          = \Config\Database::connect();

        $tenant = $tenantModel->find($id);
        if (!$tenant) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $users    = $userModel->where('tenant_id', $id)->findAll();
        $contacts = $db->table('crm_contacts')->where('tenant_id', $id)->countAll();
        $leads    = $db->table('crm_leads')->where('tenant_id', $id)->countAll();
        $planModel = new SubscriptionPlanModel();
        $plans     = $planModel->where('is_active', 1)->orderBy('ordre')->findAll();

        return view('superadmin/tenants/view', [
            'title'    => lang('SuperAdmin.view_org') . ' — ' . $tenant['nom'],
            'tenant'   => $tenant,
            'users'    => $users,
            'contacts' => $contacts,
            'leads'    => $leads,
            'plans'    => $plans,
        ]);
    }

    public function toggleStatus(int $id)
    {
        $tenantModel = new TenantModel();
        $tenant = $tenantModel->find($id);
        if (!$tenant) return redirect()->to('/superadmin/tenants')->with('error', 'Organisation introuvable.');

        $tenantModel->update($id, ['actif' => $tenant['actif'] ? 0 : 1]);
        $msg = $tenant['actif'] ? 'Organisation désactivée.' : 'Organisation activée.';
        return redirect()->to('/superadmin/tenants')->with('success', $msg);
    }

    public function updateSubscription(int $id)
    {
        $tenantModel = new TenantModel();
        $plan   = $this->request->getPost('plan');
        $expire = $this->request->getPost('expire_le');

        $tenantModel->update($id, [
            'plan'      => $plan,
            'expire_le' => $expire ?: null,
        ]);

        return redirect()->to('/superadmin/tenants/view/' . $id)->with('success', 'Abonnement mis à jour.');
    }

    public function create(): string
    {
        return view('superadmin/tenants/create', [
            'title' => lang('SuperAdmin.new_org'),
        ]);
    }

    public function store()
    {
        $rules = [
            'nom'   => 'required|min_length[3]',
            'email' => 'required|valid_email',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        $tenantModel = new TenantModel();
        $raw  = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $post['nom']);
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $raw), '-'));
        $base = $slug; $i = 1;
        while ($tenantModel->where('slug', $slug)->first()) {
            $slug = $base . '-' . $i++;
        }

        $tenantId = $tenantModel->insert([
            'nom'       => $post['nom'],
            'slug'      => $slug,
            'adresse'   => $post['adresse'] ?? null,
            'telephone' => $post['telephone'] ?? null,
            'email'     => $post['email'],
            'ville'     => $post['ville'] ?? null,
            'pays'      => $post['pays'] ?? 'Algérie',
            'couleur'   => $post['couleur'] ?? '#059669',
            'plan'      => $post['plan'] ?? 'starter',
            'expire_le' => $post['expire_le'] ?: null,
            'actif'     => 1,
        ]);

        if (! empty($post['admin_email'])) {
            $userModel = new UserModel();
            $userModel->insert([
                'tenant_id'    => $tenantId,
                'nom'          => $post['admin_nom'] ?? 'Administrateur',
                'prenom'       => $post['admin_prenom'] ?? '',
                'email'        => $post['admin_email'],
                'mot_de_passe' => password_hash($post['admin_password'] ?? 'Admin@2024', PASSWORD_DEFAULT),
                'role'         => 'admin',
                'telephone'    => $post['admin_telephone'] ?? null,
                'actif'        => 1,
            ]);
        }

        return redirect()->to('/superadmin/tenants')->with('success', 'Organisation « ' . $post['nom'] . ' » créée avec succès.');
    }

    public function edit(int $id): string
    {
        $tenantModel = new TenantModel();
        $tenant = $tenantModel->find($id);
        if (! $tenant) return redirect()->to('/superadmin/tenants')->with('error', 'Organisation introuvable.');

        return view('superadmin/tenants/edit', [
            'title'  => lang('SuperAdmin.edit_org') . ' — ' . $tenant['nom'],
            'tenant' => $tenant,
        ]);
    }

    public function update(int $id)
    {
        $post        = $this->request->getPost();
        $tenantModel = new TenantModel();

        $tenantModel->update($id, [
            'nom'       => $post['nom'],
            'adresse'   => $post['adresse'] ?? null,
            'telephone' => $post['telephone'] ?? null,
            'email'     => $post['email'] ?? null,
            'ville'     => $post['ville'] ?? null,
            'pays'      => $post['pays'] ?? 'Algérie',
            'couleur'   => $post['couleur'] ?? '#059669',
        ]);

        return redirect()->to('/superadmin/tenants/view/' . $id)->with('success', 'Informations mises à jour.');
    }

    public function delete(int $id)
    {
        $tenantModel = new TenantModel();
        $tenantModel->update($id, ['actif' => 0]);
        return redirect()->to('/superadmin/tenants')->with('success', 'Organisation désactivée avec succès.');
    }
}
