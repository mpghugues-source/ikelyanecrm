<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isAdmin = in_array(session()->get('role'), ['admin','super_admin','secretaire']);
$base    = $isAdmin ? '/admin' : '/medecin';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-clipboard2-pulse text-primary me-2"></i>Consultation du <?= date('d/m/Y', strtotime($record['date_consultation'])) ?></h4>
    <a href="<?= $base ?>/patients/view/<?= $patient['id'] ?>#dossierTab" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour dossier
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        <?php if ($record['motif']): ?>
        <div class="alert alert-light border-start border-primary border-3 mb-4">
            <strong><i class="bi bi-chat-text me-2"></i>Motif :</strong> <?= esc($record['motif']) ?>
        </div>
        <?php endif; ?>

        <?php
        $sections = [
            ['label' => 'Anamnèse / Symptômes',       'icon' => 'bi-journal-text',       'field' => 'symptomes'],
            ['label' => 'Examen clinique',             'icon' => 'bi-stethoscope',        'field' => 'examen_clinique'],
            ['label' => 'Diagnostic',                  'icon' => 'bi-clipboard2-check',   'field' => 'diagnostic'],
            ['label' => 'Traitement / Conduite',       'icon' => 'bi-capsule',            'field' => 'traitement'],
            ['label' => 'Notes complémentaires',       'icon' => 'bi-pencil-square',      'field' => 'observations'],
        ];
        foreach ($sections as $s):
            if (empty($record[$s['field']])) continue;
        ?>
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="<?= $s['icon'] ?> me-2"></i><?= $s['label'] ?></h6>
            </div>
            <div class="card-body">
                <?= nl2br(esc($record[$s['field']])) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="col-lg-4">
        <!-- Constantes vitales -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-activity me-2"></i>Constantes vitales</h6>
            </div>
            <div class="card-body">
                <?php
                $vitals = [
                    ['Poids',              $record['poids'] ? $record['poids'] . ' kg' : null,       'bi-person-fill'],
                    ['Taille',             $record['taille'] ? $record['taille'] . ' cm' : null,      'bi-rulers'],
                    ['Tension artérielle', $record['tension_arterielle'] ?? null,                      'bi-heart-pulse'],
                    ['Température',        $record['temperature'] ? $record['temperature'] . ' °C' : null, 'bi-thermometer-half'],
                    ['Pouls',              $record['pouls'] ? $record['pouls'] . ' bpm' : null,        'bi-activity'],
                ];
                $hasAny = false;
                foreach ($vitals as [$label, $val, $icon]):
                    if (! $val) continue;
                    $hasAny = true;
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small"><i class="<?= $icon ?> me-1"></i><?= $label ?></span>
                    <strong><?= esc($val) ?></strong>
                </div>
                <?php endforeach; ?>
                <?php if (!empty($record['poids']) && !empty($record['taille'])): ?>
                <?php $imc = round($record['poids'] / pow($record['taille']/100, 2), 1); ?>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small"><i class="bi bi-calculator me-1"></i>IMC</span>
                    <strong><?= $imc ?> kg/m²</strong>
                </div>
                <?php endif; ?>
                <?php if (! $hasAny): ?>
                <p class="text-muted mb-0 small">Aucune constante enregistrée.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Patient info -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Patient</h6>
            </div>
            <div class="card-body">
                <div class="fw-bold"><?= esc($patient['prenom'] . ' ' . $patient['nom']) ?></div>
                <div class="text-muted small mb-2"><?= esc($patient['numero_dossier']) ?></div>
                <a href="<?= $base ?>/patients/view/<?= $patient['id'] ?>" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-folder2-open me-1"></i>Voir dossier complet
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
