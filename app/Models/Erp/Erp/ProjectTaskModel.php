<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectTaskModel extends Model
{
    protected $table         = 'erp_project_tasks';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'project_id','titre','description','statut','priorite',
        'assigned_to','date_echeance','heures_estimees','heures_reelles',
    ];

    public function getWithAssignee(int $projectId): array
    {
        return $this->select('erp_project_tasks.*, CONCAT(u.prenom," ",u.nom) as assigned_name')
            ->join('users u', 'u.id = erp_project_tasks.assigned_to', 'left')
            ->where('project_id', $projectId)
            ->orderBy('erp_project_tasks.priorite', 'DESC')
            ->findAll();
    }

    public function updateProjectProgress(int $projectId): void
    {
        $total = $this->where('project_id', $projectId)->countAllResults(false);
        if ($total === 0) return;
        $done = $this->where('project_id', $projectId)->where('statut', 'done')->countAllResults(false);
        $pct = (int)round(($done / $total) * 100);
        \Config\Database::connect()->table('erp_projects')
            ->where('id', $projectId)->update(['avancement' => $pct]);
    }
}
