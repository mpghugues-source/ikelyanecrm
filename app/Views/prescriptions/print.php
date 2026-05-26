<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance <?= esc($ordonnance['numero']) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #000; background: #fff; }
        .print-page { width: 210mm; min-height: 148mm; margin: 0 auto; padding: 15mm; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 15px; }
        .clinic-info h2 { font-size: 16pt; color: #0d6efd; }
        .clinic-info p { font-size: 9pt; color: #555; margin: 2px 0; }
        .doctor-info { text-align: right; }
        .doctor-info h3 { font-size: 13pt; color: #000; }
        .doctor-info p { font-size: 9pt; color: #555; }
        .ordonnance-title { text-align: center; font-size: 14pt; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin: 15px 0; color: #0d6efd; }
        .patient-box { background: #f5f5f5; border-left: 4px solid #0d6efd; padding: 8px 12px; margin-bottom: 15px; font-size: 11pt; }
        .medications-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .medications-table th { background: #0d6efd; color: #fff; padding: 6px 10px; font-size: 10pt; text-align: left; }
        .medications-table td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 11pt; vertical-align: top; }
        .medications-table tr:nth-child(even) td { background: #fafafa; }
        .med-name { font-weight: bold; }
        .instructions-box { background: #fffde7; border: 1px solid #fbc02d; border-radius: 4px; padding: 10px; margin-bottom: 15px; font-size: 10pt; }
        .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; }
        .signature-box { text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 120px; margin: 30px auto 0; }
        .validity { font-size: 9pt; color: #555; }
        .no-print { position: fixed; top: 15px; right: 15px; }
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
<div class="no-print">
    <button onclick="window.print()" style="background:#0d6efd;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px">
        🖨 Imprimer
    </button>
    &nbsp;
    <button onclick="window.close()" style="background:#6c757d;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px">
        ✕ Fermer
    </button>
</div>

<div class="print-page">
    <!-- En-tête -->
    <div class="header">
        <div class="clinic-info">
            <h2><?= esc($tenant['nom'] ?? 'IkeylaneMed') ?></h2>
            <p><?= esc($tenant['adresse'] ?? '') ?></p>
            <p><?= esc($tenant['telephone'] ?? '') ?> | <?= esc($tenant['email'] ?? '') ?></p>
        </div>
        <div class="doctor-info">
            <h3>Dr. <?= esc($ordonnance['medecin_nom']) ?></h3>
            <p><?= esc($ordonnance['specialite'] ?? 'Médecin') ?></p>
            <p>N° Ordre: <?= esc($ordonnance['numero_ordre'] ?? '—') ?></p>
        </div>
    </div>

    <!-- Titre -->
    <div class="ordonnance-title">✦ Ordonnance Médicale ✦</div>

    <!-- Patient -->
    <div class="patient-box">
        <strong>Patient(e):</strong> <?= esc($ordonnance['patient_nom']) ?>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Date de naissance:</strong> <?= $ordonnance['patient_ddn'] ? date('d/m/Y', strtotime($ordonnance['patient_ddn'])) : '—' ?>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Date:</strong> <?= date('d/m/Y', strtotime($ordonnance['date_ordonnance'])) ?>
    </div>

    <!-- Médicaments -->
    <table class="medications-table">
        <thead>
            <tr>
                <th style="width:30%">Médicament</th>
                <th style="width:30%">Posologie</th>
                <th style="width:20%">Fréquence / Durée</th>
                <th style="width:10%">Qté</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
            <tr>
                <td class="med-name">
                    <?= esc($item['medicament_nom'] ?? $item['medicament_libre'] ?? '—') ?>
                    <?php if ($item['forme']): ?><br><small style="font-weight:normal;color:#666"><?= esc($item['forme']) ?></small><?php endif; ?>
                </td>
                <td><?= esc($item['posologie']) ?></td>
                <td>
                    <?= esc($item['frequence'] ?? '') ?>
                    <?php if ($item['duree']): ?><br><em><?= esc($item['duree']) ?></em><?php endif; ?>
                </td>
                <td><?= $item['quantite'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Instructions -->
    <?php if ($ordonnance['instructions']): ?>
    <div class="instructions-box">
        <strong>Instructions:</strong> <?= esc($ordonnance['instructions']) ?>
    </div>
    <?php endif; ?>

    <!-- Pied de page -->
    <div class="footer">
        <div class="validity">
            N° <?= esc($ordonnance['numero']) ?><br>
            Valable <?= $ordonnance['validite_jours'] ?> jours à compter du <?= date('d/m/Y', strtotime($ordonnance['date_ordonnance'])) ?>
        </div>
        <div class="signature-box">
            <div>Signature & Cachet</div>
            <div class="signature-line"></div>
            <div style="margin-top:4px;font-size:10pt">Dr. <?= esc($ordonnance['medecin_nom']) ?></div>
        </div>
    </div>
</div>
</body>
</html>
