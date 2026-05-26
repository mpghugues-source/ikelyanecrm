<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.transactions') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-receipt text-info me-2"></i><?= lang('Erp.transactions') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item active"><?= lang('Erp.finance') ?></li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="/erp/finance/accounts" class="btn btn-outline-secondary"><i class="bi bi-journal-bookmark me-1"></i><?= lang('Erp.accounts') ?></a>
        <a href="/erp/finance/budgets" class="btn btn-outline-secondary"><i class="bi bi-wallet2 me-1"></i><?= lang('Erp.budgets') ?></a>
        <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addTxnModal"><i class="bi bi-plus-lg me-1"></i><?= lang('Erp.new_transaction') ?></button>
    </div>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Erp.reference') ?></th>
                        <th><?= lang('Erp.date') ?></th>
                        <th><?= lang('Erp.description') ?></th>
                        <th>Type</th>
                        <th><?= lang('Erp.accounts') ?></th>
                        <th><?= lang('Erp.amount') ?></th>
                        <th><?= lang('Erp.status') ?></th>
                        <th width="80"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted"><?= lang('Erp.no_records') ?></td></tr>
                    <?php else: foreach ($transactions as $tx):
                        $typeColors = ['income'=>'success','expense'=>'danger','transfer'=>'info'];
                        $tc = $typeColors[$tx['type']] ?? 'secondary';
                        $stColors = ['draft'=>'secondary','validated'=>'success','cancelled'=>'danger'];
                    ?>
                    <tr>
                        <td><code class="small"><?= esc($tx['reference']) ?></code></td>
                        <td><?= date('d/m/Y', strtotime($tx['date_transaction'])) ?></td>
                        <td class="fw-semibold"><?= esc($tx['description']) ?></td>
                        <td><span class="badge bg-<?= $tc ?>"><?= lang('Erp.type_'.$tx['type']) ?></span></td>
                        <td class="text-muted small"><?= esc($tx['account_nom'] ?? '—') ?></td>
                        <td class="fw-bold text-<?= $tc ?>"><?= $tx['type']==='income' ? '+' : '-' ?><?= number_format($tx['montant'],2,',',' ') ?> DZD</td>
                        <td><span class="badge bg-<?= $stColors[$tx['statut']] ?>"><?= $tx['statut'] ?></span></td>
                        <td>
                            <?php if ($tx['statut'] !== 'cancelled'): ?>
                            <a href="/erp/finance/transactions/<?= $tx['id'] ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('<?= lang('Erp.confirm_delete') ?>')"><i class="bi bi-x-lg"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTxnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><?= lang('Erp.new_transaction') ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="/erp/finance/transactions/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold"><?= lang('Erp.description') ?> <span class="text-danger">*</span></label><input type="text" name="description" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="income"><?= lang('Erp.type_income') ?></option>
                                <option value="expense"><?= lang('Erp.type_expense') ?></option>
                                <option value="transfer"><?= lang('Erp.type_transfer') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.amount') ?> (DZD) <span class="text-danger">*</span></label><input type="number" name="montant" class="form-control" step="0.01" min="0" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.date') ?> <span class="text-danger">*</span></label><input type="date" name="date_transaction" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.accounts') ?></label>
                            <select name="account_id" class="form-select">
                                <option value="">—</option>
                                <?php foreach ($accounts as $acc): ?>
                                <option value="<?= $acc['id'] ?>"><?= esc($acc['code'].' — '.$acc['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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
