<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AccueilModel;

class AccueilController extends BaseController
{
    private AccueilModel $model;

    public function __construct()
    {
        $this->model = new AccueilModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $statut   = $this->request->getGet('statut');
        $db       = \Config\Database::connect();
        $builder  = $db->table('accueil a')
            ->select('a.*, CONCAT(u.prenom," ",u.nom) AS patient_nom')
            ->join('patients p', 'p.id = a.patient_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('a.tenant_id', $tenantId)->where('a.actif', 1);
        if ($statut) $builder->where('a.statut', $statut);
        $operations = $builder->orderBy('a.date_heure', 'DESC')->get()->getResultArray();
        return view('accueil/index', [
            'title'      => 'Bureau d\'accueil',
            'operations' => $operations,
            'statut'     => $statut,
        ]);
    }

    public function create(): string
    {
        $tenantId = $this->getTenantId();
        $db       = \Config\Database::connect();
        $patients = $db->table('patients p')->select('p.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=p.user_id')->where('p.tenant_id', $tenantId)->where('p.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        return view('accueil/create', ['title' => 'Nouvelle opération', 'patients' => $patients]);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        if (!$this->validate(['type_operation' => 'required', 'date_heure' => 'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert([
            'tenant_id'      => $tenantId,
            'patient_id'     => $this->request->getPost('patient_id') ?: null,
            'nom_visiteur'   => $this->request->getPost('nom_visiteur'),
            'type_operation' => $this->request->getPost('type_operation'),
            'date_heure'     => $this->request->getPost('date_heure'),
            'motif'          => $this->request->getPost('motif'),
            'chambre'        => $this->request->getPost('chambre'),
            'statut'         => 'en_attente',
            'notes'          => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/accueil')->with('success', 'Opération enregistrée.');
    }

    public function show(int $id): string
    {
        $tenantId  = $this->getTenantId();
        $db        = \Config\Database::connect();
        $operation = $db->table('accueil a')
            ->select('a.*, CONCAT(u.prenom," ",u.nom) AS patient_nom, p.date_naissance, p.groupe_sanguin, p.telephone AS patient_tel')
            ->join('patients p', 'p.id = a.patient_id', 'left')
            ->join('users u',    'u.id = p.user_id',    'left')
            ->where('a.tenant_id', $tenantId)
            ->where('a.id', $id)
            ->get()->getRowArray();
        if (!$operation) {
            return redirect()->to('/admin/accueil')->with('error', 'Introuvable.');
        }
        return view('accueil/view', ['title' => 'Détail opération', 'operation' => $operation]);
    }

    public function edit(int $id): string
    {
        $tenantId  = $this->getTenantId();
        $operation = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$operation) return redirect()->to('/admin/accueil')->with('error', 'Introuvable.');
        $db       = \Config\Database::connect();
        $patients = $db->table('patients p')->select('p.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=p.user_id')->where('p.tenant_id', $tenantId)->where('p.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        return view('accueil/edit', ['title' => 'Modifier l\'opération', 'operation' => $operation, 'patients' => $patients]);
    }

    public function update(int $id)
    {
        $tenantId  = $this->getTenantId();
        $operation = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$operation) return redirect()->to('/admin/accueil')->with('error', 'Introuvable.');
        $this->model->update($id, [
            'patient_id'     => $this->request->getPost('patient_id') ?: null,
            'nom_visiteur'   => $this->request->getPost('nom_visiteur'),
            'type_operation' => $this->request->getPost('type_operation'),
            'date_heure'     => $this->request->getPost('date_heure'),
            'motif'          => $this->request->getPost('motif'),
            'chambre'        => $this->request->getPost('chambre'),
            'statut'         => $this->request->getPost('statut'),
            'notes'          => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/accueil')->with('success', 'Opération mise à jour.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->getTenantId();
        $this->model->where('tenant_id', $tenantId)->update($id, ['actif' => 0]);
        return redirect()->to('/admin/accueil')->with('success', 'Opération supprimée.');
    }
}
