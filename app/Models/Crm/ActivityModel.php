<?php

namespace App\Models\Crm;

use CodeIgniter\Model;

class ActivityModel extends Model
{
    protected $table         = 'crm_activities';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','type','sujet','description','date_debut','date_fin',
        'duree_min','statut','priorite','contact_id','lead_id','assigned_to','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('crm_activities.tenant_id', $tenantId);
    }

    public function getWithRelations(int $tenantId): array
    {
        return $this->select('crm_activities.*, CONCAT(IFNULL(c.prenom,"")," ",c.nom) as contact_name, l.titre as lead_titre, CONCAT(u.prenom," ",u.nom) as assigned_name')
            ->join('crm_contacts c', 'c.id = crm_activities.contact_id', 'left')
            ->join('crm_leads l', 'l.id = crm_activities.lead_id', 'left')
            ->join('users u', 'u.id = crm_activities.assigned_to', 'left')
            ->where('crm_activities.tenant_id', $tenantId)
            ->orderBy('crm_activities.date_debut', 'DESC')
            ->findAll();
    }

    public function getUpcoming(int $tenantId, int $days = 7): array
    {
        return $this->where('tenant_id', $tenantId)
            ->where('statut', 'planned')
            ->where('date_debut >=', date('Y-m-d H:i:s'))
            ->where('date_debut <=', date('Y-m-d H:i:s', strtotime("+{$days} days")))
            ->orderBy('date_debut', 'ASC')
            ->findAll();
    }
}
