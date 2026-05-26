<?php

namespace App\Models\Crm;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table         = 'crm_contacts';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','type','prenom','nom','email','telephone',
        'entreprise','poste','adresse','ville','pays','source',
        'statut','notes','avatar','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('tenant_id', $tenantId);
    }

    public function getWithStats(int $tenantId): array
    {
        return $this->select('crm_contacts.*, COUNT(DISTINCT crm_leads.id) as nb_leads, COUNT(DISTINCT crm_activities.id) as nb_activities, COUNT(DISTINCT crm_cases.id) as nb_cases')
            ->join('crm_leads', 'crm_leads.contact_id = crm_contacts.id AND crm_leads.statut NOT IN ("won","lost")', 'left')
            ->join('crm_activities', 'crm_activities.contact_id = crm_contacts.id', 'left')
            ->join('crm_cases', 'crm_cases.contact_id = crm_contacts.id AND crm_cases.statut NOT IN ("resolved","closed")', 'left')
            ->where('crm_contacts.tenant_id', $tenantId)
            ->groupBy('crm_contacts.id')
            ->orderBy('crm_contacts.nom', 'ASC')
            ->findAll();
    }
}
