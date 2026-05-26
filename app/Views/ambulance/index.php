<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-truck me-2 text-danger"></i><?= esc($title) ?></h1>
    <div class="d-flex gap-2">
        <a href="/admin/export/ambulances/pdf" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
        </a>
        <a href="/admin/export/ambulances/csv" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
        </a>
        <a href="/admin/ambulances/create" class="btn btn-danger">
            <i class="bi bi-plus-lg me-1"></i><?= lang('Ambulance.add') ?>
        </a>
    </div>
</div>
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <?php
    $statColors = ['disponible'=>'success','en_mission'=>'warning','maintenance'=>'info','hors_service'=>'danger'];
    foreach ($stats as $s => $nb): ?>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="h2 fw-bold text-<?= $statColors[$s] ?>"><?= $nb ?></div>
                <div class="small text-muted"><?= lang('Ambulance.statuses.' . $s) ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
<?php if (empty($vehicules)): ?>
<div class="col-12"><div class="text-center py-5 text-muted"><?= lang('Ambulance.no_results') ?></div></div>
<?php else: foreach ($vehicules as $v): ?>
<div class="col-md-6 col-lg-4">
    <div class="card shadow-sm h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="mb-0 fw-bold"><?= esc($v['immatriculation']) ?></h5>
                <span class="badge bg-<?= $statColors[$v['statut']] ?? 'secondary' ?>"><?= lang('Ambulance.statuses.' . $v['statut']) ?></span>
            </div>
            <p class="text-muted small mb-1"><?= esc($v['type_vehicule']) ?> — <?= esc($v['modele'] ?? '') ?></p>
            <?php if ($v['chauffeur_nom']): ?>
            <p class="small mb-1"><i class="bi bi-person me-1"></i><?= esc($v['chauffeur_nom']) ?><?= $v['chauffeur_tel'] ? ' — ' . esc($v['chauffeur_tel']) : '' ?></p>
            <?php endif; ?>
            <?php if ($v['date_revision']): ?>
            <p class="small text-muted mb-0"><i class="bi bi-tools me-1"></i><?= lang('Ambulance.revision_date') ?> : <?= date('d/m/Y', strtotime($v['date_revision'])) ?></p>
            <?php endif; ?>
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            <a href="/admin/ambulances/<?= $v['id'] ?>/view" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
            <a href="/admin/ambulances/<?= $v['id'] ?>/edit" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?></a>
            <form method="POST" action="/admin/ambulances/<?= $v['id'] ?>/delete" onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
                <?= csrf_field() ?>
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    </div>
</div>
<?php endforeach; endif; ?>
</div>
<?= $this->endSection() ?>
