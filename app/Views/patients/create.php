<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h4><i class="bi bi-person-plus text-primary me-2"></i><?= lang('Patients.new_patient') ?></h4>
    </div>
    <a href="/admin/patients" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?>
    </a>
</div>

<form action="/admin/patients/store" method="POST">
    <?= csrf_field() ?>
    <div class="row g-3">
        <!-- Informations personnelles -->
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-person me-2 text-primary"></i><?= lang('Patients.personal_info') ?></h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" value="<?= esc(old('nom')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.firstname') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc(old('prenom')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.birthdate') ?></label>
                            <input type="date" name="date_naissance" class="form-control" value="<?= esc(old('date_naissance')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.gender') ?></label>
                            <select name="sexe" class="form-select">
                                <option value=""><?= lang('Patients.gender_unspecified') ?></option>
                                <option value="M" <?= old('sexe')==='M'?'selected':'' ?>><?= lang('Patients.male') ?></option>
                                <option value="F" <?= old('sexe')==='F'?'selected':'' ?>><?= lang('Patients.female') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control" value="<?= esc(old('telephone')) ?>" placeholder="+213 ...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.email') ?></label>
                            <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?= lang('Patients.address') ?></label>
                            <input type="text" name="adresse" class="form-control" value="<?= esc(old('adresse')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.city') ?></label>
                            <input type="text" name="ville" class="form-control" value="<?= esc(old('ville')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Patients.blood_type') ?></label>
                            <select name="groupe_sanguin" class="form-select">
                                <option value="">—</option>
                                <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g): ?>
                                <option value="<?= $g ?>" <?= old('groupe_sanguin')===$g?'selected':'' ?>><?= $g ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations médicales -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-heart-pulse me-2 text-danger"></i><?= lang('Patients.medical_info') ?></h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Common.allergies') ?></label>
                        <textarea name="allergies" class="form-control" rows="2" placeholder="<?= lang('Common.allergies') ?>..."><?= esc(old('allergies')) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Patients.antecedents') ?></label>
                        <textarea name="antecedents" class="form-control" rows="3" placeholder="<?= lang('Patients.antecedents') ?>..."><?= esc(old('antecedents')) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Patients.current_treatment') ?></label>
                        <textarea name="traitement_en_cours" class="form-control" rows="2"><?= esc(old('traitement_en_cours')) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title"><i class="bi bi-shield-check me-2 text-success"></i><?= lang('Patients.insurance') ?></h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Patients.insurer') ?></label>
                        <input type="text" name="assurance" class="form-control" value="<?= esc(old('assurance')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Patients.social_security') ?></label>
                        <input type="text" name="numero_secu" class="form-control" value="<?= esc(old('numero_secu')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Patients.emergency_contact') ?></label>
                        <input type="text" name="contact_urgence_nom" class="form-control mb-2" placeholder="<?= lang('Patients.name') ?>" value="<?= esc(old('contact_urgence_nom')) ?>">
                        <input type="tel" name="contact_urgence_tel" class="form-control" placeholder="<?= lang('Patients.phone') ?>" value="<?= esc(old('contact_urgence_tel')) ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-person-check me-1"></i><?= lang('Patients.create_btn') ?>
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
