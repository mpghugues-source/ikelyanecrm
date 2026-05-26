<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.accounts') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-journal-bookmark text-info me-2"></i><?= lang('Erp.accounts') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item"><a href="/erp/finance/transactions"><?= lang('Erp.finance') ?></a></li><li class="breadcrumb-item active"><?= lang('Erp.accounts') ?></li></ol></nav>
    </div>
    <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addAccModal"><i class="bi bi-plus-lg me-1"></i><?= lang('Erp.new_account') ?></button>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<!-- Balance Summary -->
<div class="row g-3 mb-4">
    <?php
    $typeConfig = ['asset'=>['label'=>lang('Erp.type_asset'),'color'=>'success'],'liability'=>['label'=>lang('Erp.type_liability'),'color'=>'danger'],'equity'=>['label'=>lang('Erp.type_equity'),'color'=>'primary'],'revenue'=>['label'=>lang('Erp.type_revenue'),'color'=>'info'],'expense'=>['label'=>lang('Erp.type_expense_acc'),'color'=>'warning']];
    foreach ($typeConfig as $type => $cfg): $bal = $balances[$type] ?? 0; ?>
    <div class="col-md"><div class="card border-0 shadow-sm text-center py-3"><div class="fw-bold fs-5 text-<?= $cfg['color'] ?>"><?= number_format($bal,0,'.',',') ?></div><small class="text-muted"><?= $cfg['label'] ?> (DZD)</small></div></div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th><?= lang('Erp.account_code') ?></th><th><?= lang('Erp.account_name') ?></th><th><?= lang('Erp.account_type') ?></th><th><?= lang('Erp.balance') ?> (DZD)</th><th width="80"></th></tr>
                </thead>
                <tbody>
                    <?php if (empty($accounts)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted"><?= lang('Erp.no_records') ?></td></tr>
                    <?php else: foreach ($accounts as $acc):
                        $tc = $typeConfig[$acc['type']]['color'] ?? 'secondary';
                    ?>
                    <tr>
                        <td><code><?= esc($acc['code']) ?></code></td>
                        <td class="fw-semibold"><?= esc($acc['nom']) ?><?php if ($acc['description']): ?><br><small class="text-muted"><?= esc($acc['description']) ?></small><?php endif; ?></td>
                        <td><span class="badge bg-<?= $tc ?>-subtle text-<?= $tc ?>"><?= $typeConfig[$acc['type']]['label'] ?? $acc['type'] ?></span></td>
                        <td class="fw-bold text-<?= $acc['solde'] >= 0 ? 'success' : 'danger' ?>"><?= number_format($acc['solde'],2,',',' ') ?></td>
                        <td><a href="/erp/finance/accounts/<?= $acc['id'] ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= lang('Erp.confirm_delete') ?>')"><i class="bi bi-archive"></i></a></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Account Modal -->
<div class="modal fade" id="addAccModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><?= lang('Erp.new_account') ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="/erp/finance/accounts/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.account_code') ?> <span class="text-danger">*</span></label><input type="text" name="code" class="form-control" required placeholder="601"></div>
                        <div class="col-md-8"><label class="form-label fw-semibold"><?= lang('Erp.account_name') ?> <span class="text-danger">*</span></label><input type="text" name="nom" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.account_type') ?></label>
                            <select name="type" class="form-select">
                                <?php foreach ($typeConfig as $t => $cfg): ?><option value="<?= $t ?>"><?= $cfg['label'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.balance') ?> initial (DZD)</label><input type="number" name="solde" class="form-control" step="0.01" value="0"></div>
                        <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
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
