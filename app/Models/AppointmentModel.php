<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table         = 'rendez_vous';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id','patient_id','medecin_id','date_rdv','heure_rdv',
        'duree','motif','statut','type_rdv','type_consultation','notes','rappel_envoye',
    ];

    public function getWithDetails(int $tenantId, array $filters = []): array
    {
        $builder = $this->select('rendez_vous.*,
                p.nom as patient_nom, p.prenom as patient_prenom,
                p.telephone as patient_tel,
                u.nom as medecin_nom, u.prenom as medecin_prenom,
                sp.nom as specialite_nom')
            ->join('patients p', 'p.id = rendez_vous.patient_id')
            ->join('medecins m', 'm.id = rendez_vous.medecin_id')
            ->join('users u', 'u.id = m.user_id')
            ->join('specialites sp', 'sp.id = m.specialite_id', 'left')
            ->where('rendez_vous.tenant_id', $tenantId);

        if (! empty($filters['date'])) {
            $builder->where('rendez_vous.date_rdv', $filters['date']);
        }
        if (! empty($filters['patient_id'])) {
            $builder->where('rendez_vous.patient_id', $filters['patient_id']);
        }
        if (! empty($filters['medecin_id'])) {
            $builder->where('rendez_vous.medecin_id', $filters['medecin_id']);
        }
        if (! empty($filters['statut'])) {
            $builder->where('rendez_vous.statut', $filters['statut']);
        }

        return $builder->orderBy('rendez_vous.date_rdv', 'DESC')
                       ->orderBy('rendez_vous.heure_rdv', 'ASC')
                       ->findAll();
    }

    public function getOneWithDetails(int $id): ?array
    {
        return $this->select('rendez_vous.*,
                p.nom as patient_nom, p.prenom as patient_prenom,
                p.telephone as patient_tel,
                u.nom as medecin_nom, u.prenom as medecin_prenom,
                sp.nom as specialite_nom')
            ->join('patients p', 'p.id = rendez_vous.patient_id')
            ->join('medecins m', 'm.id = rendez_vous.medecin_id')
            ->join('users u', 'u.id = m.user_id')
            ->join('specialites sp', 'sp.id = m.specialite_id', 'left')
            ->where('rendez_vous.id', $id)
            ->first();
    }

    public function getForCalendar(int $tenantId, ?int $medecinId = null): array
    {
        $builder = $this->select('rendez_vous.*, p.nom as patient_nom, p.prenom as patient_prenom, u.nom as medecin_nom, u.prenom as medecin_prenom')
            ->join('patients p', 'p.id = rendez_vous.patient_id')
            ->join('medecins m', 'm.id = rendez_vous.medecin_id')
            ->join('users u', 'u.id = m.user_id')
            ->where('rendez_vous.tenant_id', $tenantId)
            ->whereNotIn('rendez_vous.statut', ['annule']);

        if ($medecinId) {
            $builder->where('rendez_vous.medecin_id', $medecinId);
        }

        $rdvs = $builder->findAll();
        $events = [];
        foreach ($rdvs as $rdv) {
            $color = match ($rdv['statut']) {
                'planifie'  => '#0d6efd',
                'confirme'  => '#198754',
                'en_cours'  => '#fd7e14',
                'termine'   => '#6c757d',
                'absent'    => '#dc3545',
                default     => '#0d6efd',
            };
            $events[] = [
                'id'    => $rdv['id'],
                'title' => $rdv['patient_prenom'] . ' ' . $rdv['patient_nom'] . ' — Dr. ' . $rdv['medecin_prenom'] . ' ' . $rdv['medecin_nom'],
                'start' => $rdv['date_rdv'] . 'T' . $rdv['heure_rdv'],
                'end'   => $rdv['date_rdv'] . 'T' . date('H:i:s', strtotime($rdv['heure_rdv']) + ($rdv['duree'] * 60)),
                'color' => $color,
                'extendedProps' => [
                    'statut' => $rdv['statut'],
                    'motif'  => $rdv['motif'],
                ],
            ];
        }
        return $events;
    }

    public function getTodayCount(int $tenantId, ?int $medecinId = null): int
    {
        $builder = $this->where('tenant_id', $tenantId)
                        ->where('date_rdv', date('Y-m-d'))
                        ->whereNotIn('statut', ['annule']);
        if ($medecinId) {
            $builder->where('medecin_id', $medecinId);
        }
        return $builder->countAllResults();
    }

    public function getThisMonthCount(int $tenantId, ?int $medecinId = null): int
    {
        $builder = $this->where('tenant_id', $tenantId)
                        ->where('MONTH(date_rdv)', date('n'))
                        ->where('YEAR(date_rdv)', date('Y'));
        if ($medecinId) {
            $builder->where('medecin_id', $medecinId);
        }
        return $builder->countAllResults();
    }
}
