<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/radiologie" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><?= esc($title) ?></h1>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
<form method="POST" action="/admin/radiologie/<?= $examen['id'] ?>/update">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label"><?= lang('Radiologie.patient') ?></label>
            <select name="patient_id" class="form-select">
                <option value="">— <?= lang('Common.search') ?> —</option>
                <?php foreach ($patients as $p): ?>
                <option value="<?= $p['id'] ?>" <?= old('patient_id', $examen['patient_id']) == $p['id'] ? 'selected' : '' ?>><?= esc($p['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('Radiologie.doctor') ?></label>
            <select name="medecin_id" class="form-select">
                <option value="">— <?= lang('Common.search') ?> —</option>
                <?php foreach ($medecins as $m): ?>
                <option value="<?= $m['id'] ?>" <?= old('medecin_id', $examen['medecin_id']) == $m['id'] ? 'selected' : '' ?>><?= esc($m['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('Radiologie.exam_type') ?> <span class="text-danger">*</span></label>
            <select name="type_examen" class="form-select" required>
                <?php foreach (['Radiographie','Échographie','Scanner','IRM','Mammographie','Autre'] as $t): ?>
                <option value="<?= $t ?>" <?= old('type_examen', $examen['type_examen']) == $t ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('Radiologie.region') ?></label>
            <input type="text" name="region" class="form-control" value="<?= esc(old('region', $examen['region'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Radiologie.request_date') ?> <span class="text-danger">*</span></label>
            <input type="date" name="date_demande" class="form-control" value="<?= esc(old('date_demande', $examen['date_demande'])) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Radiologie.exam_date') ?></label>
            <input type="date" name="date_examen" class="form-control" value="<?= esc(old('date_examen', $examen['date_examen'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Radiologie.status') ?></label>
            <select name="statut" class="form-select">
                <?php foreach (['en_attente','programme','realise','annule'] as $val): ?>
                <option value="<?= $val ?>" <?= old('statut', $examen['statut']) == $val ? 'selected' : '' ?>><?= lang('Radiologie.statuses.' . $val) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="urgence" value="1" id="urgence" <?= old('urgence', $examen['urgence']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="urgence"><span class="badge bg-danger"><?= lang('Common.urgent') ?></span></label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Radiologie.indication') ?></label>
            <textarea name="description" class="form-control" rows="2"><?= esc(old('description', $examen['description'])) ?></textarea>
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Radiologie.report') ?></label>
            <textarea name="compte_rendu" class="form-control" rows="4"><?= esc(old('compte_rendu', $examen['compte_rendu'])) ?></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Common.update') ?></button>
            <a href="/admin/radiologie" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
        </div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
