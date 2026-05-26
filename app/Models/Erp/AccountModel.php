<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table         = 'erp_accounts';
    protected $primaryKey    = 'id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'tenant_id','code','nom','type','solde','description','parent_id','actif',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('tenant_id', $tenantId);
    }

    public function getBalanceByType(int $tenantId): array
    {
        $rows = $this->select('type, SUM(solde) as total')
            ->where('tenant_id', $tenantId)
            ->where('actif', 1)
            ->groupBy('type')
            ->findAll();
        $result = [];
        foreach ($rows as $r) {
            $result[$r['type']] = $r['total'];
        }
        return $result;
    }
}
