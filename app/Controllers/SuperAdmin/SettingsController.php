<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $platformTenant = $db->table('tenants')->where('slug', 'ikelyanemed')->get()->getRowArray();

        return view('superadmin/settings/index', [
            'title'          => 'Paramètres — Super Admin',
            'platformTenant' => $platformTenant,
        ]);
    }

    public function save()
    {
        $post = $this->request->getPost();

        // Mettre à jour le tenant plateforme
        if (! empty($post['platform_nom'])) {
            $db = \Config\Database::connect();
            $db->table('tenants')
               ->where('slug', 'ikelyanemed')
               ->update([
                   'nom'       => $post['platform_nom'],
                   'email'     => $post['platform_email'] ?? null,
                   'telephone' => $post['platform_telephone'] ?? null,
                   'adresse'   => $post['platform_adresse'] ?? null,
               ]);
        }

        return redirect()->to('/superadmin/settings')->with('success', 'Paramètres enregistrés.');
    }
}
