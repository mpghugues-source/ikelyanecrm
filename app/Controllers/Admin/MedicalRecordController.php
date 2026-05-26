<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MedicalRecordModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;

class MedicalRecordController extends BaseController
{
    private MedicalRecordModel $recordModel;

    public function __construct()
    {
        $this->recordModel = new MedicalRecordModel();
    }

    public function create(int $patientId): string
    {
        $patient = (new PatientModel())->find($patientId);
        if (! $patient || $patient['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/patients')->with('error', 'Patient introuvable.');
        }
        return view('medical_records/create', [
            'title'    => 'Nouvelle consultation — ' . $patient['prenom'] . ' ' . $patient['nom'],
            'patient'  => $patient,
            'medecins' => (new DoctorModel())->getWithUser($this->getTenantId()),
        ]);
    }

    public function store()
    {
        $post      = $this->request->getPost();
        $patientId = (int) ($post['patient_id'] ?? 0);

        // Resolve medecin_id for médecin users
        $medecinId = $post['medecin_id'] ?: null;
        if (! $medecinId && $this->getRole() === 'medecin') {
            $medecin   = (new DoctorModel())->findByUserId($this->getUserId());
            $medecinId = $medecin['id'] ?? null;
        }

        $this->recordModel->insert([
            'tenant_id'          => $this->getTenantId(),
            'patient_id'         => $patientId,
            'medecin_id'         => $medecinId,
            'date_consultation'  => $post['date_consultation'] ?? date('Y-m-d'),
            'motif'              => $post['motif'] ?? null,
            'symptomes'          => $post['anamnese'] ?? null,   // form: anamnese → DB: symptomes
            'examen_clinique'    => $post['examen_clinique'] ?? null,
            'diagnostic'         => $post['diagnostic'] ?? null,
            'traitement'         => $post['traitement'] ?? null,
            'observations'       => $post['notes'] ?? null,       // form: notes → DB: observations
            'poids'              => $post['poids'] ?: null,
            'taille'             => $post['taille'] ?: null,
            'tension_arterielle' => $post['tension_arterielle'] ?? null,
            'temperature'        => $post['temperature'] ?: null,
            'pouls'              => $post['pouls'] ?: null,
        ]);

        $redirect = $post['redirect_base'] ?? '/admin';
        return redirect()->to($redirect . '/patients/view/' . $patientId)
                         ->with('success', 'Consultation enregistrée.');
    }

    public function view(int $id): string
    {
        $record = $this->recordModel->find($id);
        if (! $record || $record['tenant_id'] !== $this->getTenantId()) {
            return redirect()->back()->with('error', 'Consultation introuvable.');
        }
        $patient = (new PatientModel())->find($record['patient_id']);
        return view('medical_records/view', [
            'title'   => 'Consultation du ' . date('d/m/Y', strtotime($record['date_consultation'])),
            'record'  => $record,
            'patient' => $patient,
        ]);
    }
}
