<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-calendar-check text-primary me-2"></i><?= lang('Nav.my_appointments') ?></h4>
    <a href="/patient/appointments/book" class="btn btn-primary">
        <i class="bi bi-calendar-plus me-1"></i><?= lang('Nav.book_appointment') ?>
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($rdvs)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
            <?= lang('Appointments.no_results') ?><br>
            <a href="/patient/appointments/book" class="btn btn-primary mt-3">
                <?= lang('Nav.book_appointment') ?>
            </a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><?= lang('Appointments.date_time') ?></th>
                        <th><?= lang('Appointments.doctor') ?></th>
                        <th><?= lang('Appointments.reason') ?></th>
                        <th>Type</th>
                        <th><?= lang('Appointments.status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rdvs as $r): ?>
                    <tr>
                        <td>
                            <strong><?= date('d/m/Y', strtotime($r['date_rdv'])) ?></strong><br>
                            <small class="text-primary"><?= substr($r['heure_rdv'], 0, 5) ?></small>
                        </td>
                        <td>
                            Dr. <?= esc($r['medecin_prenom'] . ' ' . $r['medecin_nom']) ?><br>
                            <small class="text-muted"><?= esc($r['specialite_nom'] ?? '') ?></small>
                        </td>
                        <td><?= esc($r['motif'] ?? '—') ?></td>
                        <td><span class="badge bg-light text-dark"><?= esc($r['type_rdv']) ?></span></td>
                        <td>
                            <span class="badge-statut statut-<?= $r['statut'] ?>">
                                <?= lang('Appointments.statuses.' . $r['statut']) ?: ucfirst(str_replace('_', ' ', $r['statut'])) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
