<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorScheduleModel extends Model
{
    protected $table         = 'doctor_schedules';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'medecin_id','tenant_id','date_debut','date_fin','type','motif',
    ];

    /** Vérifier si un médecin est disponible pour un créneau */
    public function isAvailable(int $medecinId, string $date, string $heure, int $duree = 30): bool
    {
        $debut = $date . ' ' . $heure . ':00';
        $fin   = date('Y-m-d H:i:s', strtotime($debut) + $duree * 60);

        // 1. Vérifier indisponibilités manuelles
        $blocked = $this->where('medecin_id', $medecinId)
                        ->where('date_debut <', $fin)
                        ->where('date_fin >', $debut)
                        ->first();

        if ($blocked) return false;

        // 2. Vérifier conflits avec RDV existants
        $db = \Config\Database::connect();
        $conflict = $db->query("
            SELECT id FROM rendez_vous
            WHERE medecin_id = ?
            AND date_rdv = ?
            AND statut NOT IN ('annule', 'absent')
            AND (
                (TIME(heure_rdv) < TIME(?) AND ADDTIME(heure_rdv, SEC_TO_TIME(duree * 60)) > TIME(?))
                OR TIME(heure_rdv) = TIME(?)
            )
        ", [$medecinId, $date, $fin, $debut, $heure])->getRow();

        return $conflict === null;
    }

    /** Obtenir les créneaux disponibles d'un médecin pour une date */
    public function getAvailableSlots(int $medecinId, string $date): array
    {
        $db     = \Config\Database::connect();
        $medecin = $db->table('medecins')->where('id', $medecinId)->get()->getRowArray();

        if (!$medecin) return [];

        $heureDebut = $medecin['heure_debut'] ?? '08:00:00';
        $heureFin   = $medecin['heure_fin']   ?? '17:00:00';
        $duree      = $medecin['duree_consultation'] ?? 30;

        // Vérifier si le médecin travaille ce jour
        $jourSemaine = (int)date('N', strtotime($date)); // 1=Lundi, 7=Dimanche
        $jours = explode(',', $medecin['jours_travail'] ?? '1,2,3,4,5');
        if (!in_array((string)$jourSemaine, $jours)) return [];

        // Générer tous les créneaux
        $slots  = [];
        $current = strtotime($date . ' ' . $heureDebut);
        $end     = strtotime($date . ' ' . $heureFin);

        while ($current + $duree * 60 <= $end) {
            $heure = date('H:i', $current);
            $slots[] = [
                'heure'       => $heure,
                'disponible'  => $this->isAvailable($medecinId, $date, $heure, $duree),
                'label'       => $heure,
            ];
            $current += $duree * 60;
        }

        return $slots;
    }

    /** Vérifier double-booking et retourner message d'erreur si conflit */
    public function checkConflict(int $medecinId, string $date, string $heure, int $duree = 30, ?int $excludeRdvId = null): ?string
    {
        $db    = \Config\Database::connect();
        $debut = $date . ' ' . $heure;
        $finTs = strtotime($debut) + $duree * 60;
        $fin   = date('H:i:s', $finTs);

        $sql = "
            SELECT r.id, CONCAT(p.nom, ' ', p.prenom) AS patient_nom,
                   r.heure_rdv, r.duree
            FROM rendez_vous r
            LEFT JOIN patients p ON r.patient_id = p.id
            WHERE r.medecin_id = ?
            AND r.date_rdv = ?
            AND r.statut NOT IN ('annule','absent')
            AND TIME(r.heure_rdv) < ?
            AND ADDTIME(r.heure_rdv, SEC_TO_TIME(r.duree * 60)) > ?
        ";
        $params = [$medecinId, $date, $fin, $heure.':00'];

        if ($excludeRdvId) {
            $sql .= " AND r.id != ?";
            $params[] = $excludeRdvId;
        }

        $conflict = $db->query($sql, $params)->getRowArray();

        if ($conflict) {
            return "Conflit de créneau : le médecin a déjà un RDV avec {$conflict['patient_nom']} à " .
                   substr($conflict['heure_rdv'], 0, 5) . ".";
        }

        return null;
    }
}
