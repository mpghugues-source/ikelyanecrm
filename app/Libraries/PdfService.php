<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    protected Dompdf $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isPhpEnabled', false);

        $this->dompdf = new Dompdf($options);
    }

    public function stream(string $html, string $filename = 'document.pdf', bool $inline = false): void
    {
        $this->dompdf->loadHtml($html, 'UTF-8');
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        $this->dompdf->stream($filename, ['Attachment' => $inline ? 0 : 1]);
        exit;
    }

    public function output(string $html): string
    {
        $this->dompdf->loadHtml($html, 'UTF-8');
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    /** HTML commun : entête de document */
    public static function htmlHeader(array $tenant, string $docType, string $docNumber): string
    {
        $color = $tenant['couleur'] ?? '#1a56db';
        return "
        <div style='display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;padding-bottom:16px;border-bottom:3px solid {$color}'>
            <div>
                <div style='font-size:1.5rem;font-weight:800;color:{$color}'>" . htmlspecialchars($tenant['nom'] ?? 'Clinique') . "</div>
                " . ($tenant['adresse'] ? "<div style='color:#64748b;font-size:.85rem'>" . htmlspecialchars($tenant['adresse']) . "</div>" : "") . "
                " . ($tenant['telephone'] ? "<div style='color:#64748b;font-size:.85rem'>Tél : " . htmlspecialchars($tenant['telephone']) . "</div>" : "") . "
                " . ($tenant['email'] ? "<div style='color:#64748b;font-size:.85rem'>Email : " . htmlspecialchars($tenant['email']) . "</div>" : "") . "
            </div>
            <div style='text-align:right'>
                <div style='background:{$color};color:#fff;padding:8px 20px;border-radius:8px;font-weight:700;font-size:.9rem'>{$docType}</div>
                <div style='color:#64748b;font-size:.85rem;margin-top:8px'>" . htmlspecialchars($docNumber) . "</div>
                <div style='color:#64748b;font-size:.8rem'>Le " . date('d/m/Y') . "</div>
            </div>
        </div>";
    }

    /** Rapport patients PDF */
    public static function patientsList(array $patients, array $tenant): string
    {
        $color  = $tenant['couleur'] ?? '#1a56db';
        $rows   = '';
        foreach ($patients as $i => $p) {
            $bg = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:8px 12px;font-size:.85rem'>" . htmlspecialchars($p['numero_dossier'] ?? '') . "</td>
                <td style='padding:8px 12px;font-size:.85rem;font-weight:600'>" . htmlspecialchars($p['nom'].' '.$p['prenom']) . "</td>
                <td style='padding:8px 12px;font-size:.85rem'>" . htmlspecialchars($p['date_naissance'] ? date('d/m/Y', strtotime($p['date_naissance'])) : '—') . "</td>
                <td style='padding:8px 12px;font-size:.85rem'>" . htmlspecialchars($p['sexe'] ?? '—') . "</td>
                <td style='padding:8px 12px;font-size:.85rem'>" . htmlspecialchars($p['telephone'] ?? '—') . "</td>
                <td style='padding:8px 12px;font-size:.85rem'>" . htmlspecialchars($p['groupe_sanguin'] ?? '—') . "</td>
            </tr>";
        }

        return "
        <!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>
            * { font-family: DejaVu Sans, Arial, sans-serif; margin:0;padding:0; }
            body { padding: 32px; color: #374151; }
            table { width:100%; border-collapse: collapse; }
            th { background:{$color}; color:#fff; padding:10px 12px; font-size:.8rem; text-align:left; }
            h3 { font-size:1rem; color:#64748b; margin-bottom:16px; font-weight:400; }
        </style>
        </head><body>
        " . self::htmlHeader($tenant, 'Liste Patients', 'Exporté le '.date('d/m/Y à H:i')) . "
        <h3>Total : " . count($patients) . " patient(s)</h3>
        <table>
            <thead>
                <tr>
                    <th>N° Dossier</th><th>Nom complet</th><th>Date naissance</th>
                    <th>Sexe</th><th>Téléphone</th><th>Groupe sanguin</th>
                </tr>
            </thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";
    }

    /** Rapport RDV PDF */
    public static function appointmentsList(array $rdvs, array $tenant): string
    {
        $color = $tenant['couleur'] ?? '#1a56db';
        $statusColors = [
            'planifie'  => '#94a3b8',
            'confirme'  => '#1a56db',
            'en_cours'  => '#d97706',
            'termine'   => '#059669',
            'annule'    => '#dc2626',
            'absent'    => '#7c3aed',
        ];
        $rows = '';
        foreach ($rdvs as $i => $r) {
            $bg  = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $sc  = $statusColors[$r['statut']] ?? '#94a3b8';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:8px 10px;font-size:.8rem'>" . date('d/m/Y', strtotime($r['date_rdv'])) . "</td>
                <td style='padding:8px 10px;font-size:.8rem'>" . substr($r['heure_rdv'],0,5) . "</td>
                <td style='padding:8px 10px;font-size:.8rem;font-weight:600'>" . htmlspecialchars($r['patient_nom']??$r['nom']??'—') . "</td>
                <td style='padding:8px 10px;font-size:.8rem'>" . htmlspecialchars($r['medecin_nom']??'—') . "</td>
                <td style='padding:8px 10px;font-size:.8rem'>" . htmlspecialchars($r['motif']??'—') . "</td>
                <td style='padding:8px 10px;font-size:.78rem'>
                    <span style='background:{$sc};color:#fff;padding:2px 8px;border-radius:4px'>" . ucfirst($r['statut']) . "</span>
                </td>
            </tr>";
        }

        return "
        <!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family: DejaVu Sans, Arial, sans-serif; margin:0;padding:0; } body { padding:32px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:10px;font-size:.78rem;text-align:left; } h3 { font-size:1rem;color:#64748b;margin-bottom:16px;font-weight:400; }</style>
        </head><body>
        " . self::htmlHeader($tenant, 'Liste Rendez-vous', 'Exporté le '.date('d/m/Y à H:i')) . "
        <h3>Total : " . count($rdvs) . " rendez-vous</h3>
        <table>
            <thead><tr><th>Date</th><th>Heure</th><th>Patient</th><th>Médecin</th><th>Motif</th><th>Statut</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";
    }

    /** Rapport financier PDF */
    public static function revenueReport(array $invoices, array $stats, array $tenant): string
    {
        $color = $tenant['couleur'] ?? '#1a56db';
        $rows  = '';
        foreach ($invoices as $i => $f) {
            $bg  = $i % 2 === 0 ? '#fff' : '#f8fafc';
            $rows .= "
            <tr style='background:{$bg}'>
                <td style='padding:8px 10px;font-size:.8rem'>" . htmlspecialchars($f['numero']??'') . "</td>
                <td style='padding:8px 10px;font-size:.8rem'>" . date('d/m/Y', strtotime($f['date_facture'])) . "</td>
                <td style='padding:8px 10px;font-size:.8rem;font-weight:600'>" . htmlspecialchars($f['patient_nom']??'—') . "</td>
                <td style='padding:8px 10px;font-size:.8rem;text-align:right'>" . number_format($f['total'],0,',','.')." DA" . "</td>
                <td style='padding:8px 10px;font-size:.8rem;text-align:right'>" . number_format($f['montant_paye'],0,',','.')." DA" . "</td>
                <td style='padding:8px 10px;font-size:.78rem;text-align:center'>" . ucfirst($f['statut']) . "</td>
            </tr>";
        }

        return "
        <!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'>
        <style>* { font-family:DejaVu Sans,Arial,sans-serif;margin:0;padding:0; } body { padding:32px;color:#374151; } table { width:100%;border-collapse:collapse; } th { background:{$color};color:#fff;padding:10px;font-size:.78rem;text-align:left; } .summary { display:flex;gap:16px;margin-bottom:20px; } .sum-box { background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 20px;flex:1; } .sum-val { font-size:1.2rem;font-weight:800;color:{$color}; } .sum-lbl { font-size:.75rem;color:#94a3b8; }</style>
        </head><body>
        " . self::htmlHeader($tenant, 'Rapport Financier', date('F Y')) . "
        <table style='margin-bottom:20px;width:auto'>
            <tr>
                <td style='padding:8px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-right:12px'>
                    <div style='font-size:.75rem;color:#94a3b8'>Total facturé</div>
                    <div style='font-size:1.2rem;font-weight:800;color:{$color}'>" . number_format($stats['total_facture']??0,0,',','.')." DA</div>
                </td>
                <td style='padding:0 12px'></td>
                <td style='padding:8px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px'>
                    <div style='font-size:.75rem;color:#94a3b8'>Total encaissé</div>
                    <div style='font-size:1.2rem;font-weight:800;color:#059669'>" . number_format($stats['total_paye']??0,0,',','.')." DA</div>
                </td>
                <td style='padding:0 12px'></td>
                <td style='padding:8px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px'>
                    <div style='font-size:.75rem;color:#94a3b8'>En attente</div>
                    <div style='font-size:1.2rem;font-weight:800;color:#d97706'>" . number_format($stats['en_attente']??0,0,',','.')." DA</div>
                </td>
            </tr>
        </table>
        <table>
            <thead><tr><th>N° Facture</th><th>Date</th><th>Patient</th><th style='text-align:right'>Montant</th><th style='text-align:right'>Payé</th><th style='text-align:center'>Statut</th></tr></thead>
            <tbody>{$rows}</tbody>
        </table>
        </body></html>";
    }
}
