<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Crm.leads') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-funnel-fill text-warning me-2"></i><?= lang('Crm.leads') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item active"><?= lang('Crm.leads') ?></li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="/crm/leads/kanban" class="btn btn-outline-secondary"><i class="bi bi-kanban me-1"></i><?= lang('Crm.kanban') ?></a>
        <a href="/crm/leads/create" class="btn btn-warning"><i class="bi bi-plus-lg me-1"></i><?= lang('Crm.new_lead') ?></a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Pipeline Summary -->
<div class="row g-2 mb-4">
    <?php
    $stages = ['new'=>['label'=>lang('Crm.stage_new'),'color'=>'secondary'],
               'qualified'=>['label'=>lang('Crm.stage_qualified'),'color'=>'info'],
               'proposition'=>['label'=>lang('Crm.stage_proposition'),'color'=>'primary'],
               'negotiation'=>['label'=>lang('Crm.stage_negotiation'),'color'=>'warning'],
               'won'=>['label'=>lang('Crm.stage_won'),'color'=>'success'],
               'lost'=>['label'=>lang('Crm.stage_lost'),'color'=>'danger']];
    foreach ($stages as $key => $s):
    ?>
    <div class="col">
        <div class="card border-0 shadow-sm text-center py-2 px-1">
            <div class="fw-bold fs-5"><?= $pipeline[$key]['count'] ?? 0 ?></div>
            <div class="small text-muted"><?= $s['label'] ?></div>
            <div class="small fw-semibold text-<?= $s['color'] ?>"><?= number_format(($pipeline[$key]['total_value'] ?? 0)/1000, 1) ?>k</div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Crm.lead') ?></th>
                        <th><?= lang('Crm.related_contact') ?></th>
                        <th><?= lang('Crm.est_value') ?></th>
                        <th><?= lang('Crm.probability') ?></th>
                        <th><?= lang('Crm.close_date') ?></th>
                        <th><?= lang('Crm.status') ?></th>
                        <th width="80"><?= lang('Crm.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leads)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('Crm.no_records') ?></td></tr>
                    <?php else: foreach ($leads as $l):
                        $statusColors = ['new'=>'secondary','qualified'=>'info','proposition'=>'primary','negotiation'=>'warning','won'=>'success','lost'=>'danger'];
                        $sc = $statusColors[$l['statut']] ?? 'secondary';
                    ?>
                    <tr>
                        <td><a href="/crm/leads/<?= $l['id'] ?>/edit" class="fw-semibold text-decoration-none"><?= esc($l['titre']) ?></a></td>
                        <td><?= esc($l['contact_name'] ?? '—') ?></td>
                        <td class="fw-semibold text-success"><?= number_format($l['valeur_estimee'],2) ?> <?= $l['devise'] ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:5px"><div class="progress-bar bg-<?= $sc ?>" style="width:<?= $l['probabilite'] ?>%"></div></div>
                                <small class="text-nowrap"><?= $l['probabilite'] ?>%</small>
                            </div>
                        </td>
                        <td><?= $l['date_cloture_prevue'] ? date('d/m/Y', strtotime($l['date_cloture_prevue'])) : '—' ?></td>
                        <td><span class="badge bg-<?= $sc ?>"><?= lang('Crm.stage_'.$l['statut']) ?></span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/crm/leads/<?= $l['id'] ?>/edit" class="btn btn-outline-primary" title="<?= lang('Crm.edit') ?>"><i class="bi bi-pencil"></i></a>
                                <a href="/crm/leads/<?= $l['id'] ?>/delete" class="btn btn-outline-danger" onclick="return confirm('<?= lang('Crm.confirm_delete') ?>')"><i class="bi bi-trash"></i></a>
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
