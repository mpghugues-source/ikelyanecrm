<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table         = 'medecins';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id','tenant_id','specialite_id','numero_ordre','biographie',
        'tarif_consultation','duree_consultation','jours_travail',
        'heure_debut','heure_fin','actif',
    ];

    public function getWithUser(int $tenantId): array
    {
        return $this->select('medecins.*, users.nom as user_nom, users.prenom as user_prenom, users.email as user_email, users.telephone as user_telephone, users.avatar as user_avatar, specialites.nom as specialite_nom')
                    ->join('users', 'users.id = medecins.user_id')
                    ->join('specialites', 'specialites.id = medecins.specialite_id', 'left')
                    ->where('medecins.tenant_id', $tenantId)
                    ->where('medecins.actif', 1)
                    ->orderBy('users.nom', 'ASC')
                    ->findAll();
    }

    public function getOneWithUser(int $id): ?array
    {
        return $this->select('medecins.*, users.nom as user_nom, users.prenom as user_prenom, users.email as user_email, users.telephone as user_telephone, users.avatar as user_avatar, specialites.nom as specialite_nom')
                    ->join('users', 'users.id = medecins.user_id')
                    ->join('specialites', 'specialites.id = medecins.specialite_id', 'left')
                    ->where('medecins.id', $id)
                    ->first();
    }

    public function getBySpecialty(int $specialiteId, int $tenantId): array
    {
        return $this->select('medecins.*, users.nom as user_nom, users.prenom as user_prenom')
                    ->join('users', 'users.id = medecins.user_id')
                    ->where('medecins.specialite_id', $specialiteId)
                    ->where('medecins.tenant_id', $tenantId)
                    ->where('medecins.actif', 1)
                    ->findAll();
    }

    public function findByUserId(int $userId): ?array
    {
        return $this->select('medecins.*, users.nom as user_nom, users.prenom as user_prenom, users.email as user_email, specialites.nom as specialite_nom')
                    ->join('users', 'users.id = medecins.user_id', 'left')
                    ->join('specialites', 'specialites.id = medecins.specialite_id', 'left')
                    ->where('medecins.user_id', $userId)
                    ->first();
    }

    public function countByTenant(int $tenantId): int
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1)->countAllResults();
    }
}
