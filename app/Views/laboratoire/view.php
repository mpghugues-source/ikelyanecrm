<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/laboratoire" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><i class="bi bi-flask me-2 text-info"></i><?= esc($title) ?></h1>
    <a href="/admin/laboratoire/<?= $analyse['id'] ?>/edit" class="btn btn-primary ms-auto">
        <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
    </a>
</div>

<?php
$statutColors = ['en_attente'=>'warning','en_cours'=>'info','resultat_disponible'=>'success','annule'=>'secondary'];
$sc = $statutColors[$analyse['statut']] ?? 'secondary';
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-clipboard-pulse me-2 text-info"></i><?= lang('Laboratoire.details') ?? 'Détails de l\'analyse' ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.number') ?></dt>
                    <dd class="col-sm-8"><code class="fs-6"><?= esc($analyse['numero'] ?? '—') ?></code></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.type') ?></dt>
                    <dd class="col-sm-8 fw-semibold"><?= esc($analyse['type_analyse']) ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.patient') ?></dt>
                    <dd class="col-sm-8"><?= esc($patientNom ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.doctor') ?></dt>
                    <dd class="col-sm-8"><?= esc($medecinNom ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.request_date') ?></dt>
                    <dd class="col-sm-8"><?= date('d/m/Y', strtotime($analyse['date_demande'])) ?></dd>

                    <?php if ($analyse['date_resultat']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.result_date') ?? 'Date résultat' ?></dt>
                    <dd class="col-sm-8"><?= date('d/m/Y', strtotime($analyse['date_resultat'])) ?></dd>
                    <?php endif; ?>

                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.status') ?></dt>
                    <dd class="col-sm-8"><span class="badge bg-<?= $sc ?>"><?= lang('Laboratoire.statuses.' . $analyse['statut']) ?></span></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Common.urgent') ?></dt>
                    <dd class="col-sm-8"><?= $analyse['urgence'] ? '<span class="badge bg-danger">' . lang('Common.urgent') . '</span>' : '<span class="text-muted">Non</span>' ?></dd>

                    <?php if ($analyse['notes']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('Laboratoire.notes') ?? 'Notes' ?></dt>
                    <dd class="col-sm-8"><?= nl2br(esc($analyse['notes'])) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <?php if ($analyse['resultat']): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-file-earmark-text me-2 text-success"></i><?= lang('Laboratoire.result') ?? 'Résultat' ?>
            </div>
            <div class="card-body">
                <div class="bg-light rounded p-3" style="white-space:pre-wrap;font-size:.9rem"><?= esc($analyse['resultat']) ?></div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-activity me-2 text-info"></i><?= lang('Common.status') ?>
            </div>
            <div class="card-body text-center py-4">
                <span class="badge bg-<?= $sc ?> fs-6 px-4 py-2"><?= lang('Laboratoire.statuses.' . $analyse['statut']) ?></span>
                <?php if ($analyse['urgence']): ?>
                <div class="mt-3"><span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= lang('Common.urgent') ?></span></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="/admin/laboratoire/<?= $analyse['id'] ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
            </a>
            <a href="/admin/laboratoire" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?? 'Retour à la liste' ?>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
