<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\PurchaseOrderModel;
use App\Models\Erp\SupplierModel;
use App\Models\Erp\InventoryItemModel;

class PurchaseOrderController extends BaseController
{
    private PurchaseOrderModel $model;

    public function __construct()
    {
        $this->model = new PurchaseOrderModel();
    }

    public function index(): string
    {
        $tid    = session()->get('tenant_id');
        $orders = $this->model->getWithSupplier($tid);
        return view('erp/purchase_orders/index', ['orders' => $orders]);
    }

    public function create(): string
    {
        $tid       = session()->get('tenant_id');
        $suppliers = (new SupplierModel())->forTenant($tid)->where('statut','active')->findAll();
        $items     = (new InventoryItemModel())->forTenant($tid)->where('statut','active')->findAll();
        return view('erp/purchase_orders/form', ['order' => null, 'suppliers' => $suppliers, 'items' => $items]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid   = session()->get('tenant_id');
        $db    = \Config\Database::connect();

        $data = $this->request->getPost([
            'supplier_id','date_commande','date_livraison_prevue',
            'conditions_paiement','tva','notes',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $data['statut']     = 'draft';
        $data['numero']     = $this->model->generateNumber($tid);

        $descriptions = $this->request->getPost('description');
        $quantities   = $this->request->getPost('quantite');
        $units        = $this->request->getPost('unite');
        $prices       = $this->request->getPost('prix_unitaire');
        $item_ids     = $this->request->getPost('item_id');
        $tva          = (float)($data['tva'] ?? 0);

        $montant_ht = 0;
        $lines = [];
        if (is_array($descriptions)) {
            foreach ($descriptions as $i => $desc) {
                if (empty($desc)) continue;
                $qty     = (float)($quantities[$i] ?? 0);
                $price   = (float)($prices[$i] ?? 0);
                $total   = $qty * $price;
                $montant_ht += $total;
                $lines[] = [
                    'description'   => $desc,
                    'quantite'      => $qty,
                    'unite'         => $units[$i] ?? 'pièce',
                    'prix_unitaire' => $price,
                    'tva'           => $tva,
                    'montant_total' => $total,
                    'item_id'       => $item_ids[$i] ?: null,
                    'quantite_recue'=> 0,
                ];
            }
        }

        $data['montant_ht']  = $montant_ht;
        $data['montant_ttc'] = $montant_ht * (1 + $tva / 100);
        $poId = $this->model->insert($data, true);

        foreach ($lines as $line) {
            $line['po_id'] = $poId;
            $db->table('erp_purchase_order_items')->insert($line);
        }

        return redirect()->to('/erp/purchase-orders')->with('success', lang('Erp.po_saved'));
    }

    public function view(int $id): string
    {
        $tid   = session()->get('tenant_id');
        $order = $this->model->where('tenant_id', $tid)->find($id);
        if (!$order) return redirect()->to('/erp/purchase-orders');
        $items = \Config\Database::connect()->table('erp_purchase_order_items')->where('po_id', $id)->get()->getResultArray();
        $supplier = (new SupplierModel())->find($order['supplier_id']);
        return view('erp/purchase_orders/view', [
            'order'    => $order,
            'items'    => $items,
            'supplier' => $supplier,
        ]);
    }

    public function updateStatus(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid    = session()->get('tenant_id');
        $statut = $this->request->getPost('statut');
        $this->model->where('tenant_id', $tid)->update($id, ['statut' => $statut]);
        if ($statut === 'received') {
            $this->model->where('tenant_id', $tid)->update($id, ['date_livraison_reelle' => date('Y-m-d')]);
        }
        return redirect()->back()->with('success', lang('Erp.po_updated'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->update($id, ['statut' => 'cancelled']);
        return redirect()->to('/erp/purchase-orders')->with('success', lang('Erp.po_cancelled'));
    }
}
