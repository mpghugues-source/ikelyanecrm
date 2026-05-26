<?php

namespace App\Controllers\Patient;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\SpecialtyModel;

class AppointmentController extends BaseController
{
    public function index(): string
    {
        $userId   = $this->getUserId();
        $patient  = (new PatientModel())->where('user_id', $userId)->first();

        $rdvs = [];
        if ($patient) {
            $rdvs = (new AppointmentModel())->getWithDetails($this->getTenantId(), ['patient_id' => $patient['id']]);
        }

        return view('patient/appointments', [
            'title'  => 'Mes rendez-vous',
            'rdvs'   => $rdvs,
            'patient'=> $patient,
        ]);
    }

    public function book(): string
    {
        $tenantId = $this->getTenantId();
        return view('patient/book_appointment', [
            'title'      => 'Prendre un rendez-vous',
            'specialites'=> (new SpecialtyModel())->getAll(),
            'medecins'   => (new DoctorModel())->getWithUser($tenantId),
        ]);
    }

    public function store()
    {
        $userId   = $this->getUserId();
        $patient  = (new PatientModel())->where('user_id', $userId)->first();

        if (! $patient) {
            return redirect()->back()->with('error', 'Profil patient non trouvé.');
        }

        $post = $this->request->getPost();
        (new AppointmentModel())->insert([
            'tenant_id'  => $this->getTenantId(),
            'patient_id' => $patient['id'],
            'medecin_id' => $post['medecin_id'],
            'date_rdv'   => $post['date_rdv'],
            'heure_rdv'  => $post['heure_rdv'],
            'motif'      => $post['motif'] ?? null,
            'type_rdv'   => $post['type_rdv'] ?? 'consultation',
            'statut'     => 'planifie',
        ]);

        return redirect()->to('/patient/appointments')->with('success', 'Rendez-vous demandé avec succès.');
    }
}
