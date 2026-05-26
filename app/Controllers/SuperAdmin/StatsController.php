<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;

class StatsController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        // Croissance mensuelle des tenants (6 derniers mois)
        $growth = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as mois,
                   COUNT(*) as nb
            FROM tenants
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mois ORDER BY mois ASC
        ")->getResultArray();

        // Top tenants par nombre de RDV
        $topTenants = $db->query("
            SELECT t.nom, t.ville, t.abonnement, t.couleur,
                COUNT(r.id) as nb_rdv,
                COUNT(DISTINCT r.patient_id) as nb_patients
            FROM tenants t
            LEFT JOIN rendez_vous r ON r.tenant_id = t.id
            GROUP BY t.id
            ORDER BY nb_rdv DESC
            LIMIT 5
        ")->getResultArray();

        // RDV par mois (6 derniers mois) — toute la plateforme
        $rdvMensuel = $db->query("
            SELECT DATE_FORMAT(date_rdv, '%Y-%m') as mois,
                   COUNT(*) as nb,
                   SUM(CASE WHEN statut='termine' THEN 1 ELSE 0 END) as termines
            FROM rendez_vous
            WHERE date_rdv >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mois ORDER BY mois ASC
        ")->getResultArray();

        // Stats globales
        $global = $db->query("
            SELECT
                (SELECT COUNT(*) FROM tenants WHERE actif=1) as tenants_actifs,
                (SELECT COUNT(*) FROM users WHERE role != 'super_admin') as total_users,
                (SELECT COUNT(*) FROM patients) as total_patients,
                (SELECT COUNT(*) FROM rendez_vous) as total_rdv,
                (SELECT COUNT(*) FROM rendez_vous WHERE statut='termine') as rdv_termines,
                (SELECT COUNT(*) FROM medecins WHERE actif=1) as total_medecins,
                (SELECT COUNT(*) FROM ordonnances) as total_ordonnances,
                (SELECT COALESCE(SUM(total),0) FROM factures WHERE statut='paye') as revenus_plateforme
        ")->getRowArray();

        return view('superadmin/stats/index', [
            'title'      => 'Statistiques — Super Admin',
            'global'     => $global,
            'growth'     => $growth,
            'topTenants' => $topTenants,
            'rdvMensuel' => $rdvMensuel,
        ]);
    }
}
