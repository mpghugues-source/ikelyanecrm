<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.budgets') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-wallet2 text-info me-2"></i><?= lang('Erp.budgets') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item active"><?= lang('Erp.budgets') ?></li></ol></nav>
    </div>
    <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addBudgetModal"><i class="bi bi-plus-lg me-1"></i><?= lang('Erp.new_budget') ?></button>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th><?= lang('Erp.budget') ?></th><th><?= lang('Erp.fiscal_year') ?></th><th><?= lang('Erp.period') ?></th><th><?= lang('Erp.accounts') ?></th><th><?= lang('Erp.planned_amount') ?></th><th><?= lang('Erp.actual_amount') ?></th><th><?= lang('Erp.variance') ?></th><th width="80"></th></tr>
                </thead>
                <tbody>
                    <?php if (empty($budgets)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted"><?= lang('Erp.no_records') ?></td></tr>
                    <?php else: foreach ($budgets as $b):
                        $variance = $b['montant_prevu'] - $b['montant_realise'];
                        $pct = $b['montant_prevu'] > 0 ? round($b['montant_realise']/$b['montant_prevu']*100) : 0;
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= esc($b['nom']) ?></td>
                        <td><?= $b['exercice'] ?></td>
                        <td><span class="badge bg-secondary"><?= lang('Erp.period_'.str_replace(' ','_',$b['periode'])) ?></span></td>
                        <td class="text-muted small"><?= esc($b['account_nom'] ?? '—') ?></td>
                        <td><?= number_format($b['montant_prevu'],0,'.',',') ?> DZD</td>
                        <td>
                            <?= number_format($b['montant_realise'],0,'.',',') ?> DZD
                            <div class="progress mt-1" style="height:4px"><div class="progress-bar bg-<?= $pct > 90 ? 'danger' : ($pct > 70 ? 'warning' : 'success') ?>" style="width:<?= min(100,$pct) ?>%"></div></div>
                        </td>
                        <td class="fw-bold text-<?= $variance >= 0 ? 'success' : 'danger' ?>"><?= ($variance >= 0 ? '+' : '')  . number_format($variance,0,'.',',') ?></td>
                        <td><a href="/erp/finance/budgets/<?= $b['id'] ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= lang('Erp.confirm_delete') ?>')"><i class="bi bi-trash"></i></a></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><?= lang('Erp.new_budget') ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="/erp/finance/budgets/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label><input type="text" name="nom" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.fiscal_year') ?></label><input type="number" name="exercice" class="form-control" value="<?= date('Y') ?>" min="2020" max="2035"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.period') ?></label>
                            <select name="periode" class="form-select">
                                <?php foreach (['annual','q1','q2','q3','q4','monthly'] as $p): ?>
                                <option value="<?= $p ?>"><?= lang('Erp.period_'.$p) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.accounts') ?></label>
                            <select name="account_id" class="form-select">
                                <option value="">—</option>
                                <?php foreach ($accounts as $acc): ?><option value="<?= $acc['id'] ?>"><?= esc($acc['code'].' — '.$acc['nom']) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12"><label class="form-label fw-semibold"><?= lang('Erp.planned_amount') ?> (DZD) <span class="text-danger">*</span></label><input type="number" name="montant_prevu" class="form-control" step="0.01" min="0" required></div>
                        <div class="col-12"><label class="form-label fw-semibold"><?= lang('Erp.notes') ?></label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= lang('Erp.cancel') ?></button>
                    <button type="submit" class="btn btn-info text-white"><?= lang('Erp.save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
