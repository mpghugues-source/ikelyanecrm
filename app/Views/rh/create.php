<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/rh" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><?= esc($title) ?></h1>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
<form method="POST" action="/admin/rh/store">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.name') ?> <span class="text-danger">*</span></label>
            <input type="text" name="nom" class="form-control" value="<?= esc(old('nom')) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.firstname') ?> <span class="text-danger">*</span></label>
            <input type="text" name="prenom" class="form-control" value="<?= esc(old('prenom')) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.position') ?> <span class="text-danger">*</span></label>
            <input type="text" name="poste" class="form-control" value="<?= esc(old('poste')) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.department') ?></label>
            <input type="text" name="departement" class="form-control" value="<?= esc(old('departement')) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.contract') ?></label>
            <select name="contrat" class="form-select">
                <?php foreach (['CDI','CDD','Vacation','Stage','Autre'] as $c): ?>
                <option value="<?= $c ?>" <?= old('contrat', 'CDI') == $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.hire_date') ?></label>
            <input type="date" name="date_embauche" class="form-control" value="<?= esc(old('date_embauche')) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.salary') ?> (DA)</label>
            <input type="number" step="0.01" name="salaire" class="form-control" value="<?= esc(old('salaire')) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.phone') ?></label>
            <input type="text" name="telephone" class="form-control" value="<?= esc(old('telephone')) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('RH.email') ?></label>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>">
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('RH.notes') ?></label>
            <textarea name="notes" class="form-control" rows="3"><?= esc(old('notes')) ?></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-secondary"><i class="bi bi-check-lg me-1"></i><?= lang('Common.save') ?></button>
            <a href="/admin/rh" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
        </div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
