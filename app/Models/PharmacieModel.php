<?php
namespace App\Models;
use CodeIgniter\Model;

class PharmacieModel extends Model
{
    protected $table         = 'pharmacie';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['tenant_id','nom','categorie','forme','dosage','stock_actuel','stock_minimum','prix_unitaire','fournisseur','date_expiration','notes','actif'];

    public function forTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1);
    }

    public function stockBas(int $tenantId): array
    {
        return $this->where('tenant_id', $tenantId)
                    ->where('actif', 1)
                    ->where('stock_actuel <=', 'stock_minimum', false)
                    ->findAll();
    }
}
