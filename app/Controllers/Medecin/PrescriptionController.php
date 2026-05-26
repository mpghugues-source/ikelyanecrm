<?php

namespace App\Controllers\Medecin;

use App\Controllers\Admin\PrescriptionController as AdminPrescriptionController;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\MedicationModel;

class PrescriptionController extends AdminPrescriptionController
{
    private function getMedecinId(): int
    {
        $medecin = (new DoctorModel())->findByUserId($this->getUserId());
        return $medecin['id'] ?? 0;
    }

    public function index(): string
    {
        return view('prescriptions/index', [
            'title'       => 'Mes ordonnances',
            'ordonnances' => $this->prescModel->getWithDetails($this->getTenantId(), $this->getMedecinId()),
            'base_url'    => '/medecin',
        ]);
    }

    public function create(int $patientId = 0): string
    {
        $tenantId  = $this->getTenantId();
        $medecinId = $this->getMedecinId();
        return view('prescriptions/create', [
            'title'       => 'Nouvelle ordonnance',
            'patients'    => (new PatientModel())->getByTenant($tenantId),
            'medecins'    => (new DoctorModel())->getWithUser($tenantId),
            'medicaments' => (new MedicationModel())->where('actif', 1)->findAll(),
            'patient_id'  => $patientId,
            'medecin_id'  => $medecinId,
            'base_url'    => '/medecin',
        ]);
    }

    public function store()
    {
        $medecinId = $this->getMedecinId();
        $tenantId  = $this->getTenantId();
        $post      = $this->request->getPost();

        // Force medecin_id to the authenticated doctor — prevent privilege escalation
        $post['medecin_id'] = $medecinId;

        $ordonnanceId = $this->prescModel->insert([
            'tenant_id'       => $tenantId,
            'patient_id'      => $post['patient_id'],
            'medecin_id'      => $medecinId,
            'numero'          => $this->prescModel->generateNumero($tenantId),
            'date_ordonnance' => $post['date_ordonnance'] ?? date('Y-m-d'),
            'validite_jours'  => $post['validite_jours'] ?? 30,
            'instructions'    => $post['instructions'] ?? null,
            'statut'          => 'active',
        ]);

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

        return redirect()->to('/medecin/prescriptions')->with('success', 'Ordonnance créée.');
    }
}
