<?php

namespace App\Models\Crm;

use CodeIgniter\Model;

class LeadModel extends Model
{
    protected $table         = 'crm_leads';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','contact_id','titre','valeur_estimee','devise',
        'source','statut','probabilite','date_cloture_prevue',
        'date_cloture_reelle','raison_perte','notes','assigned_to','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('crm_leads.tenant_id', $tenantId);
    }

    public function getPipelineData(int $tenantId): array
    {
        $stages = ['new','qualified','proposition','negotiation','won','lost'];
        $result = [];
        foreach ($stages as $stage) {
            $data = $this->selectSum('valeur_estimee', 'total_value')
                ->selectCount('id', 'count')
                ->where('tenant_id', $tenantId)
                ->where('statut', $stage)
                ->first();
            $result[$stage] = $data;
        }
        return $result;
    }

    public function getWithContact(int $tenantId): array
    {
        return $this->select('crm_leads.*, CONCAT(IFNULL(c.prenom,"")," ",c.nom) as contact_name, c.email as contact_email, c.telephone as contact_phone, CONCAT(u.prenom," ",u.nom) as assigned_name')
            ->join('crm_contacts c', 'c.id = crm_leads.contact_id', 'left')
            ->join('users u', 'u.id = crm_leads.assigned_to', 'left')
            ->where('crm_leads.tenant_id', $tenantId)
            ->orderBy('crm_leads.created_at', 'DESC')
            ->findAll();
    }
}
