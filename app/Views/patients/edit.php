<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-pencil text-primary me-2"></i><?= lang('Patients.edit') ?></h4>
    <a href="/admin/patients/view/<?= $patient['id'] ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?>
    </a>
</div>

<form action="/admin/patients/update/<?= $patient['id'] ?>" method="POST">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-person me-2 text-primary"></i><?= lang('Patients.personal_info') ?></h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.name') ?> *</label>
                            <input type="text" name="nom" class="form-control" value="<?= esc($patient['nom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.firstname') ?> *</label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc($patient['prenom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.birthdate') ?></label>
                            <input type="date" name="date_naissance" class="form-control" value="<?= esc($patient['date_naissance']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.gender') ?></label>
                            <select name="sexe" class="form-select">
                                <option value=""><?= lang('Patients.gender_unspecified') ?></option>
                                <option value="M" <?= $patient['sexe']==='M'?'selected':'' ?>><?= lang('Patients.male') ?></option>
                                <option value="F" <?= $patient['sexe']==='F'?'selected':'' ?>><?= lang('Patients.female') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control" value="<?= esc($patient['telephone']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.email') ?></label>
                            <input type="email" name="email" class="form-control" value="<?= esc($patient['email']) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?= lang('Patients.address') ?></label>
                            <input type="text" name="adresse" class="form-control" value="<?= esc($patient['adresse']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.city') ?></label>
                            <input type="text" name="ville" class="form-control" value="<?= esc($patient['ville']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.blood_type') ?></label>
                            <select name="groupe_sanguin" class="form-select">
                                <option value="">—</option>
                                <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g): ?>
                                <option value="<?= $g ?>" <?= $patient['groupe_sanguin']===$g?'selected':'' ?>><?= $g ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-heart-pulse me-2 text-danger"></i><?= lang('Patients.medical_info') ?></h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Common.allergies') ?></label>
                        <textarea name="allergies" class="form-control" rows="2"><?= esc($patient['allergies']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Patients.antecedents_short') ?></label>
                        <textarea name="antecedents" class="form-control" rows="3"><?= esc($patient['antecedents']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Patients.current_treatment') ?></label>
                        <textarea name="traitement_en_cours" class="form-control" rows="2"><?= esc($patient['traitement_en_cours']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Common.notes') ?></label>
                        <textarea name="notes" class="form-control" rows="2"><?= esc($patient['notes']) ?></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-save me-1"></i><?= lang('Patients.save_btn') ?>
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
