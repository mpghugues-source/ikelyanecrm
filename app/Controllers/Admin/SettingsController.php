<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TenantModel;
use App\Models\ServiceModel;

class SettingsController extends BaseController
{
    public function index(): string
    {
        $tenantModel = new TenantModel();
        $tenant = $tenantModel->find($this->getTenantId());

        $db = \Config\Database::connect();
        $specialites = $db->table('specialites')->orderBy('nom')->get()->getResultArray();

        return view('admin/settings/index', [
            'title'       => 'Paramètres',
            'tenant'      => $tenant,
            'services'    => (new ServiceModel())->getByTenant($this->getTenantId()),
            'specialites' => $specialites,
        ]);
    }

    public function save()
    {
        $post = $this->request->getPost();
        $tenantModel = new TenantModel();
        $tenantModel->update($this->getTenantId(), [
            'nom'       => $post['nom'] ?? '',
            'adresse'   => $post['adresse'] ?? null,
            'telephone' => $post['telephone'] ?? null,
            'email'     => $post['email'] ?? null,
            'ville'     => $post['ville'] ?? null,
            'couleur'   => $post['couleur'] ?? '#059669',
        ]);
        return redirect()->back()->with('success', 'Paramètres enregistrés.');
    }

    public function addService()
    {
        $post = $this->request->getPost();
        if (! empty($post['nom']) && ! empty($post['prix'])) {
            (new ServiceModel())->insert([
                'tenant_id' => $this->getTenantId(),
                'nom'       => $post['nom'],
                'code'      => strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $post['nom']), 0, 10)),
                'prix'      => (float) $post['prix'],
                'actif'     => 1,
            ]);
            return redirect()->back()->with('success', 'Service ajouté.');
        }
        return redirect()->back()->with('error', 'Nom et prix requis.');
    }

    public function deleteService(int $id)
    {
        $service = (new ServiceModel())->find($id);
        if ($service && $service['tenant_id'] === $this->getTenantId()) {
            (new ServiceModel())->update($id, ['actif' => 0]);
        }
        return redirect()->back()->with('success', 'Service supprimé.');
    }
}
