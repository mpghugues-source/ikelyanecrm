<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicalRecordModel extends Model
{
    protected $table         = 'dossiers_medicaux';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id','patient_id','medecin_id','rdv_id',
        'date_consultation','motif','symptomes','examen_clinique',
        'diagnostic','traitement','observations',
        'tension_arterielle','poids','taille','temperature','pouls',
    ];

    public function getByPatient(int $patientId): array
    {
        return $this->select('dossiers_medicaux.*, CONCAT(u.prenom, " ", u.nom) as medecin_nom')
                    ->join('medecins m', 'm.id = dossiers_medicaux.medecin_id', 'left')
                    ->join('users u', 'u.id = m.user_id', 'left')
                    ->where('dossiers_medicaux.patient_id', $patientId)
                    ->orderBy('dossiers_medicaux.date_consultation', 'DESC')
                    ->findAll();
    }
}
