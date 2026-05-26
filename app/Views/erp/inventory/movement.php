<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.movement') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><?= lang('Erp.movement') ?></h1>
        <small class="text-muted"><?= esc($item['code']) ?> — <?= esc($item['nom']) ?></small>
    </div>
    <a href="/erp/inventory" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?></a>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="fs-1 fw-bold <?= $item['quantite_stock'] <= $item['quantite_min'] ? 'text-danger' : 'text-success' ?>"><?= $item['quantite_stock'] ?></div>
                <div class="text-muted"><?= lang('Erp.stock_qty') ?> (<?= esc($item['unite']) ?>)</div>
                <hr>
                <div class="d-flex justify-content-around">
                    <div><div class="fw-semibold"><?= $item['quantite_min'] ?></div><small class="text-muted"><?= lang('Erp.min_qty') ?></small></div>
                    <div><div class="fw-semibold"><?= number_format($item['prix_achat'],2) ?> DZD</div><small class="text-muted"><?= lang('Erp.purchase_price') ?></small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-semibold"><?= lang('Erp.new_task') ?> — <?= lang('Erp.movement') ?></h6></div>
            <div class="card-body">
                <form action="/erp/inventory/<?= $item['id'] ?>/movement/store" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><?= lang('Common.type') ?> <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="in"><?= lang('Erp.movement_type_in') ?></option>
                                <option value="out"><?= lang('Erp.movement_type_out') ?></option>
                                <option value="adjustment"><?= lang('Erp.movement_type_adj') ?></option>
                                <option value="return"><?= lang('Erp.movement_type_ret') ?></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><?= lang('Erp.stock_qty') ?> <span class="text-danger">*</span></label>
                            <input type="number" name="quantite" class="form-control" step="0.001" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><?= lang('Erp.purchase_price') ?> (DZD)</label>
                            <input type="number" name="prix_unit" class="form-control" step="0.01" value="<?= $item['prix_achat'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Erp.reference') ?></label>
                            <input type="text" name="reference" class="form-control" placeholder="BC-2024-001, PO-...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Erp.reason') ?></label>
                            <input type="text" name="motif" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold"><?= lang('Erp.notes') ?></label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i><?= lang('Erp.save') ?></button>
                        <a href="/erp/inventory" class="btn btn-outline-secondary"><?= lang('Erp.cancel') ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
