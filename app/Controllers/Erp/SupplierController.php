<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\SupplierModel;

class SupplierController extends BaseController
{
    private SupplierModel $model;

    public function __construct()
    {
        $this->model = new SupplierModel();
    }

    public function index(): string
    {
        $tid       = session()->get('tenant_id');
        $suppliers = $this->model->forTenant($tid)->orderBy('nom','ASC')->findAll();
        return view('erp/suppliers/index', ['suppliers' => $suppliers]);
    }

    public function create(): string
    {
        return view('erp/suppliers/form', ['supplier' => null]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'nom','email','telephone','adresse','ville','ice','nif',
            'conditions_paiement','contact_principal','statut','notes',
        ]);
        $data['tenant_id'] = $tid;
        $this->model->insert($data);
        return redirect()->to('/erp/suppliers')->with('success', lang('Erp.supplier_saved'));
    }

    public function edit(int $id): string
    {
        $tid      = session()->get('tenant_id');
        $supplier = $this->model->where('tenant_id', $tid)->find($id);
        if (!$supplier) return redirect()->to('/erp/suppliers');
        return view('erp/suppliers/form', ['supplier' => $supplier]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'nom','email','telephone','adresse','ville','ice','nif',
            'conditions_paiement','contact_principal','statut','notes',
        ]);
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/erp/suppliers')->with('success', lang('Erp.supplier_updated'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->delete($id);
        return redirect()->to('/erp/suppliers')->with('success', lang('Erp.supplier_deleted'));
    }
}
