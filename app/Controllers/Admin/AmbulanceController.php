<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AmbulanceModel;

class AmbulanceController extends BaseController
{
    private AmbulanceModel $model;

    public function __construct()
    {
        $this->model = new AmbulanceModel();
    }

    public function index(): string
    {
        $tenantId  = $this->getTenantId();
        $vehicules = $this->model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('immatriculation')->findAll();
        $stats = [
            'disponible'   => 0, 'en_mission' => 0,
            'maintenance'  => 0, 'hors_service' => 0,
        ];
        foreach ($vehicules as $v) {
            if (isset($stats[$v['statut']])) $stats[$v['statut']]++;
        }
        return view('ambulance/index', ['title' => 'Parc Ambulances', 'vehicules' => $vehicules, 'stats' => $stats]);
    }

    public function create(): string
    {
        return view('ambulance/create', ['title' => 'Ajouter un véhicule']);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        if (!$this->validate(['immatriculation' => 'required|max_length[50]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert([
            'tenant_id'       => $tenantId,
            'immatriculation' => $this->request->getPost('immatriculation'),
            'modele'          => $this->request->getPost('modele'),
            'type_vehicule'   => $this->request->getPost('type_vehicule'),
            'statut'          => $this->request->getPost('statut') ?: 'disponible',
            'chauffeur_nom'   => $this->request->getPost('chauffeur_nom'),
            'chauffeur_tel'   => $this->request->getPost('chauffeur_tel'),
            'date_revision'   => $this->request->getPost('date_revision') ?: null,
            'notes'           => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/ambulances')->with('success', 'Véhicule ajouté.');
    }

    public function view(int $id): string
    {
        $tenantId = $this->getTenantId();
        $vehicule = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$vehicule) return redirect()->to('/admin/ambulances')->with('error', 'Introuvable.');
        return view('ambulance/view', ['title' => 'Ambulance : ' . $vehicule['immatriculation'], 'vehicule' => $vehicule]);
    }

    public function edit(int $id): string
    {
        $tenantId = $this->getTenantId();
        $vehicule = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$vehicule) return redirect()->to('/admin/ambulances')->with('error', 'Introuvable.');
        return view('ambulance/edit', ['title' => 'Modifier le véhicule', 'vehicule' => $vehicule]);
    }

    public function update(int $id)
    {
        $tenantId = $this->getTenantId();
        $vehicule = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$vehicule) return redirect()->to('/admin/ambulances')->with('error', 'Introuvable.');
        $this->model->update($id, [
            'immatriculation' => $this->request->getPost('immatriculation'),
            'modele'          => $this->request->getPost('modele'),
            'type_vehicule'   => $this->request->getPost('type_vehicule'),
            'statut'          => $this->request->getPost('statut'),
            'chauffeur_nom'   => $this->request->getPost('chauffeur_nom'),
            'chauffeur_tel'   => $this->request->getPost('chauffeur_tel'),
            'date_revision'   => $this->request->getPost('date_revision') ?: null,
            'notes'           => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/ambulances')->with('success', 'Véhicule mis à jour.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->getTenantId();
        $this->model->where('tenant_id', $tenantId)->update($id, ['actif' => 0]);
        return redirect()->to('/admin/ambulances')->with('success', 'Véhicule supprimé.');
    }
}
