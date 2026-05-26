<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $supplier ? lang('Erp.edit_supplier') : lang('Erp.new_supplier') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold"><?= $supplier ? lang('Erp.edit_supplier') : lang('Erp.new_supplier') ?></h1>
    <a href="/erp/suppliers" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Erp.cancel') ?></a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $supplier ? '/erp/suppliers/'.$supplier['id'].'/update' : '/erp/suppliers/store' ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.supplier_name') ?> <span class="text-danger">*</span></label><input type="text" name="nom" class="form-control" required value="<?= esc($supplier['nom'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="<?= esc($supplier['email'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Téléphone</label><input type="text" name="telephone" class="form-control" value="<?= esc($supplier['telephone'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.tax_id') ?></label><input type="text" name="ice" class="form-control" value="<?= esc($supplier['ice'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.nif') ?></label><input type="text" name="nif" class="form-control" value="<?= esc($supplier['nif'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.main_contact') ?></label><input type="text" name="contact_principal" class="form-control" value="<?= esc($supplier['contact_principal'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.payment_terms') ?></label><input type="text" name="conditions_paiement" class="form-control" value="<?= esc($supplier['conditions_paiement'] ?? '') ?>" placeholder="30 jours, Comptant..."></div>
                <div class="col-md-8"><label class="form-label fw-semibold">Adresse</label><input type="text" name="adresse" class="form-control" value="<?= esc($supplier['adresse'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Ville</label><input type="text" name="ville" class="form-control" value="<?= esc($supplier['ville'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.status') ?></label>
                    <select name="statut" class="form-select">
                        <option value="active" <?= ($supplier['statut']??'active') === 'active' ? 'selected' : '' ?>><?= lang('Erp.active') ?></option>
                        <option value="inactive" <?= ($supplier['statut']??'') === 'inactive' ? 'selected' : '' ?>><?= lang('Erp.inactive') ?></option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label fw-semibold"><?= lang('Erp.notes') ?></label><textarea name="notes" class="form-control" rows="3"><?= esc($supplier['notes'] ?? '') ?></textarea></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-secondary"><i class="bi bi-check-lg me-1"></i><?= lang('Erp.save') ?></button>
                <a href="/erp/suppliers" class="btn btn-outline-secondary"><?= lang('Erp.cancel') ?></a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
