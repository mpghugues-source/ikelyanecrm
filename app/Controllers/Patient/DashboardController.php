<?php

namespace App\Controllers\Patient;

use App\Controllers\BaseController;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\PrescriptionModel;
use App\Models\InvoiceModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();

        $patientModel = new PatientModel();
        $patient      = $patientModel->where('user_id', $userId)->first();

        $rdvModel    = new AppointmentModel();
        $prescModel  = new PrescriptionModel();
        $invoiceModel= new InvoiceModel();

        $data = [
            'title'      => 'Mon espace patient',
            'patient'    => $patient,
            'rdvs'       => $patient ? $rdvModel->getWithDetails($tenantId, ['patient_id' => $patient['id']]) : [],
            'ordonnances'=> $patient ? $prescModel->getByPatient($patient['id']) : [],
            'factures'   => $patient ? $invoiceModel->getByPatient($patient['id']) : [],
        ];

        return view('dashboard/patient', $data);
    }
}
