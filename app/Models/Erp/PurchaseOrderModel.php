<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table         = 'erp_purchase_orders';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','numero','supplier_id','date_commande','date_livraison_prevue',
        'date_livraison_reelle','statut','montant_ht','tva','montant_ttc',
        'devise','conditions_paiement','notes','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('erp_purchase_orders.tenant_id', $tenantId);
    }

    public function getWithSupplier(int $tenantId): array
    {
        return $this->select('erp_purchase_orders.*, s.nom as supplier_nom')
            ->join('erp_suppliers s', 's.id = erp_purchase_orders.supplier_id', 'left')
            ->where('erp_purchase_orders.tenant_id', $tenantId)
            ->orderBy('erp_purchase_orders.date_commande', 'DESC')
            ->findAll();
    }

    public function generateNumber(int $tenantId): string
    {
        $count = $this->where('tenant_id', $tenantId)->countAllResults() + 1;
        return 'PO-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
