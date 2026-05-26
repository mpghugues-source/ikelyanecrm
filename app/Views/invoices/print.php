<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture <?= esc($facture['numero']) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #000; background: #fff; }
        .invoice-page { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 15mm; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .logo-area h1 { font-size: 20pt; color: #0d6efd; }
        .logo-area p { font-size: 9pt; color: #555; margin-top: 4px; }
        .invoice-info { text-align: right; }
        .invoice-info .inv-number { font-size: 18pt; font-weight: bold; color: #0d6efd; }
        .invoice-info p { font-size: 9pt; color: #555; }
        .parties { display: flex; gap: 20px; margin: 20px 0; }
        .party-box { flex: 1; background: #f8f9fa; padding: 12px; border-radius: 6px; }
        .party-box h4 { font-size: 9pt; text-transform: uppercase; color: #888; margin-bottom: 6px; }
        .party-box p { font-size: 10pt; line-height: 1.6; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th { background: #0d6efd; color: #fff; padding: 8px 10px; text-align: left; font-size: 10pt; }
        .items-table td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 10pt; }
        .items-table tr:last-child td { border-bottom: none; }
        .totals { margin-left: auto; width: 200px; }
        .totals table { width: 100%; }
        .totals td { padding: 5px 0; font-size: 10pt; }
        .totals .total-row td { font-size: 13pt; font-weight: bold; color: #0d6efd; border-top: 2px solid #0d6efd; padding-top: 8px; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; text-align: center; font-size: 9pt; color: #888; }
        .no-print { position: fixed; top: 15px; right: 15px; }
        .statut-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 9pt; }
        .paye { background: #d4edda; color: #155724; }
        .en_attente { background: #fff3cd; color: #856404; }
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="no-print">
    <button onclick="window.print()" style="background:#0d6efd;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer">🖨 Imprimer</button>
    &nbsp;
    <button onclick="window.close()" style="background:#6c757d;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer">✕ Fermer</button>
</div>

<div class="invoice-page">
    <div class="header">
        <div class="logo-area">
            <h1>💙 <?= esc($tenant['nom'] ?? 'IkeylaneMed') ?></h1>
            <p><?= esc($tenant['adresse'] ?? '') ?><br>
            <?= esc($tenant['telephone'] ?? '') ?> | <?= esc($tenant['email'] ?? '') ?></p>
        </div>
        <div class="invoice-info">
            <div class="inv-number">FACTURE</div>
            <p><strong><?= esc($facture['numero']) ?></strong></p>
            <p>Date: <?= date('d/m/Y', strtotime($facture['date_facture'])) ?></p>
            <p><span class="statut-badge <?= $facture['statut'] ?>"><?= ucfirst(str_replace('_',' ',$facture['statut'])) ?></span></p>
        </div>
    </div>

    <div class="parties">
        <div class="party-box">
            <h4>Émetteur</h4>
            <p><strong><?= esc($tenant['nom'] ?? 'IkeylaneMed') ?></strong><br>
            <?= esc($tenant['adresse'] ?? '') ?><br>
            <?= esc($tenant['ville'] ?? '') ?></p>
        </div>
        <div class="party-box">
            <h4>Destinataire</h4>
            <p><strong><?= esc($facture['patient_nom']) ?></strong><br>
            <?= esc($facture['patient_adresse'] ?? '') ?><br>
            <?= esc($facture['patient_tel'] ?? '') ?></p>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="width:60px;text-align:center">Qté</th>
                <th style="width:120px;text-align:right">Prix unit. (DA)</th>
                <th style="width:120px;text-align:right">Total (DA)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['description']) ?></td>
                <td style="text-align:center"><?= $item['quantite'] ?></td>
                <td style="text-align:right"><?= number_format($item['prix_unitaire'], 2, ',', ' ') ?></td>
                <td style="text-align:right"><?= number_format($item['total'], 2, ',', ' ') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="display:flex;justify-content:flex-end">
        <div class="totals">
            <table>
                <tr>
                    <td>Sous-total:</td>
                    <td style="text-align:right"><?= number_format($facture['sous_total'],2,',', ' ') ?> DA</td>
                </tr>
                <?php if ($facture['remise'] > 0): ?>
                <tr>
                    <td style="color:#dc3545">Remise:</td>
                    <td style="text-align:right;color:#dc3545">-<?= number_format($facture['remise'],2,',',' ') ?> DA</td>
                </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td style="text-align:right"><?= number_format($facture['total'],2,',',' ') ?> DA</td>
                </tr>
                <?php if ($facture['montant_paye'] > 0): ?>
                <tr>
                    <td style="color:#198754">Payé:</td>
                    <td style="text-align:right;color:#198754"><?= number_format($facture['montant_paye'],2,',',' ') ?> DA</td>
                </tr>
                <tr>
                    <td style="color:#dc3545">Reste:</td>
                    <td style="text-align:right;color:#dc3545"><?= number_format($facture['total']-$facture['montant_paye'],2,',',' ') ?> DA</td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <?php if ($facture['notes']): ?>
    <div style="margin-top:20px;padding:10px;background:#f8f9fa;border-radius:4px;font-size:10pt">
        <strong>Notes:</strong> <?= esc($facture['notes']) ?>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p>Merci de votre confiance • <?= esc($tenant['nom'] ?? 'IkeylaneMed') ?> • <?= esc($tenant['telephone'] ?? '') ?></p>
    </div>
</div>
</body>
</html>
