<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <div class="patient-avatar" style="background:linear-gradient(135deg,#1565c0,#42a5f5);width:52px;height:52px;font-size:1rem;border-radius:12px">
            <?= strtoupper(substr($doctor['user_prenom'],0,1).substr($doctor['user_nom'],0,1)) ?>
        </div>
        <div>
            <h4 class="mb-0">Dr. <?= esc($doctor['user_prenom'] . ' ' . $doctor['user_nom']) ?></h4>
            <span class="badge bg-primary"><?= esc($doctor['specialite_nom'] ?? 'Médecin') ?></span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/doctors/edit/<?= $doctor['id'] ?>" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Modifier</a>
        <a href="/admin/doctors" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold small text-uppercase text-muted mb-3"><?= lang('Doctors.information') ?></h6>
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted"><?= lang('Doctors.email') ?></dt><dd class="col-7"><?= esc($doctor['user_email']) ?></dd>
                    <dt class="col-5 text-muted"><?= lang('Doctors.phone') ?></dt><dd class="col-7"><?= esc($doctor['user_telephone'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted"><?= lang('Doctors.order_number') ?></dt><dd class="col-7"><?= esc($doctor['numero_ordre'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted"><?= lang('Doctors.consultation_fee') ?></dt><dd class="col-7 fw-bold text-success"><?= number_format($doctor['tarif_consultation']) ?> DA</dd>
                    <dt class="col-5 text-muted"><?= lang('Doctors.duration') ?></dt><dd class="col-7"><?= $doctor['duree_consultation'] ?> min</dd>
                    <dt class="col-5 text-muted"><?= lang('Doctors.schedule') ?></dt><dd class="col-7"><?= substr($doctor['heure_debut'],0,5) ?> — <?= substr($doctor['heure_fin'],0,5) ?></dd>
                    <dt class="col-5 text-muted"><?= lang('Doctors.active') ?></dt>
                    <dd class="col-7">
                        <?php if ($doctor['actif']): ?>
                        <span class="badge bg-success-subtle text-success"><?= lang('Doctors.active') ?></span>
                        <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger"><?= lang('Doctors.inactive') ?></span>
                        <?php endif; ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <?php if ($doctor['biographie']): ?>
        <div class="card mb-3">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-file-text me-2 text-primary"></i><?= lang('Doctors.biography') ?></h6></div>
            <div class="card-body"><p class="mb-0"><?= esc($doctor['biographie']) ?></p></div>
        </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="card-title"><i class="bi bi-calendar3 me-2 text-primary"></i><?= lang('Doctors.schedule') ?></h6>
                <a href="/admin/appointments/calendar?medecin_id=<?= $doctor['id'] ?>" class="btn btn-sm btn-outline-primary"><?= lang('Doctors.view_calendar') ?></a>
            </div>
            <div class="card-body text-center text-muted py-4">
                <i class="bi bi-calendar3 d-block fs-2 mb-2 opacity-25"></i>
                <a href="/admin/appointments/calendar?medecin_id=<?= $doctor['id'] ?>"><?= lang('Doctors.view_calendar_full') ?></a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
