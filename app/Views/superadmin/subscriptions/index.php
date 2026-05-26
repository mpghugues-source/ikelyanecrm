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
        .stat-card { background:#fff;border-radius:16px;padding:22px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0; }
        .table-card { background:#fff;border-radius:16px;padding:24px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0; }
        .badge-plan { padding:4px 12px;border-radius:50px;font-size:.75rem;font-weight:700; }
        .badge-gratuit { background:#f1f5f9;color:#64748b; }
        .badge-basic { background:#dbeafe;color:#1a56db; }
        .badge-premium { background:linear-gradient(135deg,#ede9fe,#fce7f3);color:#7c3aed; }
    </style>
</head>
<body>
<?= view('superadmin/partials/sidebar', ['activeNav' => 'subscriptions']) ?>
<div class="sa-main">
    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 rounded-3 mb-3"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <h4 class="mb-0" style="font-weight:800"><?= lang('SuperAdmin.subscriptions_title') ?></h4>
        <small class="text-muted"><?= lang('SuperAdmin.subscriptions_subtitle') ?></small>
    </div>

    <!-- Résumé -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card text-center">
                <div style="font-size:1.8rem;font-weight:800;color:#64748b"><?= $stats['starter'] ?></div>
                <div style="font-size:.8rem;color:#94a3b8;font-weight:600">STARTER</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card text-center">
                <div style="font-size:1.8rem;font-weight:800;color:#1a56db"><?= $stats['pro'] ?></div>
                <div style="font-size:.8rem;color:#94a3b8;font-weight:600">PRO</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card text-center">
                <div style="font-size:1.8rem;font-weight:800;color:#7c3aed"><?= $stats['enterprise'] ?></div>
                <div style="font-size:.8rem;color:#94a3b8;font-weight:600">ENTERPRISE</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card text-center">
                <div style="font-size:1.8rem;font-weight:800;color:#ef4444"><?= $stats['expires_soon'] ?></div>
                <div style="font-size:.8rem;color:#94a3b8;font-weight:600"><?= strtoupper(lang('SuperAdmin.expires_soon_count')) ?></div>
            </div>
        </div>
    </div>

    <!-- Plans d'abonnement -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0" style="font-weight:700">Plans d'abonnement</h5>
        <a href="/superadmin/subscriptions/plans/create" class="btn btn-sm btn-primary" style="border-radius:10px">
            <i class="bi bi-plus-lg me-1"></i>Nouveau plan
        </a>
    </div>
    <?php if (!empty($plans)): ?>
    <div class="row g-3 mb-4">
        <?php foreach($plans as $pl):
            $featuresArr = [];
            if ($pl['features']) {
                $featuresArr = is_string($pl['features']) ? (json_decode($pl['features'], true) ?? []) : $pl['features'];
            }
        ?>
        <div class="col-md-4">
            <div class="stat-card h-100 d-flex flex-column" style="opacity:<?= $pl['is_active'] ? 1 : .55 ?>">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div style="font-size:1.1rem;font-weight:800;color:#0f172a"><?= esc($pl['nom']) ?></div>
                        <code style="font-size:.75rem;color:#94a3b8"><?= esc($pl['slug']) ?></code>
                    </div>
                    <span style="background:<?= $pl['is_active'] ? '#d1fae5' : '#fee2e2' ?>;color:<?= $pl['is_active'] ? '#059669' : '#dc2626' ?>;padding:2px 8px;border-radius:50px;font-size:.72rem;font-weight:700">
                        <?= $pl['is_active'] ? 'Actif' : 'Inactif' ?>
                    </span>
                </div>
                <div class="mb-3">
                    <div style="font-size:1.4rem;font-weight:800;color:#1a56db"><?= number_format($pl['prix_mensuel'],0,',',' ') ?> DA<small style="font-size:.75rem;font-weight:400;color:#64748b">/mois</small></div>
                    <div style="font-size:.85rem;color:#64748b"><?= number_format($pl['prix_annuel'],0,',',' ') ?> DA / an</div>
                </div>
                <div class="mb-3 small text-muted">
                    <div><i class="bi bi-person-badge me-1"></i>Max utilisateurs : <strong><?= $pl['max_medecins'] ?: 'Illimité' ?></strong></div>
                    <div><i class="bi bi-people me-1"></i>Max contacts : <strong><?= $pl['max_patients'] ?: 'Illimité' ?></strong></div>
                </div>
                <?php if (!empty($featuresArr)): ?>
                <ul class="small text-muted list-unstyled mb-3 flex-fill">
                    <?php foreach($featuresArr as $f): ?>
                    <li><i class="bi bi-check2 text-success me-1"></i><?= esc($f) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <div class="d-flex gap-2 mt-auto pt-2 border-top">
                    <a href="/superadmin/subscriptions/plans/edit/<?= $pl['id'] ?>" class="btn btn-sm btn-light border flex-fill" style="border-radius:8px;font-size:.8rem">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <a href="/superadmin/subscriptions/plans/toggle/<?= $pl['id'] ?>" class="btn btn-sm btn-light border" style="border-radius:8px;font-size:.8rem" title="<?= $pl['is_active'] ? 'Désactiver' : 'Activer' ?>">
                        <i class="bi bi-<?= $pl['is_active'] ? 'pause' : 'play' ?>-circle"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Tableau des tenants -->
    <h5 class="mb-3" style="font-weight:700">Organisations abonnées</h5>
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size:.88rem">
                <thead style="background:#f8fafc">
                    <tr>
                        <th class="border-0 py-3 ps-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.clinic')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.plan')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.expiration')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.users_col')) ?></th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem">CONTACTS</th>
                        <th class="border-0 py-3" style="font-weight:700;color:#64748b;font-size:.8rem"><?= strtoupper(lang('SuperAdmin.action')) ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($tenants as $t): ?>
                <?php
                    $expireSoon = false;
                    $expireLabel = '—';
                    if ($t['expire_le']) {
                        $diff = (new DateTime($t['expire_le']))->diff(new DateTime())->days;
                        $expireSoon = $diff <= 30 && $t['actif'];
                        $expireLabel = date('d/m/Y', strtotime($t['expire_le']));
                    }
                ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,<?= esc($t['couleur']??'#1a56db') ?>,#7c3aed);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;margin-right:8px;flex-shrink:0">
                                <?= strtoupper(substr($t['nom'],0,1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:600"><?= esc($t['nom']) ?></div>
                                <div class="text-muted" style="font-size:.75rem"><?= esc($t['ville']??'') ?></div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-plan badge-<?= $t['plan'] ?>"><?= ucfirst($t['plan']) ?></span></td>
                    <td style="font-size:.85rem">
                        <span style="color:<?= $expireSoon ? '#ef4444' : '#374151' ?>;font-weight:<?= $expireSoon ? '700' : '400' ?>">
                            <?= $expireLabel ?>
                            <?php if($expireSoon): ?><br><small style="color:#ef4444">⚠ <?= lang('SuperAdmin.expires_soon') ?></small><?php endif; ?>
                        </span>
                    </td>
                    <td style="text-align:center;font-weight:600"><?= $t['nb_users'] ?></td>
                    <td style="text-align:center;font-weight:600"><?= $t['nb_contacts'] ?></td>
                    <td>
                        <a href="/superadmin/tenants/view/<?= $t['id'] ?>" class="btn btn-sm btn-light border" style="border-radius:8px;font-size:.8rem">
                            <i class="bi bi-pencil me-1"></i><?= lang('SuperAdmin.manage_btn') ?>
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
</body>
</html>
