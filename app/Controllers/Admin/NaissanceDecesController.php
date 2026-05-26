<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NaissanceDecesModel;

class NaissanceDecesController extends BaseController
{
    private NaissanceDecesModel $model;

    public function __construct()
    {
        $this->model = new NaissanceDecesModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $type     = $this->request->getGet('type');
        $q = $this->model->where('tenant_id', $tenantId)->where('actif', 1);
        if ($type) $q->where('type_evenement', $type);
        $evenements = $q->orderBy('date_evenement', 'DESC')->findAll();
        $naissances = $this->model->where('tenant_id', $tenantId)->where('actif', 1)->where('type_evenement', 'naissance')->countAllResults();
        $deces      = $this->model->where('tenant_id', $tenantId)->where('actif', 1)->where('type_evenement', 'deces')->countAllResults();
        return view('naissance_deces/index', [
            'title'      => 'Naissances & Décès',
            'evenements' => $evenements,
            'naissances' => $naissances,
            'deces'      => $deces,
            'type'       => $type,
        ]);
    }

    public function create(): string
    {
        $tenantId = $this->getTenantId();
        $db       = \Config\Database::connect();
        $medecins = $db->table('medecins m')->select('m.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=m.user_id')->where('m.tenant_id', $tenantId)->where('m.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        return view('naissance_deces/create', ['title' => 'Nouvel événement', 'medecins' => $medecins]);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        if (!$this->validate(['nom' => 'required', 'date_evenement' => 'required|valid_date', 'type_evenement' => 'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert([
            'tenant_id'         => $tenantId,
            'type_evenement'    => $this->request->getPost('type_evenement'),
            'nom'               => $this->request->getPost('nom'),
            'prenom'            => $this->request->getPost('prenom'),
            'date_evenement'    => $this->request->getPost('date_evenement'),
            'heure_evenement'   => $this->request->getPost('heure_evenement') ?: null,
            'lieu'              => $this->request->getPost('lieu'),
            'medecin_id'        => $this->request->getPost('medecin_id') ?: null,
            'numero_certificat' => $this->request->getPost('numero_certificat'),
            'notes'             => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/naissance-deces')->with('success', 'Événement enregistré.');
    }

    public function view(int $id): string
    {
        $tenantId  = $this->getTenantId();
        $evenement = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$evenement) return redirect()->to('/admin/naissance-deces')->with('error', 'Introuvable.');
        $medecinNom = null;
        if ($evenement['medecin_id']) {
            $db  = \Config\Database::connect();
            $row = $db->query("SELECT CONCAT('Dr. ',u.prenom,' ',u.nom) AS nom FROM medecins m JOIN users u ON u.id=m.user_id WHERE m.id=?", [$evenement['medecin_id']])->getRowArray();
            $medecinNom = $row['nom'] ?? null;
        }
        return view('naissance_deces/view', [
            'title'      => 'Événement : ' . ($evenement['type_evenement'] === 'naissance' ? 'Naissance' : 'Décès') . ' de ' . $evenement['nom'],
            'evenement'  => $evenement,
            'medecinNom' => $medecinNom,
        ]);
    }

    public function edit(int $id): string
    {
        $tenantId   = $this->getTenantId();
        $evenement  = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$evenement) return redirect()->to('/admin/naissance-deces')->with('error', 'Introuvable.');
        $db       = \Config\Database::connect();
        $medecins = $db->table('medecins m')->select('m.id, CONCAT(u.prenom," ",u.nom) AS nom')
            ->join('users u','u.id=m.user_id')->where('m.tenant_id', $tenantId)->where('m.actif',1)
            ->orderBy('u.nom')->get()->getResultArray();
        return view('naissance_deces/edit', ['title' => 'Modifier l\'événement', 'evenement' => $evenement, 'medecins' => $medecins]);
    }

    public function update(int $id)
    {
        $tenantId  = $this->getTenantId();
        $evenement = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$evenement) return redirect()->to('/admin/naissance-deces')->with('error', 'Introuvable.');
        $this->model->update($id, [
            'type_evenement'    => $this->request->getPost('type_evenement'),
            'nom'               => $this->request->getPost('nom'),
            'prenom'            => $this->request->getPost('prenom'),
            'date_evenement'    => $this->request->getPost('date_evenement'),
            'heure_evenement'   => $this->request->getPost('heure_evenement') ?: null,
            'lieu'              => $this->request->getPost('lieu'),
            'medecin_id'        => $this->request->getPost('medecin_id') ?: null,
            'numero_certificat' => $this->request->getPost('numero_certificat'),
            'notes'             => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/naissance-deces')->with('success', 'Événement mis à jour.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->getTenantId();
        $this->model->where('tenant_id', $tenantId)->update($id, ['actif' => 0]);
        return redirect()->to('/admin/naissance-deces')->with('success', 'Événement supprimé.');
    }
}
