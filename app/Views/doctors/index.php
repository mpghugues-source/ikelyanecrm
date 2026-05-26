<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h4><i class="bi bi-person-badge text-primary me-2"></i><?= lang('Doctors.title') ?></h4>
        <?php if (isset($usage)): ?>
        <small class="text-muted">
            <?= $usage['current'] ?> / <?= $usage['max'] === 0 ? '∞' : $usage['max'] ?> médecins
            <?php if ($usage['max'] > 0): $pct = min(100, round($usage['current'] / $usage['max'] * 100)); $color = $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : '#1a56db'); ?>
            <span class="ms-1" style="display:inline-block;width:80px;height:6px;background:#e2e8f0;border-radius:3px;vertical-align:middle">
                <span style="display:block;height:100%;background:<?= $color ?>;border-radius:3px;width:<?= $pct ?>%"></span>
            </span>
            <?php endif; ?>
        </small>
        <?php endif; ?>
    </div>
    <?php $atLimit = isset($usage) && $usage['max'] > 0 && $usage['current'] >= $usage['max']; ?>
    <?php if ($atLimit): ?>
    <a href="/abonnement/expire" class="btn btn-warning">
        <i class="bi bi-arrow-up-circle me-1"></i>Upgrader le plan
    </a>
    <?php else: ?>
    <a href="/admin/doctors/create" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i><?= lang('Doctors.new_doctor') ?>
    </a>
    <?php endif; ?>
</div>

<div class="row g-3">
    <?php if (empty($doctors)): ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-person-badge fs-1 d-block mb-2 opacity-25"></i>
                <?= lang('Doctors.no_doctors') ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($doctors as $d): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="patient-avatar" style="background:linear-gradient(135deg,#1565c0,#42a5f5);width:52px;height:52px;font-size:1rem;border-radius:12px">
                        <?= strtoupper(substr($d['user_prenom'],0,1).substr($d['user_nom'],0,1)) ?>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Dr. <?= esc($d['user_prenom'] . ' ' . $d['user_nom']) ?></h6>
                        <span class="badge bg-primary-subtle text-primary"><?= esc($d['specialite_nom'] ?? lang('Doctors.general_doctor')) ?></span>
                    </div>
                </div>
                <div class="small text-muted mb-2">
                    <i class="bi bi-telephone me-1"></i><?= esc($d['user_telephone'] ?? '—') ?><br>
                    <i class="bi bi-envelope me-1"></i><?= esc($d['user_email'] ?? '—') ?><br>
                    <i class="bi bi-clock me-1"></i><?= esc(substr($d['heure_debut'],0,5)) ?> — <?= esc(substr($d['heure_fin'],0,5)) ?>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-success fw-semibold"><?= number_format($d['tarif_consultation']) ?> DA</span>
                    <div class="btn-group btn-group-sm">
                        <a href="/admin/doctors/view/<?= $d['id'] ?>" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="/admin/doctors/edit/<?= $d['id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <a href="/admin/doctors/delete/<?= $d['id'] ?>" class="btn btn-outline-danger"
                           data-confirm="<?= lang('Doctors.deactivate_confirm') ?>"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
