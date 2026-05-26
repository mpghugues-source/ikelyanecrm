<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = $base_url ?? '/admin'; ?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <div class="patient-avatar" style="width:52px;height:52px;font-size:1rem;background:<?= $patient['sexe']==='F'?'linear-gradient(135deg,#e91e63,#f06292)':'linear-gradient(135deg,#1565c0,#42a5f5)' ?>">
            <?= strtoupper(substr($patient['prenom'],0,1).substr($patient['nom'],0,1)) ?>
        </div>
        <div>
            <h4 class="mb-0"><?= esc($patient['prenom'] . ' ' . $patient['nom']) ?></h4>
            <span class="badge bg-secondary"><?= esc($patient['numero_dossier']) ?></span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <?php if ($base === '/admin'): ?>
        <a href="/admin/prescriptions/create/<?= $patient['id'] ?>" class="btn btn-success">
            <i class="bi bi-file-medical me-1"></i><?= lang('Patients.prescription_btn') ?>
        </a>
        <a href="/admin/invoices/create/<?= $patient['id'] ?>" class="btn btn-warning text-white">
            <i class="bi bi-receipt me-1"></i><?= lang('Patients.invoice_btn') ?>
        </a>
        <a href="/admin/patients/edit/<?= $patient['id'] ?>" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
        </a>
        <?php elseif ($base === '/medecin'): ?>
        <a href="/medecin/prescriptions/create/<?= $patient['id'] ?>" class="btn btn-success">
            <i class="bi bi-file-medical me-1"></i><?= lang('Patients.prescription_btn') ?>
        </a>
        <?php endif; ?>
        <a href="<?= $base ?>/patients" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?>
        </a>
    </div>
</div>

