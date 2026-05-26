<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-person-plus text-primary me-2"></i><?= lang('Doctors.new_doctor') ?></h4>
    <a href="/admin/doctors" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<form action="/admin/doctors/store" method="POST">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-person me-2 text-primary"></i><?= lang('Doctors.account_info') ?></h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Doctors.name') ?> *</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Doctors.firstname') ?> *</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Doctors.email') ?> *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Doctors.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?= lang('Doctors.password_label') ?></label>
                            <input type="password" name="password" class="form-control" placeholder="Laisser vide pour mot de passe par défaut: Medecin@2024">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-award me-2 text-primary"></i><?= lang('Doctors.professional_info') ?></h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.specialty') ?></label>
                        <select name="specialite_id" class="form-select">
                            <option value=""><?= lang('Doctors.select_specialty') ?></option>
                            <?php foreach ($specialites as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.order_number') ?></label>
                        <input type="text" name="numero_ordre" class="form-control" placeholder="ex: ALG-001234">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.consultation_fee_da') ?></label>
                        <input type="number" name="tarif_consultation" class="form-control" value="2000" min="0">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label"><?= lang('Doctors.start_time') ?></label>
                            <input type="time" name="heure_debut" class="form-control" value="08:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label"><?= lang('Doctors.end_time') ?></label>
                            <input type="time" name="heure_fin" class="form-control" value="17:00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('Doctors.biography') ?></label>
                        <textarea name="biographie" class="form-control" rows="3" placeholder="Résumé du parcours..."></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-person-check me-1"></i><?= lang('Doctors.create_btn') ?>
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
