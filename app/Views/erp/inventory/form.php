<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $item ? lang('Erp.edit_item') : lang('Erp.new_item') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold"><?= $item ? lang('Erp.edit_item') : lang('Erp.new_item') ?></h1>
    <a href="/erp/inventory" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Erp.cancel') ?></a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $item ? '/erp/inventory/'.$item['id'].'/update' : '/erp/inventory/store' ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.item_code') ?> <span class="text-danger">*</span></label><input type="text" name="code" class="form-control" required value="<?= esc($item['code'] ?? '') ?>"></div>
                <div class="col-md-9"><label class="form-label fw-semibold"><?= lang('Erp.item_name') ?> <span class="text-danger">*</span></label><input type="text" name="nom" class="form-control" required value="<?= esc($item['nom'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.category') ?></label><input type="text" name="categorie" class="form-control" value="<?= esc($item['categorie'] ?? '') ?>" placeholder="Médicaments, Matériel..."></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.unit') ?></label><input type="text" name="unite" class="form-control" value="<?= esc($item['unite'] ?? 'pièce') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.location') ?></label><input type="text" name="emplacement" class="form-control" value="<?= esc($item['emplacement'] ?? '') ?>" placeholder="Armoire A, Dépôt B..."></div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.stock_qty') ?></label><input type="number" name="quantite_stock" class="form-control" step="0.001" value="<?= $item['quantite_stock'] ?? 0 ?>"></div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.min_qty') ?></label><input type="number" name="quantite_min" class="form-control" step="0.001" value="<?= $item['quantite_min'] ?? 0 ?>"></div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.purchase_price') ?> (DZD)</label><input type="number" name="prix_achat" class="form-control" step="0.01" value="<?= $item['prix_achat'] ?? 0 ?>"></div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.sale_price') ?> (DZD)</label><input type="number" name="prix_vente" class="form-control" step="0.01" value="<?= $item['prix_vente'] ?? 0 ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.suppliers') ?></label>
                    <select name="supplier_id" class="form-select">
                        <option value="">—</option>
                        <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($item['supplier_id']??'') == $s['id'] ? 'selected' : '' ?>><?= esc($s['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.expiry_date') ?></label><input type="date" name="date_expiration" class="form-control" value="<?= $item['date_expiration'] ?? '' ?>"></div>
                <?php if ($item): ?>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.status') ?></label>
                    <select name="statut" class="form-select">
                        <option value="active" <?= ($item['statut']??'active') === 'active' ? 'selected' : '' ?>><?= lang('Erp.active') ?></option>
                        <option value="inactive" <?= ($item['statut']??'') === 'inactive' ? 'selected' : '' ?>><?= lang('Erp.inactive') ?></option>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2"><?= esc($item['description'] ?? '') ?></textarea></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg me-1"></i><?= lang('Erp.save') ?></button>
                <a href="/erp/inventory" class="btn btn-outline-secondary"><?= lang('Erp.cancel') ?></a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
