<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\InvoiceModel;
use App\Models\TenantModel;
use App\Models\PharmacieModel;
use App\Models\LaboratoireModel;
use App\Models\RadiologieModel;
use App\Models\AmbulanceModel;
use App\Models\RessourceHumaineModel;
use App\Models\NaissanceDecesModel;
use App\Libraries\PdfService;

class ExportController extends BaseController
{
    private function getTenant(): array
    {
        $tenantModel = new TenantModel();
        return $tenantModel->find(session()->get('tenant_id')) ?? [];
    }

    /** Export liste patients PDF */
    public function patientsPdf()
    {
        $tenantId     = session()->get('tenant_id');
        $patientModel = new PatientModel();

        $patients = $patientModel
            ->where('tenant_id', $tenantId)
            ->where('actif', 1)
            ->orderBy('nom', 'ASC')
            ->findAll();

        $html = PdfService::patientsList($patients, $this->getTenant());
        (new PdfService())->stream($html, 'patients_' . date('Y-m-d') . '.pdf');
    }

    /** Export liste patients CSV */
    public function patientsCsv()
    {
        $tenantId     = session()->get('tenant_id');
        $patientModel = new PatientModel();

        $patients = $patientModel
            ->where('tenant_id', $tenantId)
            ->where('actif', 1)
            ->orderBy('nom', 'ASC')
            ->findAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="patients_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

        fputcsv($output, ['N° Dossier','Nom','Prénom','Date naissance','Sexe','Téléphone','Email','Ville','Groupe sanguin','Allergies'], ';');

        foreach ($patients as $p) {
            fputcsv($output, [
                $p['numero_dossier'] ?? '',
                $p['nom'],
                $p['prenom'],
                $p['date_naissance'] ? date('d/m/Y', strtotime($p['date_naissance'])) : '',
                $p['sexe'] ?? '',
                $p['telephone'] ?? '',
                $p['email'] ?? '',
                $p['ville'] ?? '',
                $p['groupe_sanguin'] ?? '',
                $p['allergies'] ?? '',
            ], ';');
        }

        fclose($output);
        exit;
    }

