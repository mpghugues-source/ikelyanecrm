<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DoctorScheduleModel;
use App\Models\DoctorModel;

class AvailabilityController extends BaseController
{
    public function index(): string
    {
        $tenantId    = session()->get('tenant_id');
        $doctorModel = new DoctorModel();

        $doctors = $doctorModel
            ->select('medecins.*, users.nom, users.prenom, specialites.nom AS specialite_nom')
            ->join('users', 'users.id = medecins.user_id')
            ->join('specialites', 'specialites.id = medecins.specialite_id', 'left')
            ->where('medecins.tenant_id', $tenantId)
            ->where('medecins.actif', 1)
            ->findAll();

        $scheduleModel = new DoctorScheduleModel();
        $schedules = $scheduleModel
            ->where('tenant_id', $tenantId)
            ->where('date_fin >', date('Y-m-d H:i:s'))
            ->orderBy('date_debut', 'ASC')
            ->findAll();

        return view('availability/index', [
            'title'     => 'Disponibilités médecins',
            'doctors'   => $doctors,
            'schedules' => $schedules,
        ]);
    }

    public function store()
    {
        $tenantId = session()->get('tenant_id');
        $rules = [
            'medecin_id' => 'required|integer',
            'date_debut' => 'required',
            'date_fin'   => 'required',
            'type'       => 'required|in_list[indisponible,conge,formation,autre]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $scheduleModel = new DoctorScheduleModel();
        $scheduleModel->insert([
            'medecin_id' => $this->request->getPost('medecin_id'),
            'tenant_id'  => $tenantId,
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin'   => $this->request->getPost('date_fin'),
            'type'       => $this->request->getPost('type'),
            'motif'      => $this->request->getPost('motif'),
        ]);

        return redirect()->to('/admin/availability')->with('success', 'Indisponibilité ajoutée.');
    }

    public function delete(int $id)
    {
        $tenantId      = session()->get('tenant_id');
        $scheduleModel = new DoctorScheduleModel();
        $schedule      = $scheduleModel->find($id);

        if (!$schedule || $schedule['tenant_id'] != $tenantId) {
            return redirect()->to('/admin/availability')->with('error', 'Introuvable.');
        }

        $scheduleModel->delete($id);
        return redirect()->to('/admin/availability')->with('success', 'Indisponibilité supprimée.');
    }

    /** API : créneaux disponibles pour un médecin à une date */
    public function slotsJson(int $medecinId)
    {
        $date          = $this->request->getGet('date') ?? date('Y-m-d');
        $scheduleModel = new DoctorScheduleModel();
        $slots         = $scheduleModel->getAvailableSlots($medecinId, $date);

        return $this->response->setJSON($slots);
    }

    /** API : vérifier conflit avant enregistrement RDV */
    public function checkConflict()
    {
        $medecinId = (int)$this->request->getPost('medecin_id');
        $date      = $this->request->getPost('date');
        $heure     = $this->request->getPost('heure');
        $duree     = (int)($this->request->getPost('duree') ?: 30);
        $excludeId = (int)($this->request->getPost('rdv_id') ?: 0);

        $scheduleModel = new DoctorScheduleModel();
        $error         = $scheduleModel->checkConflict($medecinId, $date, $heure, $duree, $excludeId ?: null);

        return $this->response->setJSON([
            'available' => $error === null,
            'message'   => $error,
        ]);
    }
}
