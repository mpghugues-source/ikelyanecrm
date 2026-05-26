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
<form method="POST" action="/admin/laboratoire/<?= $analyse['id'] ?>/update">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label"><?= lang('Laboratoire.patient') ?></label>
            <select name="patient_id" class="form-select">
                <option value="">— <?= lang('Common.search') ?> —</option>
                <?php foreach ($patients as $p): ?>
                <option value="<?= $p['id'] ?>" <?= old('patient_id', $analyse['patient_id']) == $p['id'] ? 'selected' : '' ?>><?= esc($p['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('Laboratoire.doctor') ?></label>
            <select name="medecin_id" class="form-select">
                <option value="">— <?= lang('Common.search') ?> —</option>
                <?php foreach ($medecins as $m): ?>
                <option value="<?= $m['id'] ?>" <?= old('medecin_id', $analyse['medecin_id']) == $m['id'] ? 'selected' : '' ?>><?= esc($m['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-8">
            <label class="form-label"><?= lang('Laboratoire.type') ?> <span class="text-danger">*</span></label>
            <input type="text" name="type_analyse" class="form-control" value="<?= esc(old('type_analyse', $analyse['type_analyse'])) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Laboratoire.request_date') ?> <span class="text-danger">*</span></label>
            <input type="date" name="date_demande" class="form-control" value="<?= esc(old('date_demande', $analyse['date_demande'])) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Laboratoire.result_date') ?></label>
            <input type="date" name="date_resultat" class="form-control" value="<?= esc(old('date_resultat', $analyse['date_resultat'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('Laboratoire.status') ?></label>
            <select name="statut" class="form-select">
                <?php foreach (['en_attente','en_cours','resultat_disponible','annule'] as $val): ?>
                <option value="<?= $val ?>" <?= old('statut', $analyse['statut']) == $val ? 'selected' : '' ?>><?= lang('Laboratoire.statuses.' . $val) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="urgence" value="1" id="urgence" <?= old('urgence', $analyse['urgence']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="urgence"><span class="badge bg-danger"><?= lang('Common.urgent') ?></span></label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Laboratoire.notes') ?? lang('Pharmacie.notes') ?></label>
            <textarea name="notes" class="form-control" rows="2"><?= esc(old('notes', $analyse['notes'])) ?></textarea>
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('Laboratoire.result') ?></label>
            <textarea name="resultat" class="form-control" rows="4"><?= esc(old('resultat', $analyse['resultat'])) ?></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Common.update') ?></button>
            <a href="/admin/laboratoire" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
        </div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
