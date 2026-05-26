<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table         = 'erp_projects';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'tenant_id','nom','description','statut','priorite','date_debut',
        'date_fin','budget','cout_reel','avancement','chef_projet','created_by',
    ];

    public function forTenant(int $tenantId): static
    {
        return $this->where('erp_projects.tenant_id', $tenantId);
    }

    public function getWithChef(int $tenantId): array
    {
        return $this->select('erp_projects.*, CONCAT(u.prenom," ",u.nom) as chef_nom')
            ->join('users u', 'u.id = erp_projects.chef_projet', 'left')
            ->where('erp_projects.tenant_id', $tenantId)
            ->orderBy('erp_projects.created_at', 'DESC')
            ->findAll();
    }
}
