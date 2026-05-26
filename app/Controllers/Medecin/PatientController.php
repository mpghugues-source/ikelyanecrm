<?php

namespace App\Controllers\Medecin;

use App\Controllers\BaseController;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\MedicalRecordModel;
use App\Models\PrescriptionModel;
use App\Models\InvoiceModel;

class PatientController extends BaseController
{
    public function index(): string
    {
        $search = $this->request->getGet('q') ?? '';
        return view('patients/index', [
            'title'    => 'Mes patients',
            'patients' => (new PatientModel())->getByTenant($this->getTenantId(), $search),
            'search'   => $search,
            'base_url' => '/medecin',
        ]);
    }

    public function view(int $id): string
    {
        $patient = (new PatientModel())->find($id);
        if (! $patient || $patient['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/medecin/patients')->with('error', 'Patient introuvable.');
        }
        return view('patients/view', [
            'title'       => 'Dossier: ' . $patient['prenom'] . ' ' . $patient['nom'],
            'patient'     => $patient,
            'rdvs'        => (new AppointmentModel())->getWithDetails($this->getTenantId(), ['patient_id' => $id]),
            'dossiers'    => (new MedicalRecordModel())->getByPatient($id),
            'ordonnances' => (new PrescriptionModel())->getByPatient($id),
            'factures'    => (new InvoiceModel())->getByPatient($id),
            'base_url'    => '/medecin',
        ]);
    }
}
