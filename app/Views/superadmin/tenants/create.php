<!DOCTYPE html>
<html lang="<?= session()->get('locale') ?? 'fr' ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family:'Inter',sans-serif; } body { background:#f1f5f9; }
        .sa-sidebar { width:260px;background:#0f172a;min-height:100vh;position:fixed;left:0;top:0;z-index:100;padding:24px 0; }
        .sa-brand { padding:0 24px 24px;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px; }
        .sa-brand-name { font-size:1.3rem;font-weight:800;color:#fff; }
        .sa-brand-badge { font-size:.7rem;background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;padding:2px 10px;border-radius:50px;font-weight:700; }
        .sa-nav-link { display:flex;align-items:center;gap:12px;padding:12px 24px;color:#94a3b8;text-decoration:none;font-size:.9rem;font-weight:500;transition:all .2s;border-left:3px solid transparent; }
        .sa-nav-link:hover,.sa-nav-link.active { color:#fff;background:rgba(255,255,255,.05);border-left-color:#1a56db; }
        .sa-nav-link i { font-size:1.1rem;width:20px;text-align:center; }
        .sa-main { margin-left:260px;padding:32px; }
        .form-card { background:#fff;border-radius:16px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0;margin-bottom:20px; }
        .section-title { font-size:.95rem;font-weight:700;color:#0f172a;margin-bottom:18px;padding-bottom:10px;border-bottom:2px solid #f1f5f9; }
    </style>
</head>
<body>
<?= view('superadmin/partials/sidebar', ['activeNav' => 'tenants']) ?>
<div class="sa-main">

    <?php if(session()->getFlashdata('errors') || isset($errors)): ?>
    <div class="alert alert-danger border-0 rounded-3 mb-3">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?php foreach((array)(session()->getFlashdata('errors') ?? $errors) as $e): ?>
            <div><?= esc($e) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/superadmin/tenants" class="btn btn-sm btn-light border"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="mb-0" style="font-weight:800"><?= lang('SuperAdmin.new_clinic') ?></h4>
            <small class="text-muted"><?= lang('SuperAdmin.new_establishment') ?> · IkelyaneMed</small>
        </div>
    </div>

    <form action="/superadmin/tenants/store" method="POST">
        <?= csrf_field() ?>
        <div class="row g-3">

            <!-- Informations de l'établissement -->
            <div class="col-lg-7">
                <div class="form-card">
                    <div class="section-title"><i class="bi bi-building me-2 text-primary"></i><?= lang('SuperAdmin.establishment_info') ?></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.establishment_name') ?> *</label>
                            <input type="text" name="nom" class="form-control" style="border-radius:10px"
                                   value="<?= old('nom') ?>" placeholder="ex: CHU Mustapha Bacha" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('Common.email') ?> *</label>
                            <input type="email" name="email" class="form-control" style="border-radius:10px"
                                   value="<?= old('email') ?>" placeholder="contact@clinique.dz" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('Common.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control" style="border-radius:10px"
                                   value="<?= old('telephone') ?>" placeholder="+213 21 00 00 00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.city') ?></label>
                            <input type="text" name="ville" class="form-control" style="border-radius:10px"
                                   value="<?= old('ville') ?>" placeholder="Alger">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.country') ?></label>
                            <input type="text" name="pays" class="form-control" style="border-radius:10px"
                                   value="<?= old('pays', 'Algérie') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.address') ?></label>
                            <textarea name="adresse" class="form-control" style="border-radius:10px" rows="2"
                                      placeholder="Rue, quartier..."><?= old('adresse') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.brand_color') ?></label>
                            <div class="input-group">
                                <input type="color" name="couleur" class="form-control form-control-color" style="border-radius:10px 0 0 10px;width:50px"
                                       value="<?= old('couleur', '#0d6efd') ?>">
                                <input type="text" class="form-control" style="border-radius:0 10px 10px 0" value="#0d6efd" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abonnement + Admin -->
            <div class="col-lg-5">
                <div class="form-card mb-3">
                    <div class="section-title"><i class="bi bi-credit-card me-2 text-primary"></i><?= lang('SuperAdmin.subscription_section') ?></div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.plan_label') ?></label>
                        <select name="abonnement" class="form-select" style="border-radius:10px">
                            <option value="gratuit" <?= old('abonnement')==='gratuit'?'selected':'' ?>><?= lang('SuperAdmin.plan_free') ?></option>
                            <option value="basic"   <?= old('abonnement')==='basic'?'selected':'' ?>><?= lang('SuperAdmin.plan_basic') ?></option>
                            <option value="premium" <?= old('abonnement')==='premium'?'selected':'' ?>><?= lang('SuperAdmin.plan_premium') ?></option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-600" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.expire_date') ?></label>
                        <input type="date" name="expire_le" class="form-control" style="border-radius:10px"
                               value="<?= old('expire_le') ?>">
                    </div>
                </div>

                <div class="form-card">
                    <div class="section-title"><i class="bi bi-person-gear me-2 text-primary"></i><?= lang('SuperAdmin.admin_account') ?></div>
                    <p class="text-muted" style="font-size:.82rem"><?= lang('SuperAdmin.admin_optional') ?></p>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-600" style="font-size:.82rem;font-weight:600"><?= lang('Common.name') ?></label>
                            <input type="text" name="admin_nom" class="form-control form-control-sm" style="border-radius:8px" value="<?= old('admin_nom') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-600" style="font-size:.82rem;font-weight:600"><?= lang('Common.firstname') ?></label>
                            <input type="text" name="admin_prenom" class="form-control form-control-sm" style="border-radius:8px" value="<?= old('admin_prenom') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:.82rem;font-weight:600"><?= lang('Common.email') ?></label>
                            <input type="email" name="admin_email" class="form-control form-control-sm" style="border-radius:8px"
                                   value="<?= old('admin_email') ?>" placeholder="admin@clinique.dz">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:.82rem;font-weight:600"><?= lang('SuperAdmin.admin_password') ?></label>
                            <input type="text" name="admin_password" class="form-control form-control-sm" style="border-radius:8px"
                                   placeholder="<?= lang('SuperAdmin.admin_default_pw') ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn w-100 mt-2" style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;border:none;border-radius:10px;font-weight:700;padding:12px">
                    <i class="bi bi-building-add me-2"></i><?= lang('SuperAdmin.create_btn') ?>
                </button>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelector('input[type=color]').addEventListener('input', function() {
    this.nextElementSibling.value = this.value;
});
</script>
</body>
</html>
