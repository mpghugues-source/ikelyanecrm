<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Crm.campaigns') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-megaphone-fill text-success me-2"></i><?= lang('Crm.campaigns') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item active"><?= lang('Crm.campaigns') ?></li></ol></nav>
    </div>
    <a href="/crm/campaigns/create" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i><?= lang('Crm.new_campaign') ?></a>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="fw-bold fs-4"><?= $stats['total'] ?></div><small class="text-muted">Total</small></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="fw-bold fs-4 text-success"><?= $stats['active'] ?></div><small class="text-muted"><?= lang('Crm.status_active') ?></small></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="fw-bold fs-4 text-secondary"><?= $stats['completed'] ?></div><small class="text-muted"><?= lang('Crm.status_completed') ?></small></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm text-center py-3"><div class="fw-bold fs-4 text-warning"><?= number_format($stats['budget'],0) ?></div><small class="text-muted"><?= lang('Crm.budget') ?> (DZD)</small></div></div>
</div>

<div class="row g-4">
    <?php if (empty($campaigns)): ?>
    <div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body text-center py-5 text-muted"><i class="bi bi-megaphone fs-1 d-block mb-3"></i><?= lang('Crm.no_records') ?></div></div></div>
    <?php else:
    $typeIcons = ['email'=>'bi-envelope','sms'=>'bi-phone','event'=>'bi-calendar-event','social'=>'bi-share','other'=>'bi-three-dots'];
    $stColors  = ['draft'=>'secondary','active'=>'success','completed'=>'primary','cancelled'=>'danger'];
    foreach ($campaigns as $camp): $si = $typeIcons[$camp['type']] ?? 'bi-megaphone'; $sc = $stColors[$camp['statut']] ?? 'secondary'; ?>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-<?= $sc ?>-subtle text-<?= $sc ?> d-flex align-items-center justify-content-center" style="width:36px;height:36px"><i class="bi <?= $si ?>"></i></div>
                    <div>
                        <div class="fw-semibold"><?= esc($camp['nom']) ?></div>
                        <small class="text-muted"><?= lang('Crm.type_'.($camp['type'] === 'email' ? 'email_camp' : $camp['type'])) ?></small>
                    </div>
                </div>
                <span class="badge bg-<?= $sc ?>"><?= lang('Crm.status_'.$camp['statut']) ?></span>
            </div>
            <div class="card-body py-2">
                <?php if ($camp['objectif']): ?><p class="text-muted small mb-2"><?= esc(substr($camp['objectif'],0,100)) ?><?= strlen($camp['objectif'])>100 ? '...' : '' ?></p><?php endif; ?>
                <div class="row g-2 text-center">
                    <div class="col-4"><div class="small fw-bold"><?= $camp['nb_cibles'] ?></div><div class="text-muted" style="font-size:.7rem"><?= lang('Crm.targets') ?></div></div>
                    <div class="col-4"><div class="small fw-bold text-success"><?= $camp['nb_reponses'] ?></div><div class="text-muted" style="font-size:.7rem"><?= lang('Crm.responses') ?></div></div>
                    <div class="col-4"><div class="small fw-bold text-warning"><?= $camp['nb_cibles'] > 0 ? round($camp['nb_reponses']/$camp['nb_cibles']*100,1) : 0 ?>%</div><div class="text-muted" style="font-size:.7rem">Taux</div></div>
                </div>
                <?php if ($camp['date_debut']): ?><div class="mt-2 text-muted small"><i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($camp['date_debut'])) ?> → <?= $camp['date_fin'] ? date('d/m/Y', strtotime($camp['date_fin'])) : '...' ?></div><?php endif; ?>
                <div class="mt-1 text-muted small"><i class="bi bi-wallet2 me-1"></i><?= lang('Crm.budget') ?> : <?= number_format($camp['budget'],0) ?> DZD</div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex gap-2">
                <a href="/crm/campaigns/<?= $camp['id'] ?>/edit" class="btn btn-sm btn-outline-primary flex-grow-1"><?= lang('Crm.edit') ?></a>
                <a href="/crm/campaigns/<?= $camp['id'] ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= lang('Crm.confirm_delete') ?>')"><i class="bi bi-trash"></i></a>
            </div>
        </div>
    </div>
    <?php endforeach; endif; ?>
</div>
<?php $this->endSection(); ?>
