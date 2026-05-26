<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ReportController extends BaseController
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        $tenantId  = $this->getTenantId();
        $year      = (int)($this->request->getGet('year') ?? date('Y'));
        $dateFrom  = $this->request->getGet('date_from') ?? '';
        $dateTo    = $this->request->getGet('date_to')   ?? '';

        $useCustomDates = ($dateFrom !== '' || $dateTo !== '');
        $sqlDateFrom = $useCustomDates && $dateFrom ? $dateFrom : "{$year}-01-01";
        $sqlDateTo   = $useCustomDates && $dateTo   ? $dateTo   : "{$year}-12-31";

        // Leads par mois (12 mois de l'année)
        $leadsRows = $this->db->query("
            SELECT MONTH(created_at) AS mois, YEAR(created_at) AS annee,
                   COUNT(*) AS total_leads,
                   SUM(CASE WHEN statut='won' THEN 1 ELSE 0 END) AS leads_gagnes
            FROM crm_leads
            WHERE tenant_id = ? AND YEAR(created_at) = ?
            GROUP BY annee, mois ORDER BY mois
        ", [$tenantId, $year])->getResultArray();

        $leadsByMonth = array_fill(1, 12, ['total_leads' => 0, 'leads_gagnes' => 0]);
        foreach ($leadsRows as $r) {
            $leadsByMonth[(int)$r['mois']] = $r;
        }

        // Leads par statut (période filtrée)
        $leadsStatuts = $this->db->query("
            SELECT statut, COUNT(*) AS nb
            FROM crm_leads
            WHERE tenant_id = ? AND DATE(created_at) BETWEEN ? AND ?
            GROUP BY statut
        ", [$tenantId, $sqlDateFrom, $sqlDateTo])->getResultArray();

        // Valeur pipeline par statut
        $pipelineValeur = $this->db->query("
            SELECT statut,
                   COUNT(*) AS nb,
                   COALESCE(SUM(valeur_estimee), 0) AS valeur
            FROM crm_leads
            WHERE tenant_id = ? AND DATE(created_at) BETWEEN ? AND ?
            GROUP BY statut ORDER BY valeur DESC
        ", [$tenantId, $sqlDateFrom, $sqlDateTo])->getResultArray();

        // Performance commerciaux
        $commerciauxPerf = $this->db->query("
            SELECT CONCAT(u.prenom, ' ', u.nom) AS nom,
                   COUNT(l.id) AS nb_leads,
                   SUM(CASE WHEN l.statut='won' THEN 1 ELSE 0 END) AS nb_gagnes,
                   COALESCE(SUM(CASE WHEN l.statut='won' THEN l.valeur_estimee ELSE 0 END), 0) AS valeur_gagnee
            FROM users u
            LEFT JOIN crm_leads l ON l.assigned_to = u.id AND DATE(l.created_at) BETWEEN ? AND ?
            WHERE u.tenant_id = ? AND u.actif = 1 AND u.role IN ('admin','manager','commercial','support')
            GROUP BY u.id, u.prenom, u.nom
            ORDER BY valeur_gagnee DESC
        ", [$sqlDateFrom, $sqlDateTo, $tenantId])->getResultArray();

        // Nouveaux contacts par mois
        $newContacts = $this->db->query("
            SELECT MONTH(created_at) AS mois, COUNT(*) AS nb
            FROM crm_contacts
            WHERE tenant_id = ? AND YEAR(created_at) = ?
            GROUP BY mois ORDER BY mois
        ", [$tenantId, $year])->getResultArray();

        $contactsByMonth = array_fill(1, 12, 0);
        foreach ($newContacts as $p) {
            $contactsByMonth[(int)$p['mois']] = (int)$p['nb'];
        }

        // Totaux période filtrée
        $totals = $this->db->query("
            SELECT
                COUNT(*) AS nb_leads,
                SUM(CASE WHEN statut='won' THEN 1 ELSE 0 END) AS nb_gagnes,
                COALESCE(SUM(valeur_estimee), 0) AS valeur_totale,
                COALESCE(SUM(CASE WHEN statut='won' THEN valeur_estimee ELSE 0 END), 0) AS valeur_gagnee
            FROM crm_leads
            WHERE tenant_id = ? AND DATE(created_at) BETWEEN ? AND ?
        ", [$tenantId, $sqlDateFrom, $sqlDateTo])->getRowArray();

        $totalLeads    = (int)($totals['nb_leads'] ?? 0);
        $totalGagnes   = (int)($totals['nb_gagnes'] ?? 0);
        $totalContacts = $this->db->table('crm_contacts')->where('tenant_id', $tenantId)->countAll();

        $tauxConversion = $totalLeads > 0 ? round($totalGagnes / $totalLeads * 100) : 0;

        // Pertes (lost)
        $totalPerdus = 0;
        foreach ($leadsStatuts as $s) {
            if ($s['statut'] === 'lost') $totalPerdus = (int)$s['nb'];
        }
        $tauxPerte = $totalLeads > 0 ? round($totalPerdus / $totalLeads * 100) : 0;

        return view('reports/index', [
            'title'            => lang('Admin.reports_title'),
            'year'             => $year,
            'date_from'        => $dateFrom,
            'date_to'          => $dateTo,
            'useCustomDates'   => $useCustomDates,
            'sqlDateFrom'      => $sqlDateFrom,
            'sqlDateTo'        => $sqlDateTo,
            'leadsByMonth'     => $leadsByMonth,
            'leadsStatuts'     => $leadsStatuts,
            'pipelineValeur'   => $pipelineValeur,
            'commerciauxPerf'  => $commerciauxPerf,
            'contactsByMonth'  => $contactsByMonth,
            'tauxConversion'   => $tauxConversion,
            'tauxPerte'        => $tauxPerte,
            'totalLeads'       => $totalLeads,
            'totalGagnes'      => $totalGagnes,
            'totalContacts'    => $totalContacts,
            'totals'           => $totals,
        ]);
    }
}
