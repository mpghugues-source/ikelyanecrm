<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RadiologieModel;

class RadiologieController extends BaseController
{
    private RadiologieModel $model;

    public function __construct()
    {
        $this->model = new RadiologieModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $statut   = $this->request->getGet('statut');
        $db       = \Config\Database::connect();
        $builder  = $db->table('radiologie r')
            ->select('r.*, CONCAT(u.prenom," ",u.nom) AS patient_nom')
            ->join('patients p', 'p.id = r.patient_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('r.tenant_id', $tenantId)->where('r.actif', 1);
        if ($statut) $builder->where('r.statut', $statut);
        $examens = $builder->orderBy('r.created_at', 'DESC')->get()->getResultArray();
        return view('radiologie/index', [
            'title'   => 'Radiologie — Examens',
            'examens' => $examens,
            'statut'  => $statut,
        ]);
    }

    public function create(): string
    {
        $tenantId = $this->getTenantId();
        $db       = \Config\Database::connect();
        $patients = $db->table('patients p')->select('p.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=p.user_id')->where('p.tenant_id', $tenantId)->where('p.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        $medecins = $db->table('medecins m')->select('m.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=m.user_id')->where('m.tenant_id', $tenantId)->where('m.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        return view('radiologie/create', ['title' => 'Nouvel examen', 'patients' => $patients, 'medecins' => $medecins]);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        if (!$this->validate(['type_examen' => 'required', 'date_demande' => 'required|valid_date'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert([
            'tenant_id'   => $tenantId,
            'patient_id'  => $this->request->getPost('patient_id') ?: null,
            'medecin_id'  => $this->request->getPost('medecin_id') ?: null,
            'numero'      => 'RAD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
            'type_examen' => $this->request->getPost('type_examen'),
            'region'      => $this->request->getPost('region'),
            'date_demande'=> $this->request->getPost('date_demande'),
            'statut'      => 'en_attente',
            'urgence'     => (int)$this->request->getPost('urgence'),
            'description' => $this->request->getPost('description'),
        ]);
        return redirect()->to('/admin/radiologie')->with('success', 'Examen créé.');
    }

    public function view(int $id): string
    {
        $tenantId = $this->getTenantId();
        $examen   = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$examen) return redirect()->to('/admin/radiologie')->with('error', 'Introuvable.');
        $db = \Config\Database::connect();
        $patientNom = null;
        $medecinNom = null;
        if ($examen['patient_id']) {
            $row = $db->query("SELECT CONCAT(u.prenom,' ',u.nom) AS nom FROM patients p JOIN users u ON u.id=p.user_id WHERE p.id=?", [$examen['patient_id']])->getRowArray();
            $patientNom = $row['nom'] ?? null;
        }
        if ($examen['medecin_id']) {
            $row = $db->query("SELECT CONCAT('Dr. ',u.prenom,' ',u.nom) AS nom FROM medecins m JOIN users u ON u.id=m.user_id WHERE m.id=?", [$examen['medecin_id']])->getRowArray();
            $medecinNom = $row['nom'] ?? null;
        }
        return view('radiologie/view', [
            'title'      => 'Examen : ' . $examen['numero'],
            'examen'     => $examen,
            'patientNom' => $patientNom,
            'medecinNom' => $medecinNom,
        ]);
    }

    public function edit(int $id): string
    {
        $tenantId = $this->getTenantId();
        $examen   = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$examen) return redirect()->to('/admin/radiologie')->with('error', 'Introuvable.');
        $db       = \Config\Database::connect();
        $patients = $db->table('patients p')->select('p.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=p.user_id')->where('p.tenant_id', $tenantId)->where('p.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        $medecins = $db->table('medecins m')->select('m.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=m.user_id')->where('m.tenant_id', $tenantId)->where('m.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        return view('radiologie/edit', ['title' => 'Modifier l\'examen', 'examen' => $examen, 'patients' => $patients, 'medecins' => $medecins]);
    }

    public function update(int $id)
    {
        $tenantId = $this->getTenantId();
        $examen   = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$examen) return redirect()->to('/admin/radiologie')->with('error', 'Introuvable.');
        $this->model->update($id, [
            'patient_id'  => $this->request->getPost('patient_id') ?: null,
            'medecin_id'  => $this->request->getPost('medecin_id') ?: null,
            'type_examen' => $this->request->getPost('type_examen'),
            'region'      => $this->request->getPost('region'),
            'date_demande'=> $this->request->getPost('date_demande'),
            'date_examen' => $this->request->getPost('date_examen') ?: null,
            'statut'      => $this->request->getPost('statut'),
            'urgence'     => (int)$this->request->getPost('urgence'),
            'description' => $this->request->getPost('description'),
            'compte_rendu'=> $this->request->getPost('compte_rendu'),
        ]);
        return redirect()->to('/admin/radiologie')->with('success', 'Examen mis à jour.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->getTenantId();
        $this->model->where('tenant_id', $tenantId)->update($id, ['actif' => 0]);
        return redirect()->to('/admin/radiologie')->with('success', 'Examen supprimé.');
    }
}
