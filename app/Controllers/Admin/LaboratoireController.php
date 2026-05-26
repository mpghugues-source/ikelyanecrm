<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaboratoireModel;
use App\Models\PatientModel;
use App\Models\MedecinModel;

class LaboratoireController extends BaseController
{
    private LaboratoireModel $model;

    public function __construct()
    {
        $this->model = new LaboratoireModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $statut   = $this->request->getGet('statut');
        $db       = \Config\Database::connect();
        $builder  = $db->table('laboratoire l')
            ->select('l.*, CONCAT(u.prenom," ",u.nom) AS patient_nom')
            ->join('patients p', 'p.id = l.patient_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('l.tenant_id', $tenantId)->where('l.actif', 1);
        if ($statut) $builder->where('l.statut', $statut);
        $analyses = $builder->orderBy('l.created_at', 'DESC')->get()->getResultArray();
        return view('laboratoire/index', [
            'title'    => 'Laboratoire — Analyses',
            'analyses' => $analyses,
            'statut'   => $statut,
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
        return view('laboratoire/create', ['title' => 'Nouvelle analyse', 'patients' => $patients, 'medecins' => $medecins]);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        if (!$this->validate(['type_analyse' => 'required', 'date_demande' => 'required|valid_date'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert([
            'tenant_id'    => $tenantId,
            'patient_id'   => $this->request->getPost('patient_id') ?: null,
            'medecin_id'   => $this->request->getPost('medecin_id') ?: null,
            'numero'       => 'LAB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)),
            'type_analyse' => $this->request->getPost('type_analyse'),
            'date_demande' => $this->request->getPost('date_demande'),
            'statut'       => 'en_attente',
            'urgence'      => (int)$this->request->getPost('urgence'),
            'notes'        => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/laboratoire')->with('success', 'Analyse créée.');
    }

    public function view(int $id): string
    {
        $tenantId = $this->getTenantId();
        $analyse  = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$analyse) return redirect()->to('/admin/laboratoire')->with('error', 'Introuvable.');
        $db = \Config\Database::connect();
        $patientNom = null;
        $medecinNom = null;
        if ($analyse['patient_id']) {
            $row = $db->query("SELECT CONCAT(u.prenom,' ',u.nom) AS nom FROM patients p JOIN users u ON u.id=p.user_id WHERE p.id=?", [$analyse['patient_id']])->getRowArray();
            $patientNom = $row['nom'] ?? null;
        }
        if ($analyse['medecin_id']) {
            $row = $db->query("SELECT CONCAT('Dr. ',u.prenom,' ',u.nom) AS nom FROM medecins m JOIN users u ON u.id=m.user_id WHERE m.id=?", [$analyse['medecin_id']])->getRowArray();
            $medecinNom = $row['nom'] ?? null;
        }
        return view('laboratoire/view', [
            'title'      => 'Analyse : ' . $analyse['numero'],
            'analyse'    => $analyse,
            'patientNom' => $patientNom,
            'medecinNom' => $medecinNom,
        ]);
    }

    public function edit(int $id): string
    {
        $tenantId = $this->getTenantId();
        $analyse  = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$analyse) return redirect()->to('/admin/laboratoire')->with('error', 'Introuvable.');
        $db       = \Config\Database::connect();
        $patients = $db->table('patients p')->select('p.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=p.user_id')->where('p.tenant_id', $tenantId)->where('p.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        $medecins = $db->table('medecins m')->select('m.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=m.user_id')->where('m.tenant_id', $tenantId)->where('m.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        return view('laboratoire/edit', ['title' => 'Modifier l\'analyse', 'analyse' => $analyse, 'patients' => $patients, 'medecins' => $medecins]);
    }

    public function update(int $id)
    {
        $tenantId = $this->getTenantId();
        $analyse  = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$analyse) return redirect()->to('/admin/laboratoire')->with('error', 'Introuvable.');
        $this->model->update($id, [
            'patient_id'    => $this->request->getPost('patient_id') ?: null,
            'medecin_id'    => $this->request->getPost('medecin_id') ?: null,
            'type_analyse'  => $this->request->getPost('type_analyse'),
            'date_demande'  => $this->request->getPost('date_demande'),
            'date_resultat' => $this->request->getPost('date_resultat') ?: null,
            'statut'        => $this->request->getPost('statut'),
            'urgence'       => (int)$this->request->getPost('urgence'),
            'notes'         => $this->request->getPost('notes'),
            'resultat'      => $this->request->getPost('resultat'),
        ]);
        return redirect()->to('/admin/laboratoire')->with('success', 'Analyse mise à jour.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->getTenantId();
        $this->model->where('tenant_id', $tenantId)->update($id, ['actif' => 0]);
        return redirect()->to('/admin/laboratoire')->with('success', 'Analyse supprimée.');
    }
}
