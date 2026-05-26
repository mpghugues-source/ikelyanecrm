<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PlanLimitService;
use App\Models\DoctorModel;
use App\Models\UserModel;
use App\Models\SpecialtyModel;

class DoctorController extends BaseController
{
    private DoctorModel   $doctorModel;
    private SpecialtyModel $specialtyModel;

    public function __construct()
    {
        $this->doctorModel   = new DoctorModel();
        $this->specialtyModel = new SpecialtyModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $usage    = (new PlanLimitService())->getDoctorUsage($tenantId);

        return view('doctors/index', [
            'title'   => 'Médecins',
            'doctors' => $this->doctorModel->getWithUser($tenantId),
            'usage'   => $usage,
        ]);
    }

    public function create(): string
    {
        $tenantId = $this->getTenantId();
        $limit    = new PlanLimitService();

        if ($error = $limit->checkCanAddDoctor($tenantId)) {
            return redirect()->to('/admin/doctors')->with('error', $error);
        }

        return view('doctors/create', [
            'title'      => 'Nouveau médecin',
            'specialites'=> $this->specialtyModel->getAll(),
            'usage'      => $limit->getDoctorUsage($tenantId),
        ]);
    }

    public function store()
    {
        $rules = [
            'nom'    => 'required',
            'prenom' => 'required',
            'email'  => 'required|valid_email',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tenantId = $this->getTenantId();

        // Vérification limite plan (double-check serveur)
        if ($error = (new PlanLimitService())->checkCanAddDoctor($tenantId)) {
            return redirect()->to('/admin/doctors')->with('error', $error);
        }
        $post     = $this->request->getPost();

        $userModel = new UserModel();
        $userId = $userModel->insert([
            'tenant_id'    => $tenantId,
            'nom'          => $post['nom'],
            'prenom'       => $post['prenom'],
            'email'        => $post['email'],
            'mot_de_passe' => password_hash($post['password'] ?? 'Medecin@2024', PASSWORD_DEFAULT),
            'role'         => 'medecin',
            'telephone'    => $post['telephone'] ?? null,
            'actif'        => 1,
        ]);

        $this->doctorModel->insert([
            'user_id'       => $userId,
            'tenant_id'     => $tenantId,
            'specialite_id' => $post['specialite_id'] ?? null,
            'numero_ordre'  => $post['numero_ordre'] ?? null,
            'tarif_consultation' => $post['tarif_consultation'] ?? 0,
            'duree_consultation' => $post['duree_consultation'] ?? 30,
            'heure_debut'   => $post['heure_debut'] ?? '08:00:00',
            'heure_fin'     => $post['heure_fin'] ?? '17:00:00',
            'biographie'    => $post['biographie'] ?? null,
            'actif'         => 1,
        ]);

        return redirect()->to('/admin/doctors')->with('success', 'Médecin créé avec succès.');
    }

    public function view(int $id): string
    {
        $doctor = $this->doctorModel->getOneWithUser($id);
        if (! $doctor) {
            return redirect()->to('/admin/doctors')->with('error', 'Médecin introuvable.');
        }
        return view('doctors/view', ['title' => 'Dr. ' . $doctor['user_prenom'] . ' ' . $doctor['user_nom'], 'doctor' => $doctor]);
    }

    public function edit(int $id): string
    {
        $doctor = $this->doctorModel->getOneWithUser($id);
        if (! $doctor) {
            return redirect()->to('/admin/doctors')->with('error', 'Médecin introuvable.');
        }
        return view('doctors/edit', [
            'title'      => 'Modifier médecin',
            'doctor'     => $doctor,
            'specialites'=> $this->specialtyModel->getAll(),
        ]);
    }

    public function update(int $id)
    {
        $post     = $this->request->getPost();
        $doctor   = $this->doctorModel->find($id);

        if ($doctor) {
            $this->doctorModel->update($id, [
                'specialite_id'      => $post['specialite_id'] ?? null,
                'numero_ordre'       => $post['numero_ordre'] ?? null,
                'tarif_consultation' => $post['tarif_consultation'] ?? 0,
                'duree_consultation' => $post['duree_consultation'] ?? 30,
                'heure_debut'        => $post['heure_debut'] ?? '08:00:00',
                'heure_fin'          => $post['heure_fin'] ?? '17:00:00',
                'biographie'         => $post['biographie'] ?? null,
            ]);

            $userModel = new UserModel();
            $userModel->update($doctor['user_id'], [
                'nom'       => $post['nom'],
                'prenom'    => $post['prenom'],
                'telephone' => $post['telephone'] ?? null,
            ]);
        }
        return redirect()->to('/admin/doctors')->with('success', 'Médecin mis à jour.');
    }

    public function delete(int $id)
    {
        $this->doctorModel->update($id, ['actif' => 0]);
        return redirect()->to('/admin/doctors')->with('success', 'Médecin désactivé.');
    }

    public function bySpecialty(int $specialiteId)
    {
        $doctors = $this->doctorModel->getBySpecialty($specialiteId, $this->getTenantId());
        return $this->response->setJSON($doctors);
    }
}
