<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/pharmacie" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><?= esc($title) ?></h1>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/pharmacie/<?= $medicament['id'] ?>/update">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><?= lang('Pharmacie.name') ?> <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" value="<?= esc(old('nom', $medicament['nom'])) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= lang('Pharmacie.category') ?></label>
                    <input type="text" name="categorie" class="form-control" value="<?= esc(old('categorie', $medicament['categorie'])) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= lang('Pharmacie.form') ?></label>
                    <select name="forme" class="form-select">
                        <option value="">— <?= lang('Pharmacie.forms') ?> —</option>
                        <?php foreach (['Comprimé','Gélule','Sirop','Injectable','Pommade','Suppositoire','Autre'] as $f): ?>
                        <option value="<?= $f ?>" <?= old('forme', $medicament['forme']) == $f ? 'selected' : '' ?>><?= $f ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= lang('Pharmacie.dosage') ?></label>
                    <input type="text" name="dosage" class="form-control" value="<?= esc(old('dosage', $medicament['dosage'])) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= lang('Pharmacie.current_stock') ?> <span class="text-danger">*</span></label>
                    <input type="number" name="stock_actuel" class="form-control" value="<?= esc(old('stock_actuel', $medicament['stock_actuel'])) ?>" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= lang('Pharmacie.min_stock') ?> <span class="text-danger">*</span></label>
                    <input type="number" name="stock_minimum" class="form-control" value="<?= esc(old('stock_minimum', $medicament['stock_minimum'])) ?>" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= lang('Pharmacie.unit_price') ?> (DA) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="prix_unitaire" class="form-control" value="<?= esc(old('prix_unitaire', $medicament['prix_unitaire'])) ?>" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?= lang('Pharmacie.supplier') ?></label>
                    <input type="text" name="fournisseur" class="form-control" value="<?= esc(old('fournisseur', $medicament['fournisseur'])) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><?= lang('Pharmacie.expiry_date') ?></label>
                    <input type="date" name="date_expiration" class="form-control" value="<?= esc(old('date_expiration', $medicament['date_expiration'])) ?>">
                </div>
                <div class="col-12">
                    <label class="form-label"><?= lang('Pharmacie.notes') ?></label>
                    <textarea name="notes" class="form-control" rows="3"><?= esc(old('notes', $medicament['notes'])) ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Common.update') ?></button>
                    <a href="/admin/pharmacie" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
