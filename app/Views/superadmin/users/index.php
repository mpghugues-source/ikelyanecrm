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
        .table-card { background:#fff;border-radius:16px;padding:24px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0; }
        .stat-pill { display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:50px;font-size:.82rem;font-weight:700; }
    </style>
</head>
<body>
<?= view('superadmin/partials/sidebar', ['activeNav' => 'users']) ?>
<div class="sa-main">
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 rounded-3 mb-3"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0" style="font-weight:800"><?= lang('SuperAdmin.users_title') ?></h4>
            <small class="text-muted"><?= $stats['total'] ?> <?= lang('SuperAdmin.users_total') ?></small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="stat-pill" style="background:#dbeafe;color:#1a56db"><i class="bi bi-shield-check"></i><?= $stats['admin'] ?> Admin</span>
            <span class="stat-pill" style="background:#ede9fe;color:#7c3aed"><i class="bi bi-person-badge"></i><?= $stats['medecin'] ?> <?= lang('SuperAdmin.active_doctors') ?></span>
            <span class="stat-pill" style="background:#d1fae5;color:#059669"><i class="bi bi-person"></i><?= $stats['patient'] ?> <?= lang('SuperAdmin.patients_col') ?></span>
        </div>
    </div>

    <div class="table-card">
        <div class="mb-3">
            <input type="search" id="searchInput" class="form-control" style="border-radius:10px;max-width:320px" placeholder="<?= lang('SuperAdmin.search_user') ?>">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:.88rem" id="usersTable">
                <thead style="background:#f8fafc">
                    <tr>
                        <th class="border-0 py-3 ps-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.user_col')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('Common.email')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.role_col')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.clinic_col')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('Common.status')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.action')) ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($users as $u): ?>
                <?php
                    $roleColors = ['admin'=>'#dbeafe,#1a56db','medecin'=>'#ede9fe,#7c3aed','secretaire'=>'#fef3c7,#d97706','patient'=>'#d1fae5,#059669'];
                    [$bg,$fg] = explode(',', $roleColors[$u['role']] ?? '#f1f5f9,#64748b');
                ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,<?= $bg ?>,<?= $fg ?>);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                                <?= strtoupper(substr($u['prenom'],0,1).substr($u['nom'],0,1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:600"><?= esc($u['prenom'].' '.$u['nom']) ?></div>
                                <div class="text-muted" style="font-size:.75rem"><?= esc($u['telephone'] ?? '') ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted" style="font-size:.85rem"><?= esc($u['email']) ?></td>
                    <td>
                        <span style="background:<?= $bg ?>;color:<?= $fg ?>;padding:3px 10px;border-radius:50px;font-size:.75rem;font-weight:700">
                            <?= ucfirst($u['role']) ?>
                        </span>
                    </td>
                    <td style="font-size:.85rem">
                        <div style="font-weight:600"><?= esc($u['tenant_nom'] ?? '—') ?></div>
                        <div class="text-muted" style="font-size:.75rem"><?= esc($u['tenant_ville'] ?? '') ?></div>
                    </td>
                    <td>
                        <?php if($u['actif']): ?>
                        <span style="background:#d1fae5;color:#059669;padding:3px 10px;border-radius:50px;font-size:.75rem;font-weight:700"><?= lang('SuperAdmin.active') ?></span>
                        <?php else: ?>
                        <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:50px;font-size:.75rem;font-weight:700"><?= lang('SuperAdmin.inactive') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/superadmin/users/toggle/<?= $u['id'] ?>" class="btn btn-sm border"
                           style="border-radius:8px;background:<?= $u['actif'] ? '#fee2e2' : '#d1fae5' ?>;color:<?= $u['actif'] ? '#dc2626' : '#059669' ?>;border-color:transparent;font-size:.8rem"
                           onclick="return confirm('<?= lang('SuperAdmin.confirm') ?>')">
                            <i class="bi bi-<?= $u['actif'] ? 'pause-circle' : 'play-circle' ?>"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>
