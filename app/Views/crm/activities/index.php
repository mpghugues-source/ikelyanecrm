<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Crm.activities') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-activity text-primary me-2"></i><?= lang('Crm.activities') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item active"><?= lang('Crm.activities') ?></li></ol></nav>
    </div>
    <a href="/crm/activities/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i><?= lang('Crm.new_activity') ?></a>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th><?= lang('Crm.subject') ?></th>
                        <th><?= lang('Crm.related_contact') ?></th>
                        <th><?= lang('Crm.start_date') ?></th>
                        <th><?= lang('Crm.priority') ?></th>
                        <th><?= lang('Crm.status') ?></th>
                        <th width="120"><?= lang('Crm.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activities)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('Crm.no_records') ?></td></tr>
                    <?php else:
                    $typeIcons = ['call'=>['icon'=>'bi-telephone','color'=>'info'],'email'=>['icon'=>'bi-envelope','color'=>'primary'],'meeting'=>['icon'=>'bi-people','color'=>'success'],'task'=>['icon'=>'bi-check2-square','color'=>'warning'],'note'=>['icon'=>'bi-sticky','color'=>'secondary']];
                    $priColors = ['high'=>'danger','medium'=>'warning','low'=>'secondary'];
                    $stColors  = ['planned'=>'primary','completed'=>'success','cancelled'=>'secondary'];
                    foreach ($activities as $a):
                        $ti = $typeIcons[$a['type']] ?? ['icon'=>'bi-circle','color'=>'secondary'];
                    ?>
                    <tr>
                        <td><span class="badge bg-<?= $ti['color'] ?>-subtle text-<?= $ti['color'] ?>"><i class="bi <?= $ti['icon'] ?> me-1"></i><?= lang('Crm.type_'.$a['type']) ?></span></td>
                        <td class="fw-semibold"><?= esc($a['sujet']) ?></td>
                        <td><?= esc($a['contact_name'] ?? ($a['lead_titre'] ?? '—')) ?></td>
                        <td><?= $a['date_debut'] ? date('d/m/Y H:i', strtotime($a['date_debut'])) : '—' ?></td>
                        <td><span class="badge bg-<?= $priColors[$a['priorite']] ?>"><?= lang('Crm.priority_'.$a['priorite']) ?></span></td>
                        <td><span class="badge bg-<?= $stColors[$a['statut']] ?>"><?= lang('Crm.status_'.$a['statut']) ?></span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <?php if ($a['statut'] === 'planned'): ?>
                                <a href="/crm/activities/<?= $a['id'] ?>/complete" class="btn btn-outline-success" title="Terminer"><i class="bi bi-check-lg"></i></a>
                                <?php endif; ?>
                                <a href="/crm/activities/<?= $a['id'] ?>/edit" class="btn btn-outline-primary" title="<?= lang('Crm.edit') ?>"><i class="bi bi-pencil"></i></a>
                                <a href="/crm/activities/<?= $a['id'] ?>/delete" class="btn btn-outline-danger" onclick="return confirm('<?= lang('Crm.confirm_delete') ?>')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
