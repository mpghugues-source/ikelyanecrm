<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table         = 'patients';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id','user_id','numero_dossier','nom','prenom',
        'date_naissance','sexe','telephone','email','adresse','ville',
        'groupe_sanguin','allergies','antecedents','traitement_en_cours',
        'contact_urgence_nom','contact_urgence_tel','assurance','numero_secu',
        'notes','actif',
    ];

    public function getByTenant(int $tenantId, string $search = ''): array
    {
        $builder = $this->where('tenant_id', $tenantId)->where('actif', 1);
        if ($search) {
            $builder->groupStart()
                    ->like('nom', $search)
                    ->orLike('prenom', $search)
                    ->orLike('numero_dossier', $search)
                    ->orLike('telephone', $search)
                    ->groupEnd();
        }
        return $builder->orderBy('nom', 'ASC')->findAll();
    }

    public function generateNumero(int $tenantId): string
    {
        $year  = date('Y');
        $count = $this->where('tenant_id', $tenantId)
                      ->like('numero_dossier', "PAT-{$year}-", 'after')
                      ->countAllResults();
        return sprintf('PAT-%s-%03d', $year, $count + 1);
    }

    public function getWithDetails(int $id): ?array
    {
        return $this->where('patients.id', $id)->first();
    }

    public function countByTenant(int $tenantId): int
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1)->countAllResults();
    }
}
