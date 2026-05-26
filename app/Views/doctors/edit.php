<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-pencil text-primary me-2"></i><?= lang('Doctors.edit') ?></h4>
    <a href="/admin/doctors/view/<?= $doctor['id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<form action="/admin/doctors/update/<?= $doctor['id'] ?>" method="POST">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-person me-2 text-primary"></i><?= lang('Doctors.identity') ?></h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Doctors.name') ?></label>
                            <input type="text" name="nom" class="form-control" value="<?= esc($doctor['user_nom']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Doctors.firstname') ?></label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc($doctor['user_prenom']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Doctors.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control" value="<?= esc($doctor['user_telephone']) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-award me-2 text-primary"></i><?= lang('Doctors.professional_profile') ?></h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.specialty') ?></label>
                        <select name="specialite_id" class="form-select">
                            <option value="">—</option>
                            <?php foreach ($specialites as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $doctor['specialite_id'] == $s['id'] ? 'selected' : '' ?>><?= esc($s['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.order_number') ?></label>
                        <input type="text" name="numero_ordre" class="form-control" value="<?= esc($doctor['numero_ordre']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.consultation_fee_da') ?></label>
                        <input type="number" name="tarif_consultation" class="form-control" value="<?= $doctor['tarif_consultation'] ?>">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label"><?= lang('Doctors.start_time') ?></label>
                            <input type="time" name="heure_debut" class="form-control" value="<?= substr($doctor['heure_debut'],0,5) ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label"><?= lang('Doctors.end_time') ?></label>
                            <input type="time" name="heure_fin" class="form-control" value="<?= substr($doctor['heure_fin'],0,5) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.biography') ?></label>
                        <textarea name="biographie" class="form-control" rows="3"><?= esc($doctor['biographie']) ?></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Enregistrer</button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
