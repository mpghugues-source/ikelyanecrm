<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table         = 'services';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = ['tenant_id','nom','code','prix','description','actif'];

    public function getByTenant(int $tenantId): array
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('nom', 'ASC')->findAll();
    }
}
