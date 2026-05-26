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

        // Top tenants par nombre de leads
        $topTenants = $db->query("
            SELECT t.nom, t.ville, t.plan, t.couleur,
                COUNT(DISTINCT l.id) as nb_leads,
                COUNT(DISTINCT c.id) as nb_contacts
            FROM tenants t
            LEFT JOIN crm_leads l ON l.tenant_id = t.id
            LEFT JOIN crm_contacts c ON c.tenant_id = t.id
            GROUP BY t.id
            ORDER BY nb_leads DESC
            LIMIT 5
        ")->getResultArray();

        // Leads par mois (6 derniers mois) — toute la plateforme
        $leadsMensuel = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as mois,
                   COUNT(*) as nb,
                   SUM(CASE WHEN statut='won' THEN 1 ELSE 0 END) as gagnes
            FROM crm_leads
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY mois ORDER BY mois ASC
        ")->getResultArray();

        // Stats globales
        $global = $db->query("
            SELECT
                (SELECT COUNT(*) FROM tenants WHERE actif=1) as tenants_actifs,
                (SELECT COUNT(*) FROM users WHERE role != 'super_admin') as total_users,
                (SELECT COUNT(*) FROM crm_contacts) as total_contacts,
                (SELECT COUNT(*) FROM crm_leads) as total_leads,
                (SELECT COUNT(*) FROM crm_leads WHERE statut='won') as leads_gagnes,
                (SELECT COUNT(*) FROM crm_campaigns) as total_campagnes,
                (SELECT COALESCE(SUM(valeur_estimee),0) FROM crm_leads WHERE statut='won') as valeur_gagnee
        ")->getRowArray();

        return view('superadmin/stats/index', [
            'title'        => 'Statistiques — Super Admin',
            'global'       => $global,
            'growth'       => $growth,
            'topTenants'   => $topTenants,
            'leadsMensuel' => $leadsMensuel,
        ]);
    }
}
