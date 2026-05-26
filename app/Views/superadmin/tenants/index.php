<!DOCTYPE html>
<html lang="<?= session()->get('locale') ?? 'fr' ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family:'Inter',sans-serif; }
        body { background:#f1f5f9; }
        .sa-sidebar { width:260px;background:#0f172a;min-height:100vh;position:fixed;left:0;top:0;z-index:100;padding:24px 0; }
        .sa-brand { padding:0 24px 24px;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px; }
        .sa-brand-name { font-size:1.3rem;font-weight:800;color:#fff; }
        .sa-brand-badge { font-size:.7rem;background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;padding:2px 10px;border-radius:50px;font-weight:700; }
        .sa-nav-link { display:flex;align-items:center;gap:12px;padding:12px 24px;color:#94a3b8;text-decoration:none;font-size:.9rem;font-weight:500;transition:all .2s;border-left:3px solid transparent; }
        .sa-nav-link:hover,.sa-nav-link.active { color:#fff;background:rgba(255,255,255,.05);border-left-color:#1a56db; }
        .sa-nav-link i { font-size:1.1rem;width:20px;text-align:center; }
        .sa-main { margin-left:260px;padding:32px; }
        .table-card { background:#fff;border-radius:16px;padding:24px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0; }
        .badge-plan { padding:4px 12px;border-radius:50px;font-size:.75rem;font-weight:700; }
        .badge-gratuit { background:#f1f5f9;color:#64748b; }
        .badge-basic { background:#dbeafe;color:#1a56db; }
        .badge-premium { background:linear-gradient(135deg,#ede9fe,#fce7f3);color:#7c3aed; }
    </style>
</head>
<body>
<?= view('superadmin/partials/sidebar', ['activeNav' => 'tenants']) ?>
<div class="sa-main">
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 rounded-3 mb-3"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-800" style="font-weight:800"><?= lang('SuperAdmin.clinics_title') ?></h4>
            <small class="text-muted"><?= count($tenants) ?> <?= lang('SuperAdmin.clinics_count') ?></small>
        </div>
        <a href="/superadmin/tenants/create" class="btn" style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;border:none;border-radius:10px;font-weight:600;padding:10px 20px">
            <i class="bi bi-building-add me-2"></i><?= lang('SuperAdmin.new_establishment') ?>
        </a>
    </div>

    <div class="table-card">
        <div class="mb-3">
            <input type="search" id="searchInput" class="form-control" style="border-radius:10px;max-width:300px" placeholder="<?= lang('SuperAdmin.search_clinic') ?>">
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:.9rem" id="tenantsTable">
                <thead style="background:#f8fafc">
                    <tr>
                        <th class="border-0 py-3 ps-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.clinic')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.contact')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.plan')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.expiration')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.users_col')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem">CONTACTS</th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('Common.status')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('Common.actions')) ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($tenants as $t): ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,<?= esc($t['couleur']??'#1a56db') ?>,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0">
                                <?= strtoupper(substr($t['nom'],0,1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:600"><?= esc($t['nom']) ?></div>
                                <div class="text-muted" style="font-size:.78rem"><?= esc($t['ville']??'') ?>, <?= esc($t['pays']??'') ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:.85rem"><?= esc($t['email']??'—') ?></div>
                        <div class="text-muted" style="font-size:.78rem"><?= esc($t['telephone']??'') ?></div>
                    </td>
                    <td><span class="badge-plan badge-<?= $t['plan'] ?>"><?= ucfirst($t['plan']) ?></span></td>
                    <td style="font-size:.85rem">
                        <?php if($t['expire_le']): ?>
                            <?php $diff = (new DateTime($t['expire_le']))->diff(new DateTime())->days; ?>
                            <span style="color:<?= $diff < 7 ? '#ef4444' : '#374151' ?>;font-weight:<?= $diff < 7 ? '700' : '400' ?>">
                                <?= date('d/m/Y', strtotime($t['expire_le'])) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-weight:600;text-align:center"><?= $t['nb_users'] ?></td>
                    <td style="font-weight:600;text-align:center"><?= $t['nb_contacts'] ?></td>
                    <td>
                        <?php if($t['actif']): ?>
                        <span style="background:#d1fae5;color:#059669;padding:3px 10px;border-radius:50px;font-size:.75rem;font-weight:700"><?= lang('SuperAdmin.active') ?></span>
                        <?php else: ?>
                        <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:50px;font-size:.75rem;font-weight:700"><?= lang('SuperAdmin.inactive') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="/superadmin/tenants/view/<?= $t['id'] ?>" class="btn btn-sm btn-light border" title="<?= lang('SuperAdmin.details') ?>" style="border-radius:8px"><i class="bi bi-eye"></i></a>
                            <a href="/superadmin/tenants/edit/<?= $t['id'] ?>" class="btn btn-sm btn-light border" title="<?= lang('SuperAdmin.edit_establishment') ?>" style="border-radius:8px"><i class="bi bi-pencil"></i></a>
                            <a href="/superadmin/tenants/toggle/<?= $t['id'] ?>" class="btn btn-sm border"
                               title="<?= $t['actif'] ? lang('SuperAdmin.deactivate') : lang('SuperAdmin.activate') ?>"
                               style="border-radius:8px;background:<?= $t['actif'] ? '#fee2e2' : '#d1fae5' ?>;color:<?= $t['actif'] ? '#dc2626' : '#059669' ?>;border-color:transparent"
                               onclick="return confirm('<?= lang('SuperAdmin.confirm') ?>')">
                                <i class="bi bi-<?= $t['actif'] ? 'pause-circle' : 'play-circle' ?>"></i>
                            </a>
                        </div>
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
    document.querySelectorAll('#tenantsTable tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>
