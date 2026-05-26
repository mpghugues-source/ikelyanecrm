<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/accueil" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><?= esc($title) ?></h1>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
<form method="POST" action="/admin/accueil/store">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label"><?= lang('Accueil.patient') ?></label>
            <select name="patient_id" class="form-select">
                <option value="">— <?= lang('Accueil.external_visitor') ?> —</option>
                <?php foreach ($patients as $p): ?>
                <option value="<?= $p['id'] ?>" <?= old('patient_id') == $p['id'] ? 'selected' : '' ?>><?= esc($p['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('Accueil.visitor') ?></label>
            <input type="text" name="nom_visiteur" class="form-control" value="<?= esc(old('nom_visiteur')) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Accueil.operation_type') ?> <span class="text-danger">*</span></label>
            <select name="type_operation" class="form-select" required>
                <?php foreach (['admission','sortie','consultation','visite','urgence'] as $val): ?>
                <option value="<?= $val ?>" <?= old('type_operation') == $val ? 'selected' : '' ?>><?= lang('Accueil.types.' . $val) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Accueil.datetime') ?> <span class="text-danger">*</span></label>
            <input type="datetime-local" name="date_heure" class="form-control" value="<?= esc(old('date_heure', date('Y-m-d\TH:i'))) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Accueil.room') ?></label>
            <input type="text" name="chambre" class="form-control" value="<?= esc(old('chambre')) ?>">
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Accueil.reason') ?></label>
            <input type="text" name="motif" class="form-control" value="<?= esc(old('motif')) ?>">
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Accueil.notes') ?></label>
            <textarea name="notes" class="form-control" rows="3"><?= esc(old('notes')) ?></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Common.save') ?></button>
            <a href="/admin/accueil" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
        </div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
