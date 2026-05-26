<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\DoctorScheduleModel;
use App\Models\NotificationModel;

class AppointmentController extends BaseController
{
    private AppointmentModel $rdvModel;

    public function __construct()
    {
        $this->rdvModel = new AppointmentModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $filters  = $this->request->getGet() ?? [];

        return view('appointments/index', [
            'title'    => 'Rendez-vous',
            'rdvs'     => $this->rdvModel->getWithDetails($tenantId, $filters),
            'medecins' => (new DoctorModel())->getWithUser($tenantId),
            'filters'  => $filters,
            'base_url' => '/admin',
        ]);
    }

    public function calendar(): string
    {
        return view('appointments/calendar', [
            'title'    => 'Calendrier des rendez-vous',
            'medecins' => (new DoctorModel())->getWithUser($this->getTenantId()),
        ]);
    }

    public function create(): string
    {
        $tenantId = $this->getTenantId();
        return view('appointments/create', [
            'title'    => 'Nouveau rendez-vous',
            'patients' => (new PatientModel())->getByTenant($tenantId),
            'medecins' => (new DoctorModel())->getWithUser($tenantId),
        ]);
    }

    public function store()
    {
        $rules = [
            'patient_id' => 'required|integer',
            'medecin_id' => 'required|integer',
            'date_rdv'   => 'required|valid_date',
            'heure_rdv'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post     = $this->request->getPost();
        $tenantId = $this->getTenantId();

        // Vérification anti double-booking
        $scheduleModel = new DoctorScheduleModel();
        $duree         = (int)($post['duree'] ?? 30);
        $conflict      = $scheduleModel->checkConflict(
            (int)$post['medecin_id'],
            $post['date_rdv'],
            $post['heure_rdv'],
            $duree
        );

        if ($conflict) {
            return redirect()->back()->withInput()->with('error', $conflict);
        }

        $post['tenant_id'] = $tenantId;
        $post['statut']    = 'planifie';

        $rdvId = $this->rdvModel->insert($post);

        // Notification in-app
        $patientModel = new PatientModel();
        $patient      = $patientModel->find($post['patient_id']);
        $patientNom   = $patient ? $patient['prenom'].' '.$patient['nom'] : 'Patient';
        $dateF        = date('d/m/Y', strtotime($post['date_rdv']));
        $heureF       = substr($post['heure_rdv'], 0, 5);

        NotificationModel::notify(
            $tenantId,
            'rdv_cree',
            'Nouveau rendez-vous',
            "{$patientNom} — {$dateF} à {$heureF}",
            '/admin/appointments/view/' . $rdvId,
            'bi-calendar-plus',
            'primary'
        );

        return redirect()->to('/admin/appointments')->with('success', 'Rendez-vous créé.');
    }

    public function view(int $id): string
    {
        $rdv = $this->rdvModel->getOneWithDetails($id);
        if (! $rdv || $rdv['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/appointments')->with('error', 'RDV introuvable.');
        }
        return view('appointments/view', ['title' => 'Rendez-vous', 'rdv' => $rdv]);
    }

    public function edit(int $id): string
    {
        $tenantId = $this->getTenantId();
        $rdv      = $this->rdvModel->getOneWithDetails($id);
        if (! $rdv || $rdv['tenant_id'] !== $tenantId) {
            return redirect()->to('/admin/appointments')->with('error', 'RDV introuvable.');
        }
        return view('appointments/edit', [
            'title'    => 'Modifier le rendez-vous',
            'rdv'      => $rdv,
            'patients' => (new PatientModel())->getByTenant($tenantId),
            'medecins' => (new DoctorModel())->getWithUser($tenantId),
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'patient_id' => 'required|integer',
            'medecin_id' => 'required|integer',
            'date_rdv'   => 'required|valid_date',
            'heure_rdv'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost(['patient_id','medecin_id','date_rdv','heure_rdv','duree','motif','notes','statut','type_consultation']);
        $this->rdvModel->update($id, $data);
        return redirect()->to('/admin/appointments/view/' . $id)->with('success', 'Rendez-vous mis à jour.');
    }

    public function updateStatus(int $id)
    {
        $json    = $this->request->getJSON(true);
        $statut  = $json['statut'] ?? $this->request->getPost('statut');
        $allowed = ['planifie','confirme','en_cours','termine','annule','absent'];

        if (in_array($statut, $allowed)) {
            $this->rdvModel->update($id, ['statut' => $statut]);
            return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setStatusCode(400)->setJSON(['error' => 'Statut invalide']);
    }

    public function delete(int $id)
    {
        $this->rdvModel->update($id, ['statut' => 'annule']);
        return redirect()->to('/admin/appointments')->with('success', 'Rendez-vous annulé.');
    }

    public function getJson()
    {
        $medecinId = $this->request->getGet('medecin_id') ? (int)$this->request->getGet('medecin_id') : null;
        $events    = $this->rdvModel->getForCalendar($this->getTenantId(), $medecinId);
        return $this->response->setJSON($events);
    }
}
