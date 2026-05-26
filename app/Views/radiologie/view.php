<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/radiologie" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><i class="bi bi-x-ray me-2" style="color:#6f42c1"></i><?= esc($title) ?></h1>
    <a href="/admin/radiologie/<?= $examen['id'] ?>/edit" class="btn btn-primary ms-auto">
        <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
    </a>
</div>

<?php
$colors = ['en_attente'=>'warning','programme'=>'info','realise'=>'success','annule'=>'secondary'];
$sc = $colors[$examen['statut']] ?? 'secondary';
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-clipboard2-pulse me-2" style="color:#6f42c1"></i><?= lang('Radiologie.details') ?? 'Détails de l\'examen' ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.number') ?></dt>
                    <dd class="col-sm-8"><code class="fs-6"><?= esc($examen['numero'] ?? '—') ?></code></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.exam_type') ?></dt>
                    <dd class="col-sm-8 fw-semibold"><?= esc($examen['type_examen']) ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.region') ?></dt>
                    <dd class="col-sm-8"><?= esc($examen['region'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.patient') ?></dt>
                    <dd class="col-sm-8"><?= esc($patientNom ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.doctor') ?? 'Médecin prescripteur' ?></dt>
                    <dd class="col-sm-8"><?= esc($medecinNom ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.request_date') ?></dt>
                    <dd class="col-sm-8"><?= date('d/m/Y', strtotime($examen['date_demande'])) ?></dd>

                    <?php if ($examen['date_examen']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.exam_date') ?? 'Date examen' ?></dt>
                    <dd class="col-sm-8"><?= date('d/m/Y', strtotime($examen['date_examen'])) ?></dd>
                    <?php endif; ?>

                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.status') ?></dt>
                    <dd class="col-sm-8"><span class="badge bg-<?= $sc ?>"><?= lang('Radiologie.statuses.' . $examen['statut']) ?></span></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Common.urgent') ?></dt>
                    <dd class="col-sm-8"><?= $examen['urgence'] ? '<span class="badge bg-danger">' . lang('Common.urgent') . '</span>' : '<span class="text-muted">Non</span>' ?></dd>

                    <?php if ($examen['description']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('Radiologie.description') ?? 'Description' ?></dt>
                    <dd class="col-sm-8"><?= nl2br(esc($examen['description'])) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <?php if ($examen['compte_rendu']): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-file-earmark-medical me-2 text-success"></i><?= lang('Radiologie.report') ?? 'Compte-rendu' ?>
            </div>
            <div class="card-body">
                <div class="bg-light rounded p-3" style="white-space:pre-wrap;font-size:.9rem"><?= esc($examen['compte_rendu']) ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-activity me-2" style="color:#6f42c1"></i><?= lang('Common.status') ?>
            </div>
            <div class="card-body text-center py-4">
                <span class="badge bg-<?= $sc ?> fs-6 px-4 py-2"><?= lang('Radiologie.statuses.' . $examen['statut']) ?></span>
                <?php if ($examen['urgence']): ?>
                <div class="mt-3"><span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= lang('Common.urgent') ?></span></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="/admin/radiologie/<?= $examen['id'] ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
            </a>
            <a href="/admin/radiologie" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?? 'Retour à la liste' ?>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
