<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TenantModel;

class ReportController extends BaseController
{
    private int $tenantId;
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    private function tid(): int
    {
        return session()->get('tenant_id');
    }

    public function index(): string
    {
        $tenantId  = $this->tid();
        $year      = (int)($this->request->getGet('year')      ?? date('Y'));
        $dateFrom  = $this->request->getGet('date_from')       ?? '';
        $dateTo    = $this->request->getGet('date_to')         ?? '';
        $medecinId = (int)($this->request->getGet('medecin_id') ?? 0);
        $filterType = $this->request->getGet('type')           ?? '';

        // ── Résolution des bornes de dates ───────────────────────────
        // Si filtres de date fournis, on les utilise ; sinon on s'appuie sur l'année
        $useCustomDates = ($dateFrom !== '' || $dateTo !== '');
        $sqlDateFrom = $useCustomDates && $dateFrom ? $dateFrom : "{$year}-01-01";
        $sqlDateTo   = $useCustomDates && $dateTo   ? $dateTo   : "{$year}-12-31";

        // ── Liste des médecins (pour le sélecteur de filtre) ─────────
        $medecinsList = $this->db->query("
            SELECT m.id, CONCAT(u.prenom,' ',u.nom) AS nom
            FROM medecins m JOIN users u ON u.id = m.user_id
            WHERE m.tenant_id = ? AND m.actif = 1 ORDER BY u.nom
        ", [$tenantId])->getResultArray();

        // ── Filtre médecin SQL ────────────────────────────────────────
        $medecinSqlF = $medecinId ? " AND f.medecin_id = {$medecinId}" : '';
        $medecinSqlR = $medecinId ? " AND r.medecin_id = {$medecinId}" : '';

        // ── Revenus mensuels (12 mois de l'année sélectionnée) ───────
        $revenueRows = $this->db->query("
            SELECT MONTH(date_facture) AS mois, YEAR(date_facture) AS annee,
                   SUM(total) AS total_facture,
                   SUM(montant_paye) AS total_paye
            FROM factures f
            WHERE f.tenant_id = ? AND YEAR(date_facture) = ? AND statut != 'annule'
            {$medecinSqlF}
            GROUP BY annee, mois ORDER BY mois
        ", [$tenantId, $year])->getResultArray();

        $revenueByMonth = array_fill(1, 12, ['total_facture' => 0, 'total_paye' => 0]);
        foreach ($revenueRows as $r) {
            $revenueByMonth[(int)$r['mois']] = $r;
        }

        // ── RDV par statut (avec filtres de dates) ───────────────────
        $rdvStatuts = $this->db->query("
            SELECT r.statut, COUNT(*) AS nb
            FROM rendez_vous r
            WHERE r.tenant_id = ? AND r.date_rdv BETWEEN ? AND ?
            {$medecinSqlR}
            GROUP BY r.statut
        ", [$tenantId, $sqlDateFrom, $sqlDateTo])->getResultArray();

        // ── Performance médecins (filtres dates + médecin) ───────────
        $medecinPerfWhere = $medecinId ? " AND m.id = {$medecinId}" : '';
        $medecinsPerf = $this->db->query("
            SELECT CONCAT(u.prenom, ' ', u.nom) AS nom,
                   s.nom AS specialite,
                   COUNT(r.id) AS nb_rdv,
                   COUNT(CASE WHEN r.statut = 'termine' THEN 1 END) AS nb_termines,
                   COALESCE(SUM(f.montant_paye), 0) AS revenus
            FROM medecins m
            JOIN users u ON u.id = m.user_id
            LEFT JOIN specialites s ON s.id = m.specialite_id
            LEFT JOIN rendez_vous r ON r.medecin_id = m.id AND r.date_rdv BETWEEN ? AND ?
            LEFT JOIN factures f ON f.medecin_id = m.id AND f.date_facture BETWEEN ? AND ? AND f.statut != 'annule'
            WHERE m.tenant_id = ? AND m.actif = 1
            {$medecinPerfWhere}
            GROUP BY m.id, u.prenom, u.nom, s.nom
            ORDER BY nb_rdv DESC
        ", [$sqlDateFrom, $sqlDateTo, $sqlDateFrom, $sqlDateTo, $tenantId])->getResultArray();

        // ── Nouveaux patients par mois ───────────────────────────────
        $newPatients = $this->db->query("
            SELECT MONTH(created_at) AS mois, COUNT(*) AS nb
            FROM patients
            WHERE tenant_id = ? AND YEAR(created_at) = ?
            GROUP BY mois ORDER BY mois
        ", [$tenantId, $year])->getResultArray();

        $patientsByMonth = array_fill(1, 12, 0);
        foreach ($newPatients as $p) {
            $patientsByMonth[(int)$p['mois']] = (int)$p['nb'];
        }

        // ── Taux d'occupation ────────────────────────────────────────
        $totalRdv      = array_sum(array_column($rdvStatuts, 'nb'));
        $terminesCount = 0;
        $annulesCount  = 0;
        foreach ($rdvStatuts as $s) {
            if ($s['statut'] === 'termine') $terminesCount = (int)$s['nb'];
            if ($s['statut'] === 'annule')  $annulesCount  = (int)$s['nb'];
        }
        $tauxOccupation = $totalRdv > 0 ? round($terminesCount / $totalRdv * 100) : 0;
        $tauxAnnulation = $totalRdv > 0 ? round($annulesCount / $totalRdv * 100) : 0;

        // ── Totaux période filtrée ───────────────────────────────────
        $totals = $this->db->query("
            SELECT
                COALESCE(SUM(total),0) AS total_facture,
                COALESCE(SUM(montant_paye),0) AS total_paye,
                COUNT(*) AS nb_factures
            FROM factures f
            WHERE f.tenant_id = ? AND f.date_facture BETWEEN ? AND ? AND f.statut != 'annule'
            {$medecinSqlF}
        ", [$tenantId, $sqlDateFrom, $sqlDateTo])->getRowArray();

        $totalPatients = $this->db->table('patients')
            ->where('tenant_id', $tenantId)->countAll();

        // ── Revenus par médecin (filtres dates) ──────────────────────
        $revenueByMedecin = $this->db->query("
            SELECT CONCAT(u.prenom,' ',u.nom) AS nom,
                   COALESCE(SUM(f.montant_paye),0) AS revenus,
                   COUNT(f.id) AS nb_factures
            FROM medecins m
            JOIN users u ON u.id = m.user_id
            LEFT JOIN factures f ON f.medecin_id = m.id AND f.date_facture BETWEEN ? AND ? AND f.statut != 'annule'
            WHERE m.tenant_id = ? AND m.actif = 1
            GROUP BY m.id, u.prenom, u.nom
            ORDER BY revenus DESC
        ", [$sqlDateFrom, $sqlDateTo, $tenantId])->getResultArray();

        return view('reports/index', [
            'title'             => 'Rapports & Statistiques',
            'year'              => $year,
            'date_from'         => $dateFrom,
            'date_to'           => $dateTo,
            'medecin_id'        => $medecinId,
            'filterType'        => $filterType,
            'medecinsList'      => $medecinsList,
            'revenueByMonth'    => $revenueByMonth,
            'rdvStatuts'        => $rdvStatuts,
            'medecinsPerf'      => $medecinsPerf,
            'patientsByMonth'   => $patientsByMonth,
            'tauxOccupation'    => $tauxOccupation,
            'tauxAnnulation'    => $tauxAnnulation,
            'terminesCount'     => $terminesCount,
            'annulesCount'      => $annulesCount,
            'totalRdv'          => $totalRdv,
            'totalPatients'     => $totalPatients,
            'totals'            => $totals,
            'revenueByMedecin'  => $revenueByMedecin,
            'useCustomDates'    => $useCustomDates,
            'sqlDateFrom'       => $sqlDateFrom,
            'sqlDateTo'         => $sqlDateTo,
        ]);
    }
}
