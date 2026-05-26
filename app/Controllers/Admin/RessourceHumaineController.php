<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RessourceHumaineModel;

class RessourceHumaineController extends BaseController
{
    private RessourceHumaineModel $model;

    public function __construct()
    {
        $this->model = new RessourceHumaineModel();
    }

    public function index(): string
    {
        $tenantId  = $this->getTenantId();
        $search    = $this->request->getGet('search');
        $q = $this->model->where('tenant_id', $tenantId)->where('actif', 1);
        if ($search) {
            $q->groupStart()->like('nom', $search)->orLike('prenom', $search)->orLike('poste', $search)->groupEnd();
        }
        $employes = $q->orderBy('nom')->findAll();
        return view('rh/index', ['title' => 'Ressources Humaines', 'employes' => $employes, 'search' => $search]);
    }

    public function create(): string
    {
        return view('rh/create', ['title' => 'Ajouter un employé']);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        if (!$this->validate(['nom' => 'required', 'prenom' => 'required', 'poste' => 'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert([
            'tenant_id'     => $tenantId,
            'nom'           => $this->request->getPost('nom'),
            'prenom'        => $this->request->getPost('prenom'),
            'poste'         => $this->request->getPost('poste'),
            'departement'   => $this->request->getPost('departement'),
            'date_embauche' => $this->request->getPost('date_embauche') ?: null,
            'salaire'       => $this->request->getPost('salaire') ?: null,
            'telephone'     => $this->request->getPost('telephone'),
            'email'         => $this->request->getPost('email'),
            'contrat'       => $this->request->getPost('contrat') ?: 'CDI',
            'notes'         => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/rh')->with('success', 'Employé ajouté.');
    }

    public function view(int $id): string
    {
        $tenantId = $this->getTenantId();
        $employe  = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$employe) return redirect()->to('/admin/rh')->with('error', 'Introuvable.');
        return view('rh/view', ['title' => 'Fiche employé : ' . $employe['prenom'] . ' ' . $employe['nom'], 'employe' => $employe]);
    }

    public function edit(int $id): string
    {
        $tenantId = $this->getTenantId();
        $employe  = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$employe) return redirect()->to('/admin/rh')->with('error', 'Introuvable.');
        return view('rh/edit', ['title' => 'Modifier l\'employé', 'employe' => $employe]);
    }

    public function update(int $id)
    {
        $tenantId = $this->getTenantId();
        $employe  = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$employe) return redirect()->to('/admin/rh')->with('error', 'Introuvable.');
        $this->model->update($id, [
            'nom'           => $this->request->getPost('nom'),
            'prenom'        => $this->request->getPost('prenom'),
            'poste'         => $this->request->getPost('poste'),
            'departement'   => $this->request->getPost('departement'),
            'date_embauche' => $this->request->getPost('date_embauche') ?: null,
            'salaire'       => $this->request->getPost('salaire') ?: null,
            'telephone'     => $this->request->getPost('telephone'),
            'email'         => $this->request->getPost('email'),
            'contrat'       => $this->request->getPost('contrat'),
            'notes'         => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/rh')->with('success', 'Employé mis à jour.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->getTenantId();
        $this->model->where('tenant_id', $tenantId)->update($id, ['actif' => 0]);
        return redirect()->to('/admin/rh')->with('success', 'Employé supprimé.');
    }
}
