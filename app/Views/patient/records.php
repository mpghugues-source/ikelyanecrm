<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-file-earmark-medical text-primary me-2"></i><?= lang('Patients.medical_record') ?></h4>
</div>

<?php if (! $patient): ?>
<div class="alert alert-warning"><?= lang('Patients.record_not_found') ?></div>
<?php else: ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold small text-uppercase text-muted mb-3"><?= lang('Patients.my_file') ?></h6>
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted"><?= lang('Patients.blood_type') ?></dt>
                    <dd class="col-7 text-danger fw-bold"><?= esc($patient['groupe_sanguin'] ?: '—') ?></dd>

                    <dt class="col-5 text-muted"><?= lang('Patients.allergies') ?></dt>
                    <dd class="col-7"><?= esc($patient['allergies'] ?: lang('Patients.none')) ?></dd>

                    <dt class="col-5 text-muted"><?= lang('Patients.antecedents_short') ?></dt>
                    <dd class="col-7"><?= esc($patient['antecedents'] ?: '—') ?></dd>

                    <dt class="col-5 text-muted"><?= lang('Patients.current_treatment') ?></dt>
                    <dd class="col-7"><?= esc($patient['traitement_en_cours'] ?: '—') ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-clock-history me-2 text-primary"></i><?= lang('Patients.consultation_history') ?></h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($dossiers)): ?>
                <div class="text-center py-4 text-muted small">
                    <i class="bi bi-file-earmark-x d-block fs-2 mb-1 opacity-25"></i>
                    <?= lang('Patients.no_consultations') ?>
                </div>
                <?php else: ?>
                <?php foreach ($dossiers as $d): ?>
                <div class="border-bottom p-3">
                    <div class="d-flex justify-content-between mb-1">
                        <strong><?= date('d/m/Y', strtotime($d['date_consultation'])) ?></strong>
                        <small class="text-muted">Dr. <?= esc($d['medecin_nom']) ?></small>
                    </div>
                    <?php if ($d['diagnostic']): ?>
                    <p class="mb-1 small"><strong><?= lang('Patients.diagnostic') ?>:</strong> <?= esc($d['diagnostic']) ?></p>
                    <?php endif; ?>
                    <?php if ($d['traitement']): ?>
                    <p class="mb-0 small text-muted"><?= esc($d['traitement']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
