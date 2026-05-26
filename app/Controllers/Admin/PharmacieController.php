<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PharmacieModel;

class PharmacieController extends BaseController
{
    private PharmacieModel $model;

    public function __construct()
    {
        $this->model = new PharmacieModel();
    }

    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $search   = $this->request->getGet('search');
        $q = $this->model->where('tenant_id', $tenantId)->where('actif', 1);
        if ($search) {
            $q->groupStart()
              ->like('nom', $search)
              ->orLike('categorie', $search)
              ->orLike('fournisseur', $search)
              ->groupEnd();
        }
        $medicaments = $q->orderBy('nom')->findAll();
        $stockBas    = $this->model->where('tenant_id', $tenantId)->where('actif', 1)
                           ->where('stock_actuel <=', 'stock_minimum', false)->countAllResults();
        return view('pharmacie/index', [
            'title'       => 'Pharmacie — Stock médicaments',
            'medicaments' => $medicaments,
            'stockBas'    => $stockBas,
            'search'      => $search,
        ]);
    }

    public function create(): string
    {
        return view('pharmacie/create', ['title' => 'Ajouter un médicament']);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        $rules = [
            'nom'          => 'required|min_length[2]|max_length[200]',
            'stock_actuel' => 'required|integer|greater_than_equal_to[0]',
            'stock_minimum'=> 'required|integer|greater_than_equal_to[0]',
            'prix_unitaire'=> 'required|decimal',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->insert([
            'tenant_id'       => $tenantId,
            'nom'             => $this->request->getPost('nom'),
            'categorie'       => $this->request->getPost('categorie'),
            'forme'           => $this->request->getPost('forme'),
            'dosage'          => $this->request->getPost('dosage'),
            'stock_actuel'    => (int)$this->request->getPost('stock_actuel'),
            'stock_minimum'   => (int)$this->request->getPost('stock_minimum'),
            'prix_unitaire'   => (float)$this->request->getPost('prix_unitaire'),
            'fournisseur'     => $this->request->getPost('fournisseur'),
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
            'notes'           => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/pharmacie')->with('success', 'Médicament ajouté avec succès.');
    }

    public function view(int $id): string
    {
        $tenantId   = $this->getTenantId();
        $medicament = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$medicament) return redirect()->to('/admin/pharmacie')->with('error', 'Introuvable.');
        return view('pharmacie/view', ['title' => 'Fiche médicament : ' . $medicament['nom'], 'medicament' => $medicament]);
    }

    public function edit(int $id): string
    {
        $tenantId   = $this->getTenantId();
        $medicament = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$medicament) return redirect()->to('/admin/pharmacie')->with('error', 'Introuvable.');
        return view('pharmacie/edit', ['title' => 'Modifier le médicament', 'medicament' => $medicament]);
    }

    public function update(int $id)
    {
        $tenantId   = $this->getTenantId();
        $medicament = $this->model->where('tenant_id', $tenantId)->find($id);
        if (!$medicament) return redirect()->to('/admin/pharmacie')->with('error', 'Introuvable.');
        $rules = [
            'nom'          => 'required|min_length[2]|max_length[200]',
            'stock_actuel' => 'required|integer|greater_than_equal_to[0]',
            'stock_minimum'=> 'required|integer|greater_than_equal_to[0]',
            'prix_unitaire'=> 'required|decimal',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->model->update($id, [
            'nom'             => $this->request->getPost('nom'),
            'categorie'       => $this->request->getPost('categorie'),
            'forme'           => $this->request->getPost('forme'),
            'dosage'          => $this->request->getPost('dosage'),
            'stock_actuel'    => (int)$this->request->getPost('stock_actuel'),
            'stock_minimum'   => (int)$this->request->getPost('stock_minimum'),
            'prix_unitaire'   => (float)$this->request->getPost('prix_unitaire'),
            'fournisseur'     => $this->request->getPost('fournisseur'),
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
            'notes'           => $this->request->getPost('notes'),
        ]);
        return redirect()->to('/admin/pharmacie')->with('success', 'Médicament mis à jour.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->getTenantId();
        $this->model->where('tenant_id', $tenantId)->update($id, ['actif' => 0]);
        return redirect()->to('/admin/pharmacie')->with('success', 'Médicament supprimé.');
    }
}
