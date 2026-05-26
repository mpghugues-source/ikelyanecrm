<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\InventoryItemModel;
use App\Models\Erp\InventoryMovementModel;
use App\Models\Erp\SupplierModel;

class InventoryController extends BaseController
{
    private InventoryItemModel     $model;
    private InventoryMovementModel $movementModel;

    public function __construct()
    {
        $this->model         = new InventoryItemModel();
        $this->movementModel = new InventoryMovementModel();
    }

    public function index(): string
    {
        $tid   = session()->get('tenant_id');
        $items = $this->model->getWithSupplier($tid);
        $low   = $this->model->getLowStock($tid);
        return view('erp/inventory/index', ['items' => $items, 'low_stock' => $low]);
    }

    public function create(): string
    {
        $tid       = session()->get('tenant_id');
        $suppliers = (new SupplierModel())->forTenant($tid)->where('statut','active')->findAll();
        return view('erp/inventory/form', ['item' => null, 'suppliers' => $suppliers]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'code','nom','description','categorie','unite','quantite_stock',
            'quantite_min','prix_achat','prix_vente','supplier_id','emplacement','date_expiration',
        ]);
        $data['tenant_id'] = $tid;
        $this->model->insert($data);
        return redirect()->to('/erp/inventory')->with('success', lang('Erp.item_saved'));
    }

    public function edit(int $id): string
    {
        $tid       = session()->get('tenant_id');
        $item      = $this->model->where('tenant_id', $tid)->find($id);
        if (!$item) return redirect()->to('/erp/inventory');
        $suppliers = (new SupplierModel())->forTenant($tid)->findAll();
        return view('erp/inventory/form', ['item' => $item, 'suppliers' => $suppliers]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'code','nom','description','categorie','unite','quantite_min',
            'prix_achat','prix_vente','supplier_id','emplacement','date_expiration','statut',
        ]);
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/erp/inventory')->with('success', lang('Erp.item_updated'));
    }

    public function movement(int $id): string
    {
        $tid  = session()->get('tenant_id');
        $item = $this->model->where('tenant_id', $tid)->find($id);
        if (!$item) return redirect()->to('/erp/inventory');
        return view('erp/inventory/movement', ['item' => $item]);
    }

    public function storeMovement(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost(['type','quantite','prix_unit','reference','motif','notes']);
        $data['tenant_id']  = $tid;
        $data['item_id']    = $id;
        $data['created_by'] = session()->get('user_id');
        $this->movementModel->recordMovement($data);
        return redirect()->to('/erp/inventory')->with('success', lang('Erp.movement_saved'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->update($id, ['statut' => 'inactive']);
        return redirect()->to('/erp/inventory')->with('success', lang('Erp.item_deleted'));
    }
}