    /** Export rendez-vous PDF */
    public function appointmentsPdf()
    {
        $tenantId = session()->get('tenant_id');
        $db       = \Config\Database::connect();

        $rdvs = $db->query("
            SELECT r.*, CONCAT(p.nom, ' ', p.prenom) AS patient_nom,
                   CONCAT('Dr. ', u.prenom, ' ', u.nom) AS medecin_nom
            FROM rendez_vous r
            LEFT JOIN patients p ON r.patient_id = p.id
            LEFT JOIN medecins m ON r.medecin_id = m.id
            LEFT JOIN users u ON m.user_id = u.id
            WHERE r.tenant_id = ?
            ORDER BY r.date_rdv DESC, r.heure_rdv ASC
            LIMIT 500
        ", [$tenantId])->getResultArray();

        $html = PdfService::appointmentsList($rdvs, $this->getTenant());
        (new PdfService())->stream($html, 'rendez_vous_' . date('Y-m-d') . '.pdf');
    }

    /** Export rapport financier PDF */
    public function revenuePdf()
    {
        $tenantId = session()->get('tenant_id');
        $db       = \Config\Database::connect();

        $invoices = $db->query("
            SELECT f.*, CONCAT(p.nom, ' ', p.prenom) AS patient_nom
            FROM factures f
            LEFT JOIN patients p ON f.patient_id = p.id
            WHERE f.tenant_id = ?
            ORDER BY f.date_facture DESC
            LIMIT 500
        ", [$tenantId])->getResultArray();

        $statsRow = $db->query("
            SELECT
                COALESCE(SUM(total),0) AS total_facture,
                COALESCE(SUM(montant_paye),0) AS total_paye,
                COALESCE(SUM(total - montant_paye),0) AS en_attente
            FROM factures WHERE tenant_id = ? AND statut != 'annule'
        ", [$tenantId])->getRowArray();

        $html = PdfService::revenueReport($invoices, $statsRow ?? [], $this->getTenant());
        (new PdfService())->stream($html, 'rapport_financier_' . date('Y-m-d') . '.pdf');
    }

    /** Export RDV CSV */
    public function appointmentsCsv()
    {
        $tenantId = session()->get('tenant_id');
        $db       = \Config\Database::connect();

        $rdvs = $db->query("
            SELECT r.date_rdv, r.heure_rdv, CONCAT(p.nom,' ',p.prenom) AS patient,
                   CONCAT('Dr. ',u.prenom,' ',u.nom) AS medecin,
                   r.motif, r.statut, r.type_rdv
            FROM rendez_vous r
            LEFT JOIN patients p ON r.patient_id = p.id
            LEFT JOIN medecins m ON r.medecin_id = m.id
            LEFT JOIN users u ON m.user_id = u.id
            WHERE r.tenant_id = ?
            ORDER BY r.date_rdv DESC
        ", [$tenantId])->getResultArray();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="rendez_vous_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['Date','Heure','Patient','Médecin','Motif','Statut','Type'], ';');
        foreach ($rdvs as $r) {
            fputcsv($output, [
                date('d/m/Y', strtotime($r['date_rdv'])),
                substr($r['heure_rdv'],0,5),
                $r['patient'],
                $r['medecin'],
                $r['motif'],
                $r['statut'],
                $r['type_rdv'],
            ], ';');
        }
        fclose($output);
        exit;
    }

    // ─── PHARMACIE ───────────────────────────────────────────────────

    /** Export pharmacie PDF */
    public function pharmaciePdf()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new PharmacieModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('nom')->findAll();
        $tenant   = $this->getTenant();

        $color = $tenant['couleur'] ?? '#1a56db';
        $rows  = '';
        foreach ($items as $i => $m) {
            $bg      = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $stockBg = $m['stock_actuel'] <= $m['stock_minimum'] ? '#fee2e2' : '#d1fae5';
            $stockCl = $m['stock_actuel'] <= $m['stock_minimum'] ? '#dc2626' : '#059669';
            $rows   .= "
            <tr style='background:{$bg}'>
                <td style='padding:7px 10px;font-size:.82rem;font-weight:600'>" . htmlspecialchars($m['nom']) . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($m['categorie'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($m['forme'] ?? '') . ' ' . htmlspecialchars($m['dosage'] ?? '') . "</td>
                <td style='padding:7px 10px;font-size:.82rem;text-align:center'>
                    <span style='background:{$stockBg};color:{$stockCl};padding:2px 8px;border-radius:4px;font-weight:700'>{$m['stock_actuel']}</span>
                </td>
                <td style='padding:7px 10px;font-size:.82rem;text-align:center'>{$m['stock_minimum']}</td>
                <td style='padding:7px 10px;font-size:.82rem;text-align:right'>" . number_format($m['prix_unitaire'], 2, ',', ' ') . " DA</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . ($m['date_expiration'] ? date('d/m/Y', strtotime($m['date_expiration'])) : '—') . "</td>
            </tr>";
        }

        $html = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family:DejaVu Sans,Arial,sans-serif;margin:0;padding:0; } body { padding:28px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:9px 10px;font-size:.78rem;text-align:left; } h3 { font-size:.95rem;color:#64748b;margin-bottom:14px;font-weight:400; }</style>
        </head><body>"
            . PdfService::htmlHeader($tenant, 'Stock Pharmacie', 'Exporté le ' . date('d/m/Y à H:i'))
            . "<h3>Total : " . count($items) . " médicament(s)</h3>
        <table>
            <thead><tr><th>Nom</th><th>Catégorie</th><th>Forme / Dosage</th><th style='text-align:center'>Stock actuel</th><th style='text-align:center'>Stock min.</th><th style='text-align:right'>Prix unitaire</th><th>Expiration</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";

        (new PdfService())->stream($html, 'pharmacie_stock_' . date('Y-m-d') . '.pdf');
    }

    /** Export pharmacie CSV */
    public function pharmacieCsv()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new PharmacieModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('nom')->findAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="pharmacie_stock_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Nom','Catégorie','Forme','Dosage','Stock actuel','Stock minimum','Prix unitaire (DA)','Fournisseur','Date expiration'], ';');
        foreach ($items as $m) {
            fputcsv($output, [
                $m['nom'],
                $m['categorie'] ?? '',
                $m['forme'] ?? '',
                $m['dosage'] ?? '',
                $m['stock_actuel'],
                $m['stock_minimum'],
                number_format($m['prix_unitaire'], 2, ',', ''),
                $m['fournisseur'] ?? '',
                $m['date_expiration'] ? date('d/m/Y', strtotime($m['date_expiration'])) : '',
            ], ';');
        }
        fclose($output);
        exit;
    }

    // ─── LABORATOIRE ─────────────────────────────────────────────────

    /** Export laboratoire PDF */
    public function laboratoirePdf()
    {
        $tenantId = session()->get('tenant_id');
        $db       = \Config\Database::connect();
        $tenant   = $this->getTenant();

        $items = $db->query("
            SELECT l.*, CONCAT(pu.prenom,' ',pu.nom) AS patient_nom, CONCAT('Dr. ',mu.prenom,' ',mu.nom) AS medecin_nom
            FROM laboratoire l
            LEFT JOIN patients p ON p.id = l.patient_id
            LEFT JOIN users pu ON pu.id = p.user_id
            LEFT JOIN medecins m ON m.id = l.medecin_id
            LEFT JOIN users mu ON mu.id = m.user_id
            WHERE l.tenant_id = ? AND l.actif = 1
            ORDER BY l.date_demande DESC
            LIMIT 500
        ", [$tenantId])->getResultArray();

        $color = $tenant['couleur'] ?? '#1a56db';
        $statusColors = ['en_attente' => '#d97706', 'en_cours' => '#1a56db', 'resultat_disponible' => '#059669', 'annule' => '#64748b'];
        $rows  = '';
        foreach ($items as $i => $a) {
            $bg = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $sc = $statusColors[$a['statut']] ?? '#94a3b8';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:7px 10px;font-size:.8rem'><code>" . htmlspecialchars($a['numero'] ?? '') . "</code></td>
                <td style='padding:7px 10px;font-size:.8rem;font-weight:600'>" . htmlspecialchars($a['patient_nom'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.8rem'>" . htmlspecialchars($a['type_analyse']) . "</td>
                <td style='padding:7px 10px;font-size:.8rem'>" . date('d/m/Y', strtotime($a['date_demande'])) . "</td>
                <td style='padding:7px 10px;font-size:.78rem'><span style='background:{$sc};color:#fff;padding:2px 8px;border-radius:4px'>" . ucfirst(str_replace('_', ' ', $a['statut'])) . "</span></td>
                <td style='padding:7px 10px;font-size:.8rem'>" . ($a['urgence'] ? '<span style="color:#dc2626;font-weight:700">Urgent</span>' : '') . "</td>
            </tr>";
        }

        $html = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family:DejaVu Sans,Arial,sans-serif;margin:0;padding:0; } body { padding:28px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:9px 10px;font-size:.78rem;text-align:left; } h3 { font-size:.95rem;color:#64748b;margin-bottom:14px;font-weight:400; }</style>
        </head><body>"
            . PdfService::htmlHeader($tenant, 'Analyses Laboratoire', 'Exporté le ' . date('d/m/Y à H:i'))
            . "<h3>Total : " . count($items) . " analyse(s)</h3>
        <table>
            <thead><tr><th>Numéro</th><th>Patient</th><th>Type d'analyse</th><th>Date demande</th><th>Statut</th><th>Urgence</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";

        (new PdfService())->stream($html, 'laboratoire_' . date('Y-m-d') . '.pdf');
    }

    /** Export laboratoire CSV */
    public function laboratoireCsv()
    {
        $tenantId = session()->get('tenant_id');
        $db       = \Config\Database::connect();

        $items = $db->query("
            SELECT l.numero, CONCAT(pu.prenom,' ',pu.nom) AS patient_nom, CONCAT('Dr. ',mu.prenom,' ',mu.nom) AS medecin_nom,
                   l.type_analyse, l.date_demande, l.date_resultat, l.statut, l.urgence, l.notes
            FROM laboratoire l
            LEFT JOIN patients p ON p.id = l.patient_id
            LEFT JOIN users pu ON pu.id = p.user_id
            LEFT JOIN medecins m ON m.id = l.medecin_id
            LEFT JOIN users mu ON mu.id = m.user_id
            WHERE l.tenant_id = ? AND l.actif = 1
            ORDER BY l.date_demande DESC
        ", [$tenantId])->getResultArray();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="laboratoire_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Numéro','Patient','Médecin','Type analyse','Date demande','Date résultat','Statut','Urgent','Notes'], ';');
        foreach ($items as $a) {
            fputcsv($output, [
                $a['numero'] ?? '',
                $a['patient_nom'] ?? '',
                $a['medecin_nom'] ?? '',
                $a['type_analyse'],
                $a['date_demande'] ? date('d/m/Y', strtotime($a['date_demande'])) : '',
                $a['date_resultat'] ? date('d/m/Y', strtotime($a['date_resultat'])) : '',
                $a['statut'],
                $a['urgence'] ? 'Oui' : 'Non',
                $a['notes'] ?? '',
            ], ';');
        }
        fclose($output);
        exit;
    }

    // ─── RADIOLOGIE ──────────────────────────────────────────────────

    /** Export radiologie PDF */
    public function radiologiePdf()
    {
        $tenantId = session()->get('tenant_id');
        $db       = \Config\Database::connect();
        $tenant   = $this->getTenant();

        $items = $db->query("
            SELECT r.*, CONCAT(pu.prenom,' ',pu.nom) AS patient_nom, CONCAT('Dr. ',mu.prenom,' ',mu.nom) AS medecin_nom
            FROM radiologie r
            LEFT JOIN patients p ON p.id = r.patient_id
            LEFT JOIN users pu ON pu.id = p.user_id
            LEFT JOIN medecins m ON m.id = r.medecin_id
            LEFT JOIN users mu ON mu.id = m.user_id
            WHERE r.tenant_id = ? AND r.actif = 1
            ORDER BY r.date_demande DESC
            LIMIT 500
        ", [$tenantId])->getResultArray();

        $color = $tenant['couleur'] ?? '#1a56db';
        $statusColors = ['en_attente' => '#d97706', 'programme' => '#1a56db', 'realise' => '#059669', 'annule' => '#64748b'];
        $rows  = '';
        foreach ($items as $i => $e) {
            $bg = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $sc = $statusColors[$e['statut']] ?? '#94a3b8';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:7px 10px;font-size:.8rem'><code>" . htmlspecialchars($e['numero'] ?? '') . "</code></td>
                <td style='padding:7px 10px;font-size:.8rem;font-weight:600'>" . htmlspecialchars($e['patient_nom'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.8rem'>" . htmlspecialchars($e['type_examen']) . "</td>
                <td style='padding:7px 10px;font-size:.8rem'>" . htmlspecialchars($e['region'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.8rem'>" . date('d/m/Y', strtotime($e['date_demande'])) . "</td>
                <td style='padding:7px 10px;font-size:.78rem'><span style='background:{$sc};color:#fff;padding:2px 8px;border-radius:4px'>" . ucfirst(str_replace('_', ' ', $e['statut'])) . "</span></td>
            </tr>";
        }

        $html = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family:DejaVu Sans,Arial,sans-serif;margin:0;padding:0; } body { padding:28px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:9px 10px;font-size:.78rem;text-align:left; } h3 { font-size:.95rem;color:#64748b;margin-bottom:14px;font-weight:400; }</style>
        </head><body>"
            . PdfService::htmlHeader($tenant, 'Examens Radiologie', 'Exporté le ' . date('d/m/Y à H:i'))
            . "<h3>Total : " . count($items) . " examen(s)</h3>
        <table>
            <thead><tr><th>Numéro</th><th>Patient</th><th>Type examen</th><th>Région</th><th>Date demande</th><th>Statut</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";

        (new PdfService())->stream($html, 'radiologie_' . date('Y-m-d') . '.pdf');
    }

    /** Export radiologie CSV */
    public function radiologieCsv()
    {
        $tenantId = session()->get('tenant_id');
        $db       = \Config\Database::connect();

        $items = $db->query("
            SELECT r.numero, CONCAT(pu.prenom,' ',pu.nom) AS patient_nom, CONCAT('Dr. ',mu.prenom,' ',mu.nom) AS medecin_nom,
                   r.type_examen, r.region, r.date_demande, r.date_examen, r.statut, r.urgence, r.description
            FROM radiologie r
            LEFT JOIN patients p ON p.id = r.patient_id
            LEFT JOIN users pu ON pu.id = p.user_id
            LEFT JOIN medecins m ON m.id = r.medecin_id
            LEFT JOIN users mu ON mu.id = m.user_id
            WHERE r.tenant_id = ? AND r.actif = 1
            ORDER BY r.date_demande DESC
        ", [$tenantId])->getResultArray();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="radiologie_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Numéro','Patient','Médecin','Type examen','Région','Date demande','Date examen','Statut','Urgent','Description'], ';');
        foreach ($items as $e) {
            fputcsv($output, [
                $e['numero'] ?? '',
                $e['patient_nom'] ?? '',
                $e['medecin_nom'] ?? '',
                $e['type_examen'],
                $e['region'] ?? '',
                $e['date_demande'] ? date('d/m/Y', strtotime($e['date_demande'])) : '',
                $e['date_examen'] ? date('d/m/Y', strtotime($e['date_examen'])) : '',
                $e['statut'],
                $e['urgence'] ? 'Oui' : 'Non',
                $e['description'] ?? '',
            ], ';');
        }
        fclose($output);
        exit;
    }

    // ─── AMBULANCES ──────────────────────────────────────────────────

    /** Export ambulances PDF */
    public function ambulancesPdf()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new AmbulanceModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('immatriculation')->findAll();
        $tenant   = $this->getTenant();

        $color = $tenant['couleur'] ?? '#1a56db';
        $statusColors = ['disponible' => '#059669', 'en_mission' => '#d97706', 'maintenance' => '#1a56db', 'hors_service' => '#dc2626'];
        $rows  = '';
        foreach ($items as $i => $v) {
            $bg = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $sc = $statusColors[$v['statut']] ?? '#94a3b8';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:7px 10px;font-size:.82rem;font-weight:600'>" . htmlspecialchars($v['immatriculation']) . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($v['type_vehicule'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($v['modele'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.78rem'><span style='background:{$sc};color:#fff;padding:2px 8px;border-radius:4px'>" . ucfirst(str_replace('_', ' ', $v['statut'])) . "</span></td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($v['chauffeur_nom'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . ($v['date_revision'] ? date('d/m/Y', strtotime($v['date_revision'])) : '—') . "</td>
            </tr>";
        }

        $html = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family:DejaVu Sans,Arial,sans-serif;margin:0;padding:0; } body { padding:28px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:9px 10px;font-size:.78rem;text-align:left; } h3 { font-size:.95rem;color:#64748b;margin-bottom:14px;font-weight:400; }</style>
        </head><body>"
            . PdfService::htmlHeader($tenant, 'Parc Ambulances', 'Exporté le ' . date('d/m/Y à H:i'))
            . "<h3>Total : " . count($items) . " véhicule(s)</h3>
        <table>
            <thead><tr><th>Immatriculation</th><th>Type</th><th>Modèle</th><th>Statut</th><th>Chauffeur</th><th>Prochaine révision</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";

        (new PdfService())->stream($html, 'ambulances_' . date('Y-m-d') . '.pdf');
    }

    /** Export ambulances CSV */
    public function ambulancesCsv()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new AmbulanceModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('immatriculation')->findAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="ambulances_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Immatriculation','Type véhicule','Modèle','Statut','Chauffeur','Tél. chauffeur','Date révision','Notes'], ';');
        foreach ($items as $v) {
            fputcsv($output, [
                $v['immatriculation'],
                $v['type_vehicule'] ?? '',
                $v['modele'] ?? '',
                $v['statut'],
                $v['chauffeur_nom'] ?? '',
                $v['chauffeur_tel'] ?? '',
                $v['date_revision'] ? date('d/m/Y', strtotime($v['date_revision'])) : '',
                $v['notes'] ?? '',
            ], ';');
        }
        fclose($output);
        exit;
    }

    // ─── RESSOURCES HUMAINES ─────────────────────────────────────────

    /** Export RH PDF */
    public function rhPdf()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new RessourceHumaineModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('nom')->findAll();
        $tenant   = $this->getTenant();

        $color = $tenant['couleur'] ?? '#1a56db';
        $rows  = '';
        foreach ($items as $i => $emp) {
            $bg = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:7px 10px;font-size:.82rem;font-weight:600'>" . htmlspecialchars($emp['nom'] . ' ' . $emp['prenom']) . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($emp['poste']) . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($emp['departement'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.82rem'><span style='background:#f1f5f9;padding:2px 8px;border-radius:4px'>" . htmlspecialchars($emp['contrat'] ?? '—') . "</span></td>
                <td style='padding:7px 10px;font-size:.82rem'>" . ($emp['date_embauche'] ? date('d/m/Y', strtotime($emp['date_embauche'])) : '—') . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($emp['telephone'] ?? '—') . "</td>
            </tr>";
        }

        $html = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family:DejaVu Sans,Arial,sans-serif;margin:0;padding:0; } body { padding:28px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:9px 10px;font-size:.78rem;text-align:left; } h3 { font-size:.95rem;color:#64748b;margin-bottom:14px;font-weight:400; }</style>
        </head><body>"
            . PdfService::htmlHeader($tenant, 'Ressources Humaines', 'Exporté le ' . date('d/m/Y à H:i'))
            . "<h3>Total : " . count($items) . " employé(s)</h3>
        <table>
            <thead><tr><th>Nom complet</th><th>Poste</th><th>Département</th><th>Contrat</th><th>Date embauche</th><th>Téléphone</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";

        (new PdfService())->stream($html, 'rh_employes_' . date('Y-m-d') . '.pdf');
    }

    /** Export RH CSV */
    public function rhCsv()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new RessourceHumaineModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('nom')->findAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="rh_employes_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Nom','Prénom','Poste','Département','Contrat','Date embauche','Salaire (DA)','Téléphone','Email','Notes'], ';');
        foreach ($items as $emp) {
            fputcsv($output, [
                $emp['nom'],
                $emp['prenom'],
                $emp['poste'],
                $emp['departement'] ?? '',
                $emp['contrat'] ?? '',
                $emp['date_embauche'] ? date('d/m/Y', strtotime($emp['date_embauche'])) : '',
                $emp['salaire'] ?? '',
                $emp['telephone'] ?? '',
                $emp['email'] ?? '',
                $emp['notes'] ?? '',
            ], ';');
        }
        fclose($output);
        exit;
    }

    // ─── NAISSANCES & DÉCÈS ──────────────────────────────────────────

    /** Export naissances/décès PDF */
    public function naissanceDecesPdf()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new NaissanceDecesModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('date_evenement', 'DESC')->findAll();
        $tenant   = $this->getTenant();

        $color = $tenant['couleur'] ?? '#1a56db';
        $rows  = '';
        foreach ($items as $i => $ev) {
            $bg       = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $typeBg   = $ev['type_evenement'] === 'naissance' ? '#dbeafe' : '#1f2937';
            $typeCl   = $ev['type_evenement'] === 'naissance' ? '#1a56db' : '#ffffff';
            $typeLabel = $ev['type_evenement'] === 'naissance' ? 'Naissance' : 'Décès';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:7px 10px;font-size:.82rem'><span style='background:{$typeBg};color:{$typeCl};padding:2px 8px;border-radius:4px;font-weight:700'>{$typeLabel}</span></td>
                <td style='padding:7px 10px;font-size:.82rem;font-weight:600'>" . htmlspecialchars($ev['nom'] . ' ' . ($ev['prenom'] ?? '')) . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . date('d/m/Y', strtotime($ev['date_evenement'])) . ($ev['heure_evenement'] ? ' à ' . substr($ev['heure_evenement'], 0, 5) : '') . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($ev['lieu'] ?? '—') . "</td>
                <td style='padding:7px 10px;font-size:.82rem'>" . htmlspecialchars($ev['numero_certificat'] ?? '—') . "</td>
            </tr>";
        }

        $html = "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family:DejaVu Sans,Arial,sans-serif;margin:0;padding:0; } body { padding:28px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:9px 10px;font-size:.78rem;text-align:left; } h3 { font-size:.95rem;color:#64748b;margin-bottom:14px;font-weight:400; }</style>
        </head><body>"
            . PdfService::htmlHeader($tenant, 'Naissances & Décès', 'Exporté le ' . date('d/m/Y à H:i'))
            . "<h3>Total : " . count($items) . " événement(s)</h3>
        <table>
            <thead><tr><th>Type</th><th>Nom complet</th><th>Date / Heure</th><th>Lieu</th><th>N° Certificat</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";

        (new PdfService())->stream($html, 'naissances_deces_' . date('Y-m-d') . '.pdf');
    }

    /** Export naissances/décès CSV */
    public function naissanceDecesCsv()
    {
        $tenantId = session()->get('tenant_id');
        $model    = new NaissanceDecesModel();
        $items    = $model->where('tenant_id', $tenantId)->where('actif', 1)->orderBy('date_evenement', 'DESC')->findAll();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="naissances_deces_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, ['Type','Nom','Prénom','Date','Heure','Lieu','N° Certificat','Notes'], ';');
        foreach ($items as $ev) {
            fputcsv($output, [
                $ev['type_evenement'],
                $ev['nom'],
                $ev['prenom'] ?? '',
                $ev['date_evenement'] ? date('d/m/Y', strtotime($ev['date_evenement'])) : '',
                $ev['heure_evenement'] ? substr($ev['heure_evenement'], 0, 5) : '',
                $ev['lieu'] ?? '',
                $ev['numero_certificat'] ?? '',
                $ev['notes'] ?? '',
            ], ';');
        }
        fclose($output);
        exit;
    }
}
