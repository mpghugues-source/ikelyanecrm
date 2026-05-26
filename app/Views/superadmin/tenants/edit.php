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

    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 rounded-3 mb-3"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/superadmin/tenants/view/<?= $tenant['id'] ?>" class="btn btn-sm btn-light border"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="mb-0" style="font-weight:800"><?= lang('SuperAdmin.edit_establishment') ?> — <?= esc($tenant['nom']) ?></h4>
            <small class="text-muted">ID #<?= $tenant['id'] ?> · <?= lang('SuperAdmin.slug_label') ?> : <?= esc($tenant['slug']) ?></small>
        </div>
    </div>

    <form action="/superadmin/tenants/update/<?= $tenant['id'] ?>" method="POST">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="section-title"><i class="bi bi-building me-2 text-primary"></i><?= lang('SuperAdmin.establishment_info') ?></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('Common.name') ?> *</label>
                            <input type="text" name="nom" class="form-control" style="border-radius:10px"
                                   value="<?= esc($tenant['nom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('Common.email') ?></label>
                            <input type="email" name="email" class="form-control" style="border-radius:10px"
                                   value="<?= esc($tenant['email']??'') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('Common.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control" style="border-radius:10px"
                                   value="<?= esc($tenant['telephone']??'') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.city') ?></label>
                            <input type="text" name="ville" class="form-control" style="border-radius:10px"
                                   value="<?= esc($tenant['ville']??'') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.country') ?></label>
                            <input type="text" name="pays" class="form-control" style="border-radius:10px"
                                   value="<?= esc($tenant['pays']??'Algérie') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.address') ?></label>
                            <textarea name="adresse" class="form-control" style="border-radius:10px" rows="2"><?= esc($tenant['adresse']??'') ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.brand_color') ?></label>
                            <div class="input-group">
                                <input type="color" name="couleur" id="colorPicker" class="form-control form-control-color"
                                       style="border-radius:10px 0 0 10px;width:50px"
                                       value="<?= esc($tenant['couleur']??'#0d6efd') ?>">
                                <input type="text" id="colorText" class="form-control" style="border-radius:0 10px 10px 0"
                                       value="<?= esc($tenant['couleur']??'#0d6efd') ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-card" style="background:linear-gradient(135deg,#f8faff,#faf5ff);border-color:#e0e7ff">
                    <div class="section-title"><i class="bi bi-info-circle me-2 text-primary"></i><?= lang('SuperAdmin.subscription_reminder') ?></div>
                    <a href="/superadmin/tenants/view/<?= $tenant['id'] ?>" class="btn btn-sm btn-outline-primary w-100" style="border-radius:8px">
                        <i class="bi bi-arrow-right me-1"></i><?= lang('SuperAdmin.manage_subscription') ?>
                    </a>
                </div>
                <button type="submit" class="btn w-100" style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;border:none;border-radius:10px;font-weight:700;padding:12px">
                    <i class="bi bi-save me-2"></i><?= lang('SuperAdmin.save_changes') ?>
                </button>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('colorPicker').addEventListener('input', function() {
    document.getElementById('colorText').value = this.value;
});
</script>
</body>
</html>
