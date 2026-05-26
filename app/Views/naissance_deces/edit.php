<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/naissance-deces" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><?= esc($title) ?></h1>
</div>
<?php if ($errors = session()->getFlashdata('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><div class="card-body">
<form method="POST" action="/admin/naissance-deces/<?= $evenement['id'] ?>/update">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label"><?= lang('NaissanceDeces.event_type') ?> <span class="text-danger">*</span></label>
            <select name="type_evenement" class="form-select" required>
                <option value="naissance" <?= old('type_evenement', $evenement['type_evenement']) == 'naissance' ? 'selected' : '' ?>><?= lang('NaissanceDeces.birth') ?></option>
                <option value="deces" <?= old('type_evenement', $evenement['type_evenement']) == 'deces' ? 'selected' : '' ?>><?= lang('NaissanceDeces.death') ?></option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('NaissanceDeces.name') ?> <span class="text-danger">*</span></label>
            <input type="text" name="nom" class="form-control" value="<?= esc(old('nom', $evenement['nom'])) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('NaissanceDeces.firstname') ?></label>
            <input type="text" name="prenom" class="form-control" value="<?= esc(old('prenom', $evenement['prenom'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('NaissanceDeces.date') ?> <span class="text-danger">*</span></label>
            <input type="date" name="date_evenement" class="form-control" value="<?= esc(old('date_evenement', $evenement['date_evenement'])) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('NaissanceDeces.time') ?></label>
            <input type="time" name="heure_evenement" class="form-control" value="<?= esc(old('heure_evenement', $evenement['heure_evenement'])) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label"><?= lang('NaissanceDeces.place') ?></label>
            <input type="text" name="lieu" class="form-control" value="<?= esc(old('lieu', $evenement['lieu'])) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('NaissanceDeces.doctor') ?></label>
            <select name="medecin_id" class="form-select">
                <option value="">— <?= lang('Common.search') ?> —</option>
                <?php foreach ($medecins as $m): ?>
                <option value="<?= $m['id'] ?>" <?= old('medecin_id', $evenement['medecin_id']) == $m['id'] ? 'selected' : '' ?>><?= esc($m['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= lang('NaissanceDeces.cert_number') ?></label>
            <input type="text" name="numero_certificat" class="form-control" value="<?= esc(old('numero_certificat', $evenement['numero_certificat'])) ?>">
        </div>
        <div class="col-12">
            <label class="form-label"><?= lang('NaissanceDeces.notes') ?></label>
            <textarea name="notes" class="form-control" rows="3"><?= esc(old('notes', $evenement['notes'])) ?></textarea>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Common.update') ?></button>
            <a href="/admin/naissance-deces" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
        </div>
    </div>
</form>
</div></div>
<?= $this->endSection() ?>
