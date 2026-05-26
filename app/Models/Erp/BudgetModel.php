<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $table         = 'erp_budgets';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','nom','exercice','periode','mois','account_id',
        'montant_prevu','montant_realise','notes','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('erp_budgets.tenant_id', $tenantId);
    }

    public function getWithAccount(int $tenantId): array
    {
        return $this->select('erp_budgets.*, a.nom as account_nom, a.code as account_code, a.type as account_type')
            ->join('erp_accounts a', 'a.id = erp_budgets.account_id', 'left')
            ->where('erp_budgets.tenant_id', $tenantId)
            ->orderBy('erp_budgets.exercice', 'DESC')
            ->findAll();
    }
}
