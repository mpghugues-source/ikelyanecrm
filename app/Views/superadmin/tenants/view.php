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
        .info-card { background:#fff;border-radius:16px;padding:24px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0;margin-bottom:20px; }
        .info-row { display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9; }
        .info-row:last-child { border-bottom:none; }
        .info-label { font-size:.85rem;color:#64748b;font-weight:600; }
        .info-value { font-size:.9rem;font-weight:600;color:#0f172a; }
    </style>
</head>
<body>
<?= view('superadmin/partials/sidebar', ['activeNav' => 'tenants']) ?>
<div class="sa-main">
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 rounded-3 mb-3"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/superadmin/tenants" class="btn btn-sm btn-light border"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="mb-0 fw-800" style="font-weight:800"><?= esc($tenant['nom']) ?></h4>
            <small class="text-muted"><?= lang('SuperAdmin.clinic_id') ?><?= $tenant['id'] ?></small>
        </div>
        <div class="ms-auto d-flex gap-2">
            <a href="/superadmin/tenants/edit/<?= $tenant['id'] ?>" class="btn btn-sm btn-outline-primary" style="border-radius:10px">
                <i class="bi bi-pencil me-1"></i><?= lang('SuperAdmin.edit_establishment') ?>
            </a>
            <a href="/superadmin/tenants/toggle/<?= $tenant['id'] ?>" class="btn btn-sm <?= $tenant['actif'] ? 'btn-danger' : 'btn-success' ?>"
               style="border-radius:10px" onclick="return confirm('<?= lang('SuperAdmin.confirm') ?>')">
                <i class="bi bi-<?= $tenant['actif'] ? 'pause' : 'play' ?>-circle me-1"></i>
                <?= $tenant['actif'] ? lang('SuperAdmin.deactivate') : lang('SuperAdmin.activate') ?>
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <!-- Infos clinique -->
            <div class="info-card">
                <h6 class="fw-700 mb-3" style="font-weight:700"><i class="bi bi-building me-2 text-primary"></i><?= lang('SuperAdmin.clinic_info') ?></h6>
                <div class="info-row"><span class="info-label"><?= lang('Common.name') ?></span><span class="info-value"><?= esc($tenant['nom']) ?></span></div>
                <div class="info-row"><span class="info-label"><?= lang('SuperAdmin.slug_label') ?></span><span class="info-value"><?= esc($tenant['slug']) ?></span></div>
                <div class="info-row"><span class="info-label"><?= lang('Common.email') ?></span><span class="info-value"><?= esc($tenant['email']??'—') ?></span></div>
                <div class="info-row"><span class="info-label"><?= lang('Common.phone') ?></span><span class="info-value"><?= esc($tenant['telephone']??'—') ?></span></div>
                <div class="info-row"><span class="info-label"><?= lang('SuperAdmin.city') ?></span><span class="info-value"><?= esc($tenant['ville']??'—') ?>, <?= esc($tenant['pays']??'') ?></span></div>
                <div class="info-row"><span class="info-label"><?= lang('SuperAdmin.address') ?></span><span class="info-value"><?= esc($tenant['adresse']??'—') ?></span></div>
                <div class="info-row"><span class="info-label"><?= lang('SuperAdmin.registered_on') ?></span><span class="info-value"><?= date('d/m/Y', strtotime($tenant['created_at'])) ?></span></div>
                <div class="info-row">
                    <span class="info-label"><?= lang('Common.status') ?></span>
                    <span style="background:<?= $tenant['actif'] ? '#d1fae5' : '#fee2e2' ?>;color:<?= $tenant['actif'] ? '#059669' : '#dc2626' ?>;padding:3px 12px;border-radius:50px;font-size:.8rem;font-weight:700">
                        <?= $tenant['actif'] ? lang('SuperAdmin.active') : lang('SuperAdmin.inactive') ?>
                    </span>
                </div>
            </div>

            <!-- Utilisateurs -->
            <div class="info-card">
                <h6 class="fw-700 mb-3" style="font-weight:700"><i class="bi bi-people me-2 text-primary"></i><?= lang('SuperAdmin.users_section') ?> (<?= count($users) ?>)</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle" style="font-size:.88rem">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th class="border-0 py-2" style="font-weight:700;color:#64748b"><?= strtoupper(lang('Common.name')) ?></th>
                                <th class="border-0 py-2" style="font-weight:700;color:#64748b"><?= strtoupper(lang('Common.email')) ?></th>
                                <th class="border-0 py-2" style="font-weight:700;color:#64748b"><?= strtoupper(lang('SuperAdmin.role_col')) ?></th>
                                <th class="border-0 py-2" style="font-weight:700;color:#64748b"><?= strtoupper(lang('Common.status')) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($users as $u): ?>
                        <tr>
                            <td><?= esc($u['prenom'].' '.$u['nom']) ?></td>
                            <td class="text-muted"><?= esc($u['email']) ?></td>
                            <td>
                                <span style="background:#f1f5f9;padding:2px 8px;border-radius:6px;font-size:.75rem;font-weight:600"><?= $u['role'] ?></span>
                            </td>
                            <td>
                                <span style="background:<?= $u['actif'] ? '#d1fae5' : '#fee2e2' ?>;color:<?= $u['actif'] ? '#059669' : '#dc2626' ?>;padding:2px 8px;border-radius:6px;font-size:.75rem;font-weight:600">
                                    <?= $u['actif'] ? lang('SuperAdmin.active') : lang('SuperAdmin.inactive') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Stats rapides -->
            <div class="info-card">
                <h6 class="fw-700 mb-3" style="font-weight:700"><i class="bi bi-bar-chart me-2 text-primary"></i><?= lang('SuperAdmin.stats_section') ?></h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;text-align:center">
                            <div style="font-size:1.6rem;font-weight:800;color:#1a56db"><?= $contacts ?></div>
                            <div style="font-size:.75rem;color:#94a3b8;font-weight:600">Contacts</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;text-align:center">
                            <div style="font-size:1.6rem;font-weight:800;color:#7c3aed"><?= $leads ?></div>
                            <div style="font-size:.75rem;color:#94a3b8;font-weight:600">Leads</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;text-align:center">
                            <div style="font-size:1.6rem;font-weight:800;color:#059669"><?= count($users) ?></div>
                            <div style="font-size:.75rem;color:#94a3b8;font-weight:600"><?= lang('SuperAdmin.users_col') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abonnement -->
            <div class="info-card">
                <h6 class="fw-700 mb-3" style="font-weight:700"><i class="bi bi-credit-card me-2 text-primary"></i><?= lang('SuperAdmin.subscription_section') ?></h6>
                <form action="/superadmin/tenants/subscription/<?= $tenant['id'] ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.plan_label') ?></label>
                        <select name="plan" class="form-select form-select-sm" style="border-radius:8px">
                            <?php if (!empty($plans)): ?>
                                <?php foreach($plans as $pl): ?>
                                <option value="<?= esc($pl['slug']) ?>" <?= $tenant['plan'] === $pl['slug'] ? 'selected' : '' ?>>
                                    <?= esc($pl['nom']) ?> — <?= number_format($pl['prix_mensuel'],0,',',' ') ?> DA/mois
                                </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="starter" <?= $tenant['plan']==='starter'?'selected':'' ?>>Starter</option>
                                <option value="pro" <?= $tenant['plan']==='pro'?'selected':'' ?>>Pro</option>
                                <option value="enterprise" <?= $tenant['plan']==='enterprise'?'selected':'' ?>>Enterprise</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600"><?= lang('SuperAdmin.expire_date') ?></label>
                        <input type="date" name="expire_le" class="form-control form-control-sm" style="border-radius:8px"
                               value="<?= esc($tenant['expire_le']??'') ?>">
                    </div>
                    <button type="submit" class="btn btn-sm w-100" style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;border:none;border-radius:8px;font-weight:600">
                        <?= lang('SuperAdmin.update_btn') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
