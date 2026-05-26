<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrescriptionModel;
use App\Models\PrescriptionItemModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\MedicationModel;
use App\Models\TenantModel;

class PrescriptionController extends BaseController
{
    protected PrescriptionModel     $prescModel;
    protected PrescriptionItemModel $itemModel;

    public function __construct()
    {
        $this->prescModel = new PrescriptionModel();
        $this->itemModel  = new PrescriptionItemModel();
    }

    public function index(): string
    {
        return view('prescriptions/index', [
            'title'       => 'Ordonnances',
            'ordonnances' => $this->prescModel->getWithDetails($this->getTenantId()),
        ]);
    }

    public function create(int $patientId = 0): string
    {
        $tenantId = $this->getTenantId();
        return view('prescriptions/create', [
            'title'       => 'Nouvelle ordonnance',
            'patients'    => (new PatientModel())->getByTenant($tenantId),
            'medecins'    => (new DoctorModel())->getWithUser($tenantId),
            'medicaments' => (new MedicationModel())->where('actif', 1)->findAll(),
            'patient_id'  => $patientId,
        ]);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        $post     = $this->request->getPost();

        $ordonnanceId = $this->prescModel->insert([
            'tenant_id'        => $tenantId,
            'patient_id'       => $post['patient_id'],
            'medecin_id'       => $post['medecin_id'],
            'numero'           => $this->prescModel->generateNumero($tenantId),
            'date_ordonnance'  => $post['date_ordonnance'] ?? date('Y-m-d'),
            'validite_jours'   => $post['validite_jours'] ?? 30,
            'instructions'     => $post['instructions'] ?? null,
            'statut'           => 'active',
        ]);

        // Enregistrer les médicaments
        if (! empty($post['medicaments']) && is_array($post['medicaments'])) {
            foreach ($post['medicaments'] as $med) {
                if (empty($med['posologie'])) {
                    continue;
                }
                $this->itemModel->insert([
                    'ordonnance_id'    => $ordonnanceId,
                    'medicament_id'    => $med['medicament_id'] ?: null,
                    'medicament_libre' => $med['medicament_libre'] ?? null,
                    'posologie'        => $med['posologie'],
                    'frequence'        => $med['frequence'] ?? null,
                    'duree'            => $med['duree'] ?? null,
                    'quantite'         => $med['quantite'] ?? 1,
                    'instructions'     => $med['instructions'] ?? null,
                ]);
            }
        }

        $base = $this->getRole() === 'medecin' ? '/medecin' : '/admin';
        return redirect()->to($base . '/prescriptions')->with('success', 'Ordonnance créée.');
    }

    public function view(int $id): string
    {
        $ordonnance = $this->prescModel->getOneWithDetails($id);
        $base = $this->getRole() === 'medecin' ? '/medecin' : '/admin';
        if (! $ordonnance) {
            return redirect()->to($base . '/prescriptions')->with('error', 'Ordonnance introuvable.');
        }
        return view('prescriptions/view', [
            'title'      => 'Ordonnance ' . $ordonnance['numero'],
            'ordonnance' => $ordonnance,
            'items'      => $this->itemModel->getByPrescription($id),
            'base_url'   => $base,
        ]);
    }

    public function printView(int $id): string
    {
        $ordonnance = $this->prescModel->getOneWithDetails($id);
        $base = $this->getRole() === 'medecin' ? '/medecin' : '/admin';
        if (! $ordonnance) {
            return redirect()->to($base . '/prescriptions')->with('error', 'Ordonnance introuvable.');
        }

        $tenantModel = new TenantModel();
        $tenant = $tenantModel->find($this->getTenantId());

        return view('prescriptions/print', [
            'ordonnance' => $ordonnance,
            'items'      => $this->itemModel->getByPrescription($id),
            'tenant'     => $tenant,
        ]);
    }
}
