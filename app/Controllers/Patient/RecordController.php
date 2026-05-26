<?php

namespace App\Controllers\Patient;

use App\Controllers\BaseController;
use App\Models\PatientModel;
use App\Models\MedicalRecordModel;
use App\Models\PrescriptionModel;
use App\Models\PrescriptionItemModel;
use App\Models\InvoiceModel;

class RecordController extends BaseController
{
    private function getPatient(): ?array
    {
        return (new PatientModel())->where('user_id', $this->getUserId())->first();
    }

    public function index(): string
    {
        \Config\Services::language()->setLocale('en');
        $patient = $this->getPatient();
        return view('patient/records', [
            'title'   => 'My Medical Record',
            'patient' => $patient,
            'dossiers'=> $patient ? (new MedicalRecordModel())->getByPatient($patient['id']) : [],
        ]);
    }

    public function prescriptions(): string
    {
        \Config\Services::language()->setLocale('en');
        $patient = $this->getPatient();
        return view('patient/prescriptions', [
            'title'      => 'My Prescriptions',
            'patient'    => $patient,
            'ordonnances'=> $patient ? (new PrescriptionModel())->getByPatient($patient['id']) : [],
        ]);
    }

    public function invoices(): string
    {
        $patient = $this->getPatient();
        return view('patient/invoices', [
            'title'   => 'Mes factures',
            'patient' => $patient,
            'factures'=> $patient ? (new InvoiceModel())->getByPatient($patient['id']) : [],
        ]);
    }
}
