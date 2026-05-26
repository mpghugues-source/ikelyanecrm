<?php

namespace App\Controllers\Medecin;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\DoctorScheduleModel;
use CodeIgniter\HTTP\RedirectResponse;

class AppointmentController extends BaseController
{
    private AppointmentModel $rdvModel;
    private DoctorModel $doctorModel;

    public function __construct()
    {
        $this->rdvModel    = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
    }

    private function getMedecin(): array
    {
        return $this->doctorModel->findByUserId($this->getUserId()) ?? [];
    }

    public function index(): string
    {
        $tenantId  = $this->getTenantId();
        $medecin   = $this->getMedecin();
        $medecinId = $medecin['id'] ?? 0;
        $filters   = $this->request->getGet() ?? [];

        return view('medecin/appointments', [
            'title'    => 'Mes rendez-vous',
            'rdvs'     => $this->rdvModel->getWithDetails($tenantId, array_merge($filters, ['medecin_id' => $medecinId])),
            'medecins' => [$medecin],
            'filters'  => $filters,
        ]);
    }

    public function calendar(): string
    {
        $medecin = $this->getMedecin();
        return view('appointments/calendar', [
            'title'      => 'Mon calendrier',
            'medecins'   => [$medecin],
            'medecin_id' => $medecin['id'] ?? 0,
            'base_url'   => '/medecin',
        ]);
    }

    public function create(): string
    {
        $tenantId = $this->getTenantId();
        $medecin  = $this->getMedecin();
        return view('appointments/create', [
            'title'    => 'Nouveau rendez-vous',
            'patients' => (new PatientModel())->getByTenant($tenantId),
            'medecins' => [$medecin],
            'base_url' => '/medecin',
        ]);
    }

    public function store(): RedirectResponse
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

        $this->rdvModel->insert($post);

        return redirect()->to('/medecin/appointments')->with('success', 'Rendez-vous créé.');
    }

    public function view(int $id): string|RedirectResponse
    {
        $tenantId = $this->getTenantId();
        $rdv      = $this->rdvModel->getOneWithDetails($id);
        if (! $rdv || $rdv['tenant_id'] !== $tenantId) {
            return redirect()->to('/medecin/appointments')->with('error', 'RDV introuvable.');
        }
        return view('appointments/view', [
            'title'    => 'Rendez-vous',
            'rdv'      => $rdv,
            'base_url' => '/medecin',
        ]);
    }

    public function edit(int $id): string|RedirectResponse
    {
        $tenantId = $this->getTenantId();
        $rdv      = $this->rdvModel->getOneWithDetails($id);
        if (! $rdv || $rdv['tenant_id'] !== $tenantId) {
            return redirect()->to('/medecin/appointments')->with('error', 'RDV introuvable.');
        }
        return view('appointments/edit', [
            'title'    => 'Modifier le rendez-vous',
            'rdv'      => $rdv,
            'patients' => (new PatientModel())->getByTenant($tenantId),
            'medecins' => [$this->getMedecin()],
            'base_url' => '/medecin',
        ]);
    }

    public function update(int $id): RedirectResponse
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
        return redirect()->to('/medecin/appointments/view/' . $id)->with('success', 'Rendez-vous mis à jour.');
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

    public function delete(int $id): RedirectResponse
    {
        $rdv = $this->rdvModel->find($id);
        if (! $rdv || $rdv['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/medecin/appointments')->with('error', 'RDV introuvable.');
        }
        $this->rdvModel->update($id, ['statut' => 'annule']);
        return redirect()->to('/medecin/appointments')->with('success', 'Rendez-vous annulé.');
    }

    public function getJson()
    {
        $medecin = $this->getMedecin();
        $events  = $this->rdvModel->getForCalendar($this->getTenantId(), $medecin['id'] ?? null);
        return $this->response->setJSON($events);
    }
}
