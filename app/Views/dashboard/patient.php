<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php if (! $patient): ?>
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <?= lang('Dashboard.no_patient_file') ?>
</div>
<?php else: ?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Dashboard.my_appointments') ?></div>
            <div class="stat-number"><?= count($rdvs) ?></div>
            <small class="text-muted"><?= lang('Dashboard.in_total') ?></small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Nav.prescriptions') ?></div>
            <div class="stat-number"><?= count($ordonnances) ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Nav.invoices') ?></div>
            <div class="stat-number"><?= count($factures) ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Patients.blood_type') ?></div>
            <div class="stat-number text-danger"><?= $patient['groupe_sanguin'] ?: '—' ?></div>
        </div>
    </div>
</div>

<!-- Prochain RDV -->
<?php $prochainRdv = array_filter($rdvs, fn($r) => $r['date_rdv'] >= date('Y-m-d') && $r['statut'] !== 'annule'); ?>
<?php $prochainRdv = reset($prochainRdv); ?>
<?php if ($prochainRdv): ?>
<div class="alert alert-info d-flex align-items-center mb-4">
    <i class="bi bi-calendar-event-fill fs-4 me-3 text-primary"></i>
    <div>
        <strong><?= lang('Dashboard.next_appointment') ?>:</strong>
        <?= date('d/m/Y', strtotime($prochainRdv['date_rdv'])) ?> <?= lang('Common.at') ?> <?= substr($prochainRdv['heure_rdv'],0,5) ?>
        <?= lang('Common.with') ?> Dr. <?= esc($prochainRdv['medecin_prenom'] . ' ' . $prochainRdv['medecin_nom']) ?>
        <?php if ($prochainRdv['motif']): ?> — <?= esc($prochainRdv['motif']) ?><?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="card-title"><i class="bi bi-calendar3 me-2 text-primary"></i><?= lang('Dashboard.my_appointments') ?></h6>
                <a href="/patient/appointments/book" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i><?= lang('Dashboard.book_appointment') ?></a>
            </div>
            <div class="card-body p-0">
                <?php $recentRdvs = array_slice($rdvs, 0, 5); ?>
                <?php if (empty($recentRdvs)): ?>
                <div class="text-center py-4 text-muted small"><i class="bi bi-calendar-x d-block fs-3 mb-1 opacity-25"></i><?= lang('Dashboard.no_appointments') ?></div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th><?= lang('Common.date') ?></th><th><?= lang('Doctors.title') ?></th><th><?= lang('Common.status') ?></th></tr></thead>
                        <tbody>
                            <?php foreach ($recentRdvs as $r): ?>
                            <tr>
                                <td><?= date('d/m/Y',strtotime($r['date_rdv'])) ?> <?= substr($r['heure_rdv'],0,5) ?></td>
                                <td>Dr. <?= esc($r['medecin_prenom'] . ' ' . $r['medecin_nom']) ?></td>
                                <td><span class="badge-statut statut-<?= $r['statut'] ?>"><?= ucfirst(str_replace('_',' ',$r['statut'])) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-person-fill me-2 text-primary"></i><?= lang('Dashboard.my_file') ?></h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" style="width:40%"><?= lang('Common.full_name') ?></td><td class="fw-semibold"><?= esc($patient['prenom'].' '.$patient['nom']) ?></td></tr>
                    <tr><td class="text-muted"><?= lang('Common.file_number') ?></td><td><?= esc($patient['numero_dossier']) ?></td></tr>
                    <tr><td class="text-muted"><?= lang('Patients.birthdate') ?></td><td><?= $patient['date_naissance'] ? date('d/m/Y',strtotime($patient['date_naissance'])) : '—' ?></td></tr>
                    <tr><td class="text-muted"><?= lang('Common.phone') ?></td><td><?= esc($patient['telephone'] ?? '—') ?></td></tr>
                    <tr><td class="text-muted"><?= lang('Common.allergies') ?></td><td class="text-danger small"><?= esc($patient['allergies'] ?: lang('Common.no_allergies')) ?></td></tr>
                </table>
                <a href="/patient/records" class="btn btn-outline-primary btn-sm mt-3 w-100">
                    <i class="bi bi-file-earmark-medical me-1"></i><?= lang('Dashboard.view_full_record') ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
