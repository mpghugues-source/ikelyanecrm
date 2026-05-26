<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.projects') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-kanban-fill text-primary me-2"></i><?= lang('Erp.projects') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item active"><?= lang('Erp.projects') ?></li></ol></nav>
    </div>
    <a href="/erp/projects/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i><?= lang('Erp.new_project') ?></a>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="row g-4">
    <?php if (empty($projects)): ?>
    <div class="col-12"><div class="card border-0 shadow-sm"><div class="card-body text-center py-5 text-muted"><i class="bi bi-kanban fs-1 d-block mb-3"></i><?= lang('Erp.no_records') ?></div></div></div>
    <?php else:
    $stColors = ['planning'=>'secondary','active'=>'success','on_hold'=>'warning','completed'=>'primary','cancelled'=>'danger'];
    $priColors = ['low'=>'secondary','medium'=>'info','high'=>'warning','critical'=>'danger'];
    foreach ($projects as $p): $sc = $stColors[$p['statut']] ?? 'secondary'; $pc = $priColors[$p['priorite']] ?? 'secondary'; ?>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-start">
                <div>
                    <a href="/erp/projects/<?= $p['id'] ?>/view" class="fw-bold text-decoration-none"><?= esc($p['nom']) ?></a>
                    <div class="mt-1">
                        <span class="badge bg-<?= $sc ?>"><?= lang('Erp.status_'.$p['statut']) ?></span>
                        <span class="badge bg-<?= $pc ?>-subtle text-<?= $pc ?>"><?= lang('Erp.priority_'.$p['priorite']) ?></span>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/erp/projects/<?= $p['id'] ?>/view"><i class="bi bi-eye me-2"></i><?= lang('Erp.view') ?></a></li>
                        <li><a class="dropdown-item" href="/erp/projects/<?= $p['id'] ?>/edit"><i class="bi bi-pencil me-2"></i><?= lang('Erp.edit') ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/erp/projects/<?= $p['id'] ?>/cancel" onclick="return confirm('<?= lang('Erp.confirm_delete') ?>')"><i class="bi bi-x-circle me-2"></i>Annuler</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body py-2">
                <?php if ($p['description']): ?><p class="text-muted small mb-3"><?= esc(substr($p['description'],0,100)) ?><?= strlen($p['description'])>100?'...':'' ?></p><?php endif; ?>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span><?= lang('Erp.progress') ?></span><span><?= $p['avancement'] ?>%</span>
                </div>
                <div class="progress mb-3" style="height:8px"><div class="progress-bar bg-<?= $sc ?>" style="width:<?= $p['avancement'] ?>%;border-radius:4px"></div></div>
                <div class="d-flex justify-content-between small text-muted">
                    <?php if ($p['chef_nom']): ?><span><i class="bi bi-person me-1"></i><?= esc($p['chef_nom']) ?></span><?php endif; ?>
                    <?php if ($p['date_fin']): ?><span><i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($p['date_fin'])) ?></span><?php endif; ?>
                </div>
                <?php if ($p['budget'] > 0): ?>
                <div class="mt-2 small">
                    <span class="text-muted"><?= lang('Erp.budget') ?> : </span><span class="fw-semibold"><?= number_format($p['budget'],0,'.',',') ?> DZD</span>
                    <?php if ($p['cout_reel'] > 0): ?><span class="text-muted ms-2"><?= lang('Erp.actual_cost') ?> : </span><span class="fw-semibold text-<?= $p['cout_reel'] > $p['budget'] ? 'danger' : 'success' ?>"><?= number_format($p['cout_reel'],0,'.',',') ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; endif; ?>
</div>
<?php $this->endSection(); ?>
