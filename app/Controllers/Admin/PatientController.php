<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PlanLimitService;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\MedicalRecordModel;
use App\Models\PrescriptionModel;
use App\Models\InvoiceModel;

class PatientController extends BaseController
{
    private PatientModel $patientModel;

    public function __construct()
    {
        $this->patientModel = new PatientModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $search   = $this->request->getGet('q') ?? '';
        $usage    = (new PlanLimitService())->getPatientUsage($tenantId);

        return view('patients/index', [
            'title'    => 'Gestion des patients',
            'patients' => $this->patientModel->getByTenant($tenantId, $search),
            'search'   => $search,
            'base_url' => '/admin',
            'usage'    => $usage,
        ]);
    }

    public function create(): string
    {
        $tenantId = $this->getTenantId();
        $limit    = new PlanLimitService();

        if ($error = $limit->checkCanAddPatient($tenantId)) {
            return redirect()->to('/admin/patients')->with('error', $error);
        }

        return view('patients/create', [
            'title' => 'Nouveau patient',
            'usage' => $limit->getPatientUsage($tenantId),
        ]);
    }

    public function store()
    {
        $rules = [
            'nom'    => 'required|min_length[2]',
            'prenom' => 'required|min_length[2]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tenantId = $this->getTenantId();

        // Vérification limite plan (double-check serveur)
        if ($error = (new PlanLimitService())->checkCanAddPatient($tenantId)) {
            return redirect()->to('/admin/patients')->with('error', $error);
        }

        $data = $this->request->getPost();
        $data['tenant_id']      = $tenantId;
        $data['numero_dossier'] = $this->patientModel->generateNumero($tenantId);

        $this->patientModel->insert($data);

        return redirect()->to('/admin/patients')->with('success', 'Patient créé avec succès.');
    }

    public function view(int $id): string
    {
        $patient = $this->patientModel->find($id);
        if (! $patient || $patient['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/patients')->with('error', 'Patient introuvable.');
        }

        $rdvModel    = new AppointmentModel();
        $recordModel = new MedicalRecordModel();
        $prescModel  = new PrescriptionModel();
        $invModel    = new InvoiceModel();

        return view('patients/view', [
            'title'        => 'Dossier patient — ' . $patient['prenom'] . ' ' . $patient['nom'],
            'patient'      => $patient,
            'rdvs'         => $rdvModel->getWithDetails($this->getTenantId(), ['patient_id' => $id]),
            'dossiers'     => $recordModel->getByPatient($id),
            'ordonnances'  => $prescModel->getByPatient($id),
            'factures'     => $invModel->getByPatient($id),
        ]);
    }

    public function edit(int $id): string
    {
        $patient = $this->patientModel->find($id);
        if (! $patient || $patient['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/patients')->with('error', 'Patient introuvable.');
        }
        return view('patients/edit', ['title' => 'Modifier patient', 'patient' => $patient]);
    }

    public function update(int $id)
    {
        $patient = $this->patientModel->find($id);
        if (! $patient || $patient['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/patients')->with('error', 'Patient introuvable.');
        }

        $data = $this->request->getPost();
        unset($data['numero_dossier'], $data['tenant_id']);

        $this->patientModel->update($id, $data);
        return redirect()->to('/admin/patients')->with('success', 'Patient mis à jour.');
    }

    public function delete(int $id)
    {
        $patient = $this->patientModel->find($id);
        if ($patient && $patient['tenant_id'] === $this->getTenantId()) {
            $this->patientModel->update($id, ['actif' => 0]);
        }
        return redirect()->to('/admin/patients')->with('success', 'Patient archivé.');
    }
}
