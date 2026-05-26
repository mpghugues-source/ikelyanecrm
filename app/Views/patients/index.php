<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = $base_url ?? '/admin'; ?>

<div class="page-header">
    <div>
        <h4><i class="bi bi-people text-primary me-2"></i><?= lang('Patients.title') ?></h4>
        <small class="text-muted"><?= count($patients) ?> <?= lang('Patients.found_count') ?>
            <?php if (isset($usage) && $usage['max'] > 0): $pct = min(100, round($usage['current'] / $usage['max'] * 100)); $color = $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : '#1a56db'); ?>
            &nbsp;·&nbsp; <?= $usage['current'] ?>/<?= $usage['max'] ?> patients
            <span class="ms-1" style="display:inline-block;width:80px;height:6px;background:#e2e8f0;border-radius:3px;vertical-align:middle">
                <span style="display:block;height:100%;background:<?= $color ?>;border-radius:3px;width:<?= $pct ?>%"></span>
            </span>
            <?php endif; ?>
        </small>
    </div>
    <?php if ($base === '/admin'): ?>
    <?php $atLimit = isset($usage) && $usage['max'] > 0 && $usage['current'] >= $usage['max']; ?>
    <?php if ($atLimit): ?>
    <a href="/abonnement/expire" class="btn btn-warning">
        <i class="bi bi-arrow-up-circle me-1"></i>Upgrader le plan
    </a>
    <?php else: ?>
    <a href="<?= $base ?>/patients/create" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i><?= lang('Patients.new_patient') ?>
    </a>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Search -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control" placeholder="<?= lang('Patients.search_placeholder') ?>"
                       value="<?= esc($search ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary px-3"><?= lang('Common.search') ?></button>
            <?php if (!empty($search)): ?>
            <a href="?" class="btn btn-outline-secondary px-3"><?= lang('Common.reset') ?></a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($patients)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
            <?= lang('Patients.no_patients') ?><?= !empty($search) ? ' pour "' . esc($search) . '"' : '' ?>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><?= lang('Common.file_number') ?></th>
                        <th><?= lang('Patients.title') ?></th>
                        <th><?= lang('Patients.birth_age') ?></th>
                        <th><?= lang('Common.phone') ?></th>
                        <th><?= lang('Patients.blood_type') ?></th>
                        <th><?= lang('Patients.registered') ?></th>
                        <th class="text-end"><?= lang('Common.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $p): ?>
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark"><?= esc($p['numero_dossier']) ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="patient-avatar" style="background:<?= $p['sexe']==='F' ? 'linear-gradient(135deg,#e91e63,#f06292)' : 'linear-gradient(135deg,#1565c0,#42a5f5)' ?>">
                                    <?= strtoupper(substr($p['prenom'],0,1).substr($p['nom'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= esc($p['prenom'] . ' ' . $p['nom']) ?></div>
                                    <small class="text-muted"><?= esc($p['email'] ?? '') ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($p['date_naissance']): ?>
                                <?= date('d/m/Y', strtotime($p['date_naissance'])) ?>
                                <small class="text-muted">(<?= (int)((time() - strtotime($p['date_naissance'])) / 31536000) ?> <?= lang('Patients.years') ?>)</small>
                            <?php else: ?>
                            <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($p['telephone'] ?? '—') ?></td>
                        <td>
                            <?php if ($p['groupe_sanguin']): ?>
                            <span class="badge bg-danger"><?= esc($p['groupe_sanguin']) ?></span>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= $base ?>/patients/view/<?= $p['id'] ?>" class="btn btn-outline-primary" title="<?= lang('Patients.view') ?>">
                                    <i class="bi bi-folder2-open"></i>
                                </a>
                                <?php if ($base === '/admin'): ?>
                                <a href="<?= $base ?>/patients/edit/<?= $p['id'] ?>" class="btn btn-outline-secondary" title="<?= lang('Common.edit') ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= $base ?>/prescriptions/create/<?= $p['id'] ?>" class="btn btn-outline-success" title="<?= lang('Patients.prescription_btn') ?>">
                                    <i class="bi bi-file-medical"></i>
                                </a>
                                <a href="<?= $base ?>/patients/delete/<?= $p['id'] ?>" class="btn btn-outline-danger"
                                   data-confirm="<?= lang('Patients.archive_confirm') ?>" title="<?= lang('Patients.archive') ?>">
                                    <i class="bi bi-archive"></i>
                                </a>
                                <?php endif; ?>
                            </div>
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
