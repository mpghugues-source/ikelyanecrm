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
        .info-box { background:#f8fafc;border-radius:12px;padding:16px 20px;border:1px solid #e2e8f0;margin-bottom:16px; }
    </style>
</head>
<body>
<div class="sa-sidebar">
<?= view('superadmin/partials/sidebar', ['activeNav' => 'settings']) ?>
<div class="sa-main">
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 rounded-3 mb-3"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <h4 class="mb-0" style="font-weight:800"><?= lang('Admin.platform_settings') ?></h4>
        <small class="text-muted"><?= lang('Admin.platform_subtitle') ?></small>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <!-- Informations plateforme -->
            <div class="form-card">
                <div class="section-title"><i class="bi bi-globe me-2 text-primary"></i><?= lang('SuperAdmin.platform_info') ?></div>
                <form action="/superadmin/settings/save" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.platform_name') ?></label>
                            <input type="text" name="platform_nom" class="form-control" style="border-radius:10px"
                                   value="<?= esc($platformTenant['nom'] ?? 'IkelyaneCRM') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.platform_email') ?></label>
                            <input type="email" name="platform_email" class="form-control" style="border-radius:10px"
                                   value="<?= esc($platformTenant['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.platform_phone') ?></label>
                            <input type="tel" name="platform_telephone" class="form-control" style="border-radius:10px"
                                   value="<?= esc($platformTenant['telephone'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.platform_address') ?></label>
                            <textarea name="platform_adresse" class="form-control" style="border-radius:10px" rows="2"><?= esc($platformTenant['adresse'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn mt-3 w-100" style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;border:none;border-radius:10px;font-weight:700;padding:11px">
                        <i class="bi bi-save me-2"></i><?= lang('Common.save') ?>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <!-- Comptes démo -->
            <div class="form-card">
                <div class="section-title"><i class="bi bi-person-badge me-2 text-primary"></i><?= lang('SuperAdmin.demo_accounts') ?></div>
                <div class="info-box">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="background:#ef4444;color:#fff;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">SUPER ADMIN</span>
                    </div>
                    <div style="font-size:.85rem;font-weight:600">superadmin@ikelyanecrm.com</div>
                    <div class="text-muted" style="font-size:.8rem">Admin@2024</div>
                </div>
                <div class="info-box">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="background:#1a56db;color:#fff;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">ADMIN</span>
                    </div>
                    <div style="font-size:.85rem;font-weight:600">admin@ikelyane.dz</div>
                    <div class="text-muted" style="font-size:.8rem">Admin@2024</div>
                </div>
                <div class="info-box">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="background:#0ea5e9;color:#fff;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">MANAGER</span>
                    </div>
                    <div style="font-size:.85rem;font-weight:600">manager@ikelyane.dz</div>
                    <div class="text-muted" style="font-size:.8rem">Admin@2024</div>
                </div>
                <div class="info-box">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="background:#7c3aed;color:#fff;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">COMMERCIAL</span>
                    </div>
                    <div style="font-size:.85rem;font-weight:600">commercial@ikelyane.dz</div>
                    <div class="text-muted" style="font-size:.8rem">Admin@2024</div>
                </div>
                <div class="info-box mb-0">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="background:#059669;color:#fff;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:700">SUPPORT</span>
                    </div>
                    <div style="font-size:.85rem;font-weight:600">support@ikelyane.dz</div>
                    <div class="text-muted" style="font-size:.8rem">Admin@2024</div>
                </div>
            </div>

            <!-- Hôpitaux démo -->
            <div class="form-card">
                <div class="section-title"><i class="bi bi-building me-2 text-primary"></i><?= lang('SuperAdmin.demo_establishments') ?></div>
                <?php
                $demos = [
                    ['CHU Mustapha Bacha','Alger','premium','admin@chu-mustapha.dz'],
                    ['Clinique El Azhar','Oran','basic','admin@clinique-elazhar.dz'],
                    ['Hôpital Ibn Sina','Constantine','premium','admin@hopital-ibnsina.dz'],
                ];
                $planColors = ['gratuit'=>'#94a3b8','basic'=>'#1a56db','premium'=>'#7c3aed'];
                foreach($demos as $d):
                ?>
                <div class="info-box <?= $d !== end($demos) ? '' : 'mb-0' ?>">
                    <div style="font-weight:700;font-size:.88rem"><?= $d[0] ?></div>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="text-muted" style="font-size:.78rem"><i class="bi bi-geo-alt me-1"></i><?= $d[1] ?></span>
                        <span style="background:<?= $planColors[$d[2]] ?>;color:#fff;padding:1px 8px;border-radius:50px;font-size:.7rem;font-weight:700"><?= ucfirst($d[2]) ?></span>
                    </div>
                    <div class="text-muted" style="font-size:.78rem;margin-top:2px"><?= $d[3] ?> / Admin@2024</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
