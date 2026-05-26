<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Crm.cases') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-ticket-detailed-fill text-danger me-2"></i><?= lang('Crm.cases') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item active"><?= lang('Crm.cases') ?></li></ol></nav>
    </div>
    <a href="/crm/cases/create" class="btn btn-danger"><i class="bi bi-plus-lg me-1"></i><?= lang('Crm.new_case') ?></a>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Crm.case_number') ?></th>
                        <th>Sujet</th>
                        <th><?= lang('Crm.related_contact') ?></th>
                        <th><?= lang('Crm.priority') ?></th>
                        <th><?= lang('Crm.status') ?></th>
                        <th>Date</th>
                        <th width="100"><?= lang('Crm.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cases)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('Crm.no_records') ?></td></tr>
                    <?php else:
                    $priColors = ['low'=>'success','medium'=>'warning','high'=>'orange','urgent'=>'danger'];
                    $stColors  = ['open'=>'danger','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary'];
                    foreach ($cases as $c): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= esc($c['numero']) ?></span></td>
                        <td class="fw-semibold"><?= esc($c['sujet']) ?></td>
                        <td><?= esc($c['contact_name'] ?? '—') ?></td>
                        <td><span class="badge bg-<?= $priColors[$c['priorite']] ?? 'secondary' ?>"><?= lang('Crm.priority_'.$c['priorite']) ?></span></td>
                        <td><span class="badge bg-<?= $stColors[$c['statut']] ?? 'secondary' ?>"><?= lang('Crm.status_'.$c['statut']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/crm/cases/<?= $c['id'] ?>/edit" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <a href="/crm/cases/<?= $c['id'] ?>/delete" class="btn btn-outline-danger" onclick="return confirm('<?= lang('Crm.confirm_delete') ?>')"><i class="bi bi-trash"></i></a>
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
