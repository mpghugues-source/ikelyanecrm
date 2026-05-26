<?php

namespace App\Models\Crm;

use CodeIgniter\Model;

class CaseModel extends Model
{
    protected $table         = 'crm_cases';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','numero','sujet','description','type','priorite',
        'statut','contact_id','assigned_to','date_resolution','resolution','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('crm_cases.tenant_id', $tenantId);
    }

    public function getWithContact(int $tenantId): array
    {
        return $this->select('crm_cases.*, CONCAT(IFNULL(c.prenom,"")," ",c.nom) as contact_name, c.email as contact_email, CONCAT(u.prenom," ",u.nom) as assigned_name')
            ->join('crm_contacts c', 'c.id = crm_cases.contact_id', 'left')
            ->join('users u', 'u.id = crm_cases.assigned_to', 'left')
            ->where('crm_cases.tenant_id', $tenantId)
            ->orderBy('crm_cases.created_at', 'DESC')
            ->findAll();
    }

    public function generateNumber(int $tenantId): string
    {
        $count = $this->where('tenant_id', $tenantId)->countAllResults() + 1;
        return 'CASE-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
