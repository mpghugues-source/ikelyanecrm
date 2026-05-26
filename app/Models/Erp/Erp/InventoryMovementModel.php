<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class InventoryMovementModel extends Model
{
    protected $table         = 'erp_inventory_movements';
    protected $primaryKey    = 'id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'tenant_id','item_id','type','quantite','prix_unit',
        'reference','motif','notes','created_by','created_at',
    ];

    public function recordMovement(array $data): int|string
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $id = $this->insert($data, true);

        // Update stock quantity
        $db = \Config\Database::connect();
        $qty = $data['quantite'];
        if (in_array($data['type'], ['out'])) {
            $qty = -$qty;
        }
        if ($data['type'] === 'adjustment') {
            $db->table('erp_inventory_items')->where('id', $data['item_id'])
                ->set('quantite_stock', $data['quantite'])->update();
        } else {
            $db->table('erp_inventory_items')->where('id', $data['item_id'])
                ->set('quantite_stock', "quantite_stock + ({$qty})", false)->update();
        }
        return $id;
    }
}