<!-- Infos rapides -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Patients.age') ?></div>
            <div class="stat-number" style="font-size:1.5rem">
                <?= $patient['date_naissance'] ? (int)((time()-strtotime($patient['date_naissance']))/31536000) . ' ' . lang('Patients.years') : '—' ?>
            </div>
            <small class="text-muted"><?= $patient['date_naissance'] ? date('d/m/Y', strtotime($patient['date_naissance'])) : '' ?></small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Patients.blood_type') ?></div>
            <div class="stat-number text-danger" style="font-size:1.5rem"><?= $patient['groupe_sanguin'] ?: '—' ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Patients.phone') ?></div>
            <div class="fw-semibold mt-1"><?= esc($patient['telephone'] ?? '—') ?></div>
            <small class="text-muted"><?= esc($patient['email'] ?? '') ?></small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label"><?= lang('Patients.insurance') ?></div>
            <div class="fw-semibold mt-1"><?= esc($patient['assurance'] ?? '—') ?></div>
            <small class="text-muted"><?= esc($patient['numero_secu'] ?? '') ?></small>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="patientTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#rdvTab"><i class="bi bi-calendar3 me-1"></i><?= lang('Patients.tab_appointments') ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#dossierTab"><i class="bi bi-file-earmark-text me-1"></i><?= lang('Patients.tab_medical') ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ordoTab"><i class="bi bi-file-medical me-1"></i><?= lang('Patients.tab_prescriptions') ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#factTab"><i class="bi bi-receipt me-1"></i><?= lang('Patients.tab_invoices') ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#infoTab"><i class="bi bi-info-circle me-1"></i><?= lang('Patients.tab_info') ?></a></li>
        </ul>
    </div>
    <div class="card-body tab-content">

        <!-- Rendez-vous -->
        <div class="tab-pane fade show active" id="rdvTab">
            <?php if (empty($rdvs)): ?>
            <div class="text-center py-4 text-muted"><i class="bi bi-calendar-x d-block fs-2 mb-2 opacity-25"></i><?= lang('Patients.no_appointments') ?></div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th><?= lang('Common.date') ?></th><th><?= lang('Dashboard.time') ?></th><th><?= lang('Doctors.title') ?></th><th><?= lang('Dashboard.reason') ?></th><th><?= lang('Common.status') ?></th></tr></thead>
                    <tbody>
                        <?php foreach ($rdvs as $r): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($r['date_rdv'])) ?></td>
                            <td><?= substr($r['heure_rdv'],0,5) ?></td>
                            <td>Dr. <?= esc(($r['medecin_prenom'] ?? '') . ' ' . ($r['medecin_nom'] ?? '')) ?></td>
                            <td><?= esc($r['motif'] ?? '—') ?></td>
                            <td><span class="badge-statut statut-<?= $r['statut'] ?>"><?= ucfirst(str_replace('_',' ',$r['statut'])) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- Dossier médical -->
        <div class="tab-pane fade" id="dossierTab">
            <div class="d-flex justify-content-end mb-3">
                <a href="<?= $base ?>/medical-records/create/<?= $patient['id'] ?>" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i><?= lang('Patients.new_consultation') ?>
                </a>
            </div>
            <?php if (empty($dossiers)): ?>
            <div class="text-center py-4 text-muted"><i class="bi bi-file-earmark-x d-block fs-2 mb-2 opacity-25"></i><?= lang('Patients.no_consultations') ?></div>
            <?php else: ?>
            <?php foreach ($dossiers as $d): ?>
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong><?= date('d/m/Y', strtotime($d['date_consultation'])) ?></strong>
                        <?php if (!empty($d['motif'])): ?><span class="text-muted ms-2">— <?= esc($d['motif']) ?></span><?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <?php if (!empty($d['medecin_nom'])): ?>
                        <span class="text-muted small">Dr. <?= esc($d['medecin_nom']) ?></span>
                        <?php endif; ?>
                        <a href="<?= $base ?>/medical-records/view/<?= $d['id'] ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
                <?php if (!empty($d['diagnostic'])): ?><p class="mb-1"><strong><?= lang('Patients.diagnostic') ?> :</strong> <?= esc($d['diagnostic']) ?></p><?php endif; ?>
                <?php if (!empty($d['traitement'])): ?><p class="mb-1"><strong><?= lang('Patients.treatment') ?> :</strong> <?= esc($d['traitement']) ?></p><?php endif; ?>
                <?php if (!empty($d['observations'])): ?><p class="mb-0 text-muted small"><i class="bi bi-sticky me-1"></i><?= esc($d['observations']) ?></p><?php endif; ?>
                <?php $vitals = array_filter([
                    $d['poids'] ? '⚖ ' . $d['poids'] . ' kg' : null,
                    $d['tension_arterielle'] ? '♥ ' . $d['tension_arterielle'] : null,
                    $d['temperature'] ? '🌡 ' . $d['temperature'] . '°C' : null,
                    $d['pouls'] ? $d['pouls'] . ' bpm' : null,
                ]); ?>
                <?php if ($vitals): ?>
                <div class="mt-2 text-muted small"><?= implode(' &nbsp;·&nbsp; ', $vitals) ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Ordonnances -->
        <div class="tab-pane fade" id="ordoTab">
            <?php $ordoPatient = $ordonnances ?? []; ?>
            <?php if (empty($ordoPatient)): ?>
            <div class="text-center py-4 text-muted"><i class="bi bi-file-medical d-block fs-2 mb-2 opacity-25"></i><?= lang('Patients.no_prescriptions') ?></div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th><?= lang('Patients.col_number') ?></th><th><?= lang('Common.date') ?></th><th><?= lang('Doctors.title') ?></th><th><?= lang('Common.status') ?></th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($ordoPatient as $o): ?>
                        <tr>
                            <td><?= esc($o['numero']) ?></td>
                            <td><?= date('d/m/Y', strtotime($o['date_ordonnance'])) ?></td>
                            <td>Dr. <?= esc($o['medecin_nom']) ?></td>
                            <td><span class="badge-statut statut-<?= $o['statut'] ?>"><?= ucfirst($o['statut']) ?></span></td>
                            <td><a href="<?= $base ?>/prescriptions/print/<?= $o['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-printer"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- Factures -->
        <div class="tab-pane fade" id="factTab">
            <?php $factPatient = $factures ?? []; ?>
            <?php if (empty($factPatient)): ?>
            <div class="text-center py-4 text-muted"><i class="bi bi-receipt d-block fs-2 mb-2 opacity-25"></i><?= lang('Patients.no_invoices') ?></div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th><?= lang('Patients.col_number') ?></th><th><?= lang('Common.date') ?></th><th><?= lang('Common.total') ?></th><th><?= lang('Common.paid') ?></th><th><?= lang('Common.status') ?></th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($factPatient as $f): ?>
                        <tr>
                            <td><?= esc($f['numero']) ?></td>
                            <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
                            <td><?= number_format($f['total'],2) ?> DA</td>
                            <td><?= number_format($f['montant_paye'],2) ?> DA</td>
                            <td><span class="badge-statut statut-<?= $f['statut'] ?>"><?= ucfirst(str_replace('_',' ',$f['statut'])) ?></span></td>
                            <td><a href="<?= $base ?>/invoices/view/<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- Infos personnelles -->
        <div class="tab-pane fade" id="infoTab">
            <div class="row g-3">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0 small">
                        <tr><td class="text-muted fw-semibold" style="width:40%"><?= lang('Patients.address') ?></td><td><?= esc($patient['adresse'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Patients.city') ?></td><td><?= esc($patient['ville'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Common.allergies') ?></td><td><?= esc($patient['allergies'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Patients.antecedents_short') ?></td><td><?= esc($patient['antecedents'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Patients.current_treatment') ?></td><td><?= esc($patient['traitement_en_cours'] ?? '—') ?></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless mb-0 small">
                        <tr><td class="text-muted fw-semibold" style="width:40%"><?= lang('Patients.emergency_contact') ?></td><td><?= esc($patient['contact_urgence_nom'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Patients.emergency_phone') ?></td><td><?= esc($patient['contact_urgence_tel'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Patients.insurance') ?></td><td><?= esc($patient['assurance'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Patients.secu_short') ?></td><td><?= esc($patient['numero_secu'] ?? '—') ?></td></tr>
                        <tr><td class="text-muted fw-semibold"><?= lang('Common.notes') ?></td><td><?= esc($patient['notes'] ?? '—') ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
// Activate tab from URL hash (e.g., #dossierTab)
const hash = window.location.hash;
if (hash) {
    const tabEl = document.querySelector('.nav-tabs [href="' + hash + '"]');
    if (tabEl) new bootstrap.Tab(tabEl).show();
}
</script>
<?= $this->endSection() ?>
