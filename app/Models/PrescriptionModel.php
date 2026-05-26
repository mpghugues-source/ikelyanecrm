<?php

namespace App\Models;

use CodeIgniter\Model;

class PrescriptionModel extends Model
{
    protected $table         = 'ordonnances';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id','patient_id','medecin_id','rdv_id',
        'numero','date_ordonnance','validite_jours','instructions','statut',
    ];

    public function getWithDetails(int $tenantId, ?int $medecinId = null): array
    {
        $builder = $this->select('ordonnances.*, CONCAT(p.nom, " ", p.prenom) as patient_nom, CONCAT(u.nom, " ", u.prenom) as medecin_nom')
            ->join('patients p', 'p.id = ordonnances.patient_id', 'left')
            ->join('medecins m', 'm.id = ordonnances.medecin_id', 'left')
            ->join('users u', 'u.id = m.user_id', 'left')
            ->where('ordonnances.tenant_id', $tenantId);

        if ($medecinId) {
            $builder->where('ordonnances.medecin_id', $medecinId);
        }

        return $builder->orderBy('ordonnances.date_ordonnance', 'DESC')->findAll();
    }

    public function getOneWithDetails(int $id): ?array
    {
        return $this->select('ordonnances.*, CONCAT(p.nom, " ", p.prenom) as patient_nom, p.date_naissance as patient_ddn, p.adresse as patient_adresse, CONCAT(u.nom, " ", u.prenom) as medecin_nom, sp.nom as specialite, m.numero_ordre')
            ->join('patients p', 'p.id = ordonnances.patient_id', 'left')
            ->join('medecins m', 'm.id = ordonnances.medecin_id', 'left')
            ->join('users u', 'u.id = m.user_id', 'left')
            ->join('specialites sp', 'sp.id = m.specialite_id', 'left')
            ->where('ordonnances.id', $id)
            ->first();
    }

    public function getByPatient(int $patientId): array
    {
        return $this->select('ordonnances.*, CONCAT(u.nom, " ", u.prenom) as medecin_nom')
            ->join('medecins m', 'm.id = ordonnances.medecin_id', 'left')
            ->join('users u', 'u.id = m.user_id', 'left')
            ->where('ordonnances.patient_id', $patientId)
            ->orderBy('ordonnances.date_ordonnance', 'DESC')
            ->findAll();
    }

    public function generateNumero(int $tenantId): string
    {
        $year  = date('Y');
        $count = $this->where('tenant_id', $tenantId)
                      ->like('numero', "ORD-{$year}-", 'after')
                      ->countAllResults();
        return sprintf('ORD-%s-%04d', $year, $count + 1);
    }
}
