<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'erp_transactions';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','reference','date_transaction','description','type',
        'montant','account_id','statut','piece_jointe','notes','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('erp_transactions.tenant_id', $tenantId);
    }

    public function getWithAccount(int $tenantId): array
    {
        return $this->select('erp_transactions.*, a.nom as account_nom, a.code as account_code')
            ->join('erp_accounts a', 'a.id = erp_transactions.account_id', 'left')
            ->where('erp_transactions.tenant_id', $tenantId)
            ->orderBy('erp_transactions.date_transaction', 'DESC')
            ->findAll();
    }

    public function getMonthlySummary(int $tenantId, int $year): array
    {
        return $this->select("MONTH(date_transaction) as mois, type, SUM(montant) as total")
            ->where('tenant_id', $tenantId)
            ->where('statut', 'validated')
            ->where("YEAR(date_transaction)", $year)
            ->groupBy(['mois', 'type'])
            ->orderBy('mois', 'ASC')
            ->findAll();
    }

    public function generateReference(int $tenantId): string
    {
        $count = $this->where('tenant_id', $tenantId)->countAllResults() + 1;
        return 'TXN-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
