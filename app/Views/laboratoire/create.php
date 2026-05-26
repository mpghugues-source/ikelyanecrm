<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/laboratoire" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><?= esc($title) ?></h1>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
<form method="POST" action="/admin/laboratoire/store">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label"><?= lang('Laboratoire.patient') ?></label>
            <select name="patient_id" class="form-select">
                <option value="">— <?= lang('Common.search') ?> —</option>
                <?php foreach ($patients as $p): ?>
                <option value="<?= $p['id'] ?>" <?= old('patient_id') == $p['id'] ? 'selected' : '' ?>><?= esc($p['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('Laboratoire.doctor') ?></label>
            <select name="medecin_id" class="form-select">
                <option value="">— <?= lang('Common.search') ?> —</option>
                <?php foreach ($medecins as $m): ?>
                <option value="<?= $m['id'] ?>" <?= old('medecin_id') == $m['id'] ? 'selected' : '' ?>><?= esc($m['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-8">
            <label class="form-label"><?= lang('Laboratoire.type') ?> <span class="text-danger">*</span></label>
            <input type="text" name="type_analyse" class="form-control" value="<?= esc(old('type_analyse')) ?>" placeholder="<?= lang('Laboratoire.placeholder_type') ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Laboratoire.request_date') ?> <span class="text-danger">*</span></label>
            <input type="date" name="date_demande" class="form-control" value="<?= esc(old('date_demande', date('Y-m-d'))) ?>" required>
        </div>
        <div class="col-md-6">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="urgence" value="1" id="urgence" <?= old('urgence') ? 'checked' : '' ?>>
                <label class="form-check-label" for="urgence"><span class="badge bg-danger"><?= lang('Common.urgent') ?></span></label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Laboratoire.notes') ?? lang('Pharmacie.notes') ?></label>
            <textarea name="notes" class="form-control" rows="3"><?= esc(old('notes')) ?></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-info text-white"><i class="bi bi-check-lg me-1"></i><?= lang('Common.save') ?></button>
            <a href="/admin/laboratoire" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
        </div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
