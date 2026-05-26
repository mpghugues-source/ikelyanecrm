<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class InventoryItemModel extends Model
{
    protected $table         = 'erp_inventory_items';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','code','nom','description','categorie','unite',
        'quantite_stock','quantite_min','prix_achat','prix_vente',
        'supplier_id','emplacement','date_expiration','statut',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('erp_inventory_items.tenant_id', $tenantId);
    }

    public function getLowStock(int $tenantId): array
    {
        return $this->where('tenant_id', $tenantId)
            ->where('statut', 'active')
            ->where('quantite_stock <= quantite_min')
            ->findAll();
    }

    public function getWithSupplier(int $tenantId): array
    {
        return $this->select('erp_inventory_items.*, s.nom as supplier_nom')
            ->join('erp_suppliers s', 's.id = erp_inventory_items.supplier_id', 'left')
            ->where('erp_inventory_items.tenant_id', $tenantId)
            ->orderBy('erp_inventory_items.nom', 'ASC')
            ->findAll();
    }
}
