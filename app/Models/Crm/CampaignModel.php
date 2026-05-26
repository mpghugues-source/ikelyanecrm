<?php

namespace App\Models\Crm;

use CodeIgniter\Model;

class CampaignModel extends Model
{
    protected $table         = 'crm_campaigns';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','nom','description','type','statut','budget',
        'cout_reel','nb_cibles','nb_reponses','date_debut','date_fin','objectif','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('tenant_id', $tenantId);
    }

    public function getStats(int $tenantId): array
    {
        return [
            'total'     => $this->where('tenant_id', $tenantId)->countAllResults(false),
            'active'    => $this->where('tenant_id', $tenantId)->where('statut', 'active')->countAllResults(false),
            'completed' => $this->where('tenant_id', $tenantId)->where('statut', 'completed')->countAllResults(false),
            'budget'    => $this->selectSum('budget')->where('tenant_id', $tenantId)->first()['budget'] ?? 0,
        ];
    }
}
