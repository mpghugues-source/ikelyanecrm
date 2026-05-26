<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table         = 'erp_suppliers';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','nom','email','telephone','adresse','ville',
        'ice','nif','conditions_paiement','contact_principal','statut','notes',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('tenant_id', $tenantId);
    }
}
