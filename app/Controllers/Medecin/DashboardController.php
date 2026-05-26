<?php

namespace App\Controllers\Medecin;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $tenantId    = $this->getTenantId();
        $userId      = $this->getUserId();
        $doctorModel = new DoctorModel();
        $medecin     = $doctorModel->findByUserId($userId);
        $medecinId   = $medecin['id'] ?? 0;
        $rdvModel    = new AppointmentModel();
        $db          = \Config\Database::connect();

        // RDV du jour avec détails patients
        $rdvsToday = $rdvModel->getWithDetails($tenantId, [
            'date'       => date('Y-m-d'),
            'medecin_id' => $medecinId,
        ]);

        // Prochain RDV à venir aujourd'hui
        $now = date('H:i:s');
        $prochainRdv = null;
        foreach ($rdvsToday as $r) {
            if ($r['heure_rdv'] >= $now && in_array($r['statut'], ['planifie','confirme'])) {
                $prochainRdv = $r;
                break;
            }
        }

        // Stats mensuelles
        $statsMonth = $db->query("
            SELECT
                COUNT(*) AS total,
                COUNT(CASE WHEN statut = 'termine' THEN 1 END) AS termines,
                COUNT(CASE WHEN statut = 'annule' THEN 1 END) AS annules,
                COUNT(CASE WHEN statut = 'absent' THEN 1 END) AS absents
            FROM rendez_vous
            WHERE medecin_id = ? AND tenant_id = ?
              AND MONTH(date_rdv) = MONTH(NOW()) AND YEAR(date_rdv) = YEAR(NOW())
        ", [$medecinId, $tenantId])->getRowArray();

        // RDV par jour cette semaine (pour mini-chart)
        $weekStats = $db->query("
            SELECT DAYOFWEEK(date_rdv) AS jour, COUNT(*) AS nb
            FROM rendez_vous
            WHERE medecin_id = ? AND tenant_id = ?
              AND date_rdv BETWEEN DATE_SUB(NOW(), INTERVAL WEEKDAY(NOW()) DAY)
                               AND DATE_ADD(DATE_SUB(NOW(), INTERVAL WEEKDAY(NOW()) DAY), INTERVAL 6 DAY)
            GROUP BY jour ORDER BY jour
        ", [$medecinId, $tenantId])->getResultArray();

        $weekData = array_fill(0, 7, 0);
        foreach ($weekStats as $w) {
            $idx = ((int)$w['jour'] - 2 + 7) % 7;
            $weekData[$idx] = (int)$w['nb'];
        }

        // Mes patients récents
        $recentPatients = $db->query("
            SELECT DISTINCT p.id, p.nom, p.prenom, p.date_naissance,
                   MAX(r.date_rdv) AS derniere_visite, p.telephone
            FROM rendez_vous r
            JOIN patients p ON p.id = r.patient_id
            WHERE r.medecin_id = ? AND r.tenant_id = ?
            GROUP BY p.id ORDER BY derniere_visite DESC LIMIT 5
        ", [$medecinId, $tenantId])->getResultArray();

        // Revenus du mois
        $revenuMois = $db->query("
            SELECT COALESCE(SUM(montant_paye),0) AS total
            FROM factures
            WHERE medecin_id = ? AND tenant_id = ?
              AND MONTH(date_facture) = MONTH(NOW())
              AND YEAR(date_facture) = YEAR(NOW())
              AND statut != 'annule'
        ", [$medecinId, $tenantId])->getRowArray()['total'] ?? 0;

        return view('dashboard/medecin', [
            'title'          => 'Mon tableau de bord',
            'medecin'        => $medecin,
            'rdvsToday'      => $rdvsToday,
            'prochainRdv'    => $prochainRdv,
            'statsMonth'     => $statsMonth,
            'weekData'       => $weekData,
            'recentPatients' => $recentPatients,
            'revenuMois'     => $revenuMois,
            'rdv_today'      => count($rdvsToday),
            'total_patients' => (new PatientModel())->countByTenant($tenantId),
        ]);
    }
}
