<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/ambulances" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><?= esc($title) ?></h1>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
<form method="POST" action="/admin/ambulances/<?= $vehicule['id'] ?>/update">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label"><?= lang('Ambulance.plate') ?> <span class="text-danger">*</span></label>
            <input type="text" name="immatriculation" class="form-control" value="<?= esc(old('immatriculation', $vehicule['immatriculation'])) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Ambulance.model') ?></label>
            <input type="text" name="modele" class="form-control" value="<?= esc(old('modele', $vehicule['modele'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Ambulance.vehicle_type') ?></label>
            <select name="type_vehicule" class="form-select">
                <?php foreach (['Ambulance','VSL','SMUR','Autre'] as $t): ?>
                <option value="<?= $t ?>" <?= old('type_vehicule', $vehicule['type_vehicule']) == $t ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Ambulance.status') ?></label>
            <select name="statut" class="form-select">
                <?php foreach (['disponible','en_mission','maintenance','hors_service'] as $val): ?>
                <option value="<?= $val ?>" <?= old('statut', $vehicule['statut']) == $val ? 'selected' : '' ?>><?= lang('Ambulance.statuses.' . $val) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Ambulance.driver_name') ?></label>
            <input type="text" name="chauffeur_nom" class="form-control" value="<?= esc(old('chauffeur_nom', $vehicule['chauffeur_nom'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Ambulance.driver_phone') ?></label>
            <input type="text" name="chauffeur_tel" class="form-control" value="<?= esc(old('chauffeur_tel', $vehicule['chauffeur_tel'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Ambulance.revision_date') ?></label>
            <input type="date" name="date_revision" class="form-control" value="<?= esc(old('date_revision', $vehicule['date_revision'])) ?>">
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Ambulance.notes') ?></label>
            <textarea name="notes" class="form-control" rows="3"><?= esc(old('notes', $vehicule['notes'])) ?></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Common.update') ?></button>
            <a href="/admin/ambulances" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
        </div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
