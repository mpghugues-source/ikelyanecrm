<!DOCTYPE html>
<html lang="<?= session()->get('locale') ?? 'fr' ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Super Admin') ?> — IkelyaneCRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Segoe UI', system-ui, sans-serif; box-sizing: border-box; }
        body { background: #0f0a1e; margin: 0; }
        .sa-sidebar { width: 240px; background: #1e1b2e; min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 100; display:flex;flex-direction:column; }
        .sa-brand { padding: 20px 16px; border-bottom: 1px solid rgba(167,139,250,.15); }
        .sa-brand-logo { width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#06b6d4);border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(124,58,237,.4); }
        .sa-brand-name { font-size: 1rem; font-weight: 800; color: #fff; }
        .sa-brand-sub { font-size: .7rem; color: rgba(167,139,252,.6); }
        .sa-nav-link { display:flex;align-items:center;gap:10px;padding:10px 12px;margin:2px 8px;border-radius:8px;color:#a5b4fc;text-decoration:none;font-size:.875rem;transition:all .2s; }
        .sa-nav-link:hover, .sa-nav-link.active { color:#fff;background:rgba(124,58,237,.25); }
        .sa-nav-link.active { background:linear-gradient(135deg,#7c3aed,#8b5cf6);box-shadow:0 2px 8px rgba(124,58,237,.35); }
        .sa-section { color:rgba(167,139,252,.45);font-size:.65rem;font-weight:700;letter-spacing:1px;padding:12px 20px 4px;text-transform:uppercase; }
        .sa-main { margin-left: 240px; padding: 24px; }
        .sa-topbar { background:rgba(255,255,255,.04);backdrop-filter:blur(10px);border:1px solid rgba(167,139,252,.1);border-radius:12px;padding:14px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between; }
        .stat-card { background:rgba(255,255,255,.04);border:1px solid rgba(167,139,252,.1);border-radius:14px;padding:20px; }
        .stat-value { font-size:1.8rem;font-weight:800;color:#fff;line-height:1; }
        .stat-label { font-size:.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px; }
        .stat-icon { width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem; }
        .si-purple{background:rgba(124,58,237,.2);color:#a78bfa;} .si-teal{background:rgba(6,182,212,.2);color:#67e8f9;}
        .si-green{background:rgba(52,211,153,.2);color:#6ee7b7;} .si-orange{background:rgba(251,146,60,.2);color:#fdba74;}
        .si-red{background:rgba(248,113,113,.2);color:#fca5a5;}
        .table-card { background:rgba(255,255,255,.04);border:1px solid rgba(167,139,252,.1);border-radius:14px;padding:0;overflow:hidden; }
        .tc-header { padding:16px 20px;border-bottom:1px solid rgba(167,139,252,.1);display:flex;align-items:center;justify-content:space-between; }
        .tc-title { font-size:.9rem;font-weight:700;color:#e2e8f0; }
        .dark-table { color:#cbd5e1; }
        .dark-table thead th { background:rgba(255,255,255,.03);color:#94a3b8;font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;padding:10px 16px;border-color:rgba(167,139,252,.1);font-weight:600; }
        .dark-table tbody td { padding:12px 16px;border-color:rgba(167,139,252,.06);vertical-align:middle;font-size:.85rem; }
        .dark-table tbody tr:hover td { background:rgba(124,58,237,.06); }
        .bp-starter{background:rgba(124,58,237,.2);color:#c4b5fd;padding:3px 10px;border-radius:50px;font-size:.72rem;font-weight:700;}
        .bp-pro{background:rgba(6,182,212,.2);color:#67e8f9;padding:3px 10px;border-radius:50px;font-size:.72rem;font-weight:700;}
        .bp-enterprise{background:rgba(251,191,36,.2);color:#fcd34d;padding:3px 10px;border-radius:50px;font-size:.72rem;font-weight:700;}
        .bs-active{background:rgba(52,211,153,.2);color:#6ee7b7;padding:3px 10px;border-radius:50px;font-size:.72rem;font-weight:600;}
        .bs-inactive{background:rgba(248,113,113,.2);color:#fca5a5;padding:3px 10px;border-radius:50px;font-size:.72rem;font-weight:600;}
        .section-title { color:#a78bfa;font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px; }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sa-sidebar">
    <div class="sa-brand d-flex align-items-center gap-3">
        <div class="sa-brand-logo"><i class="bi bi-diagram-3-fill text-white fs-5"></i></div>
        <div>
            <div class="sa-brand-name">IkelyaneCRM</div>
            <div class="sa-brand-sub">Super Admin Panel</div>
        </div>
    </div>
    <nav style="padding:8px;flex:1">
        <div class="sa-section">Administration</div>
        <a href="/superadmin/dashboard" class="sa-nav-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="/superadmin/tenants"   class="sa-nav-link"><i class="bi bi-building"></i> Organisations</a>
        <a href="/superadmin/users"     class="sa-nav-link"><i class="bi bi-people"></i> Utilisateurs</a>
        <a href="/superadmin/subscriptions" class="sa-nav-link"><i class="bi bi-credit-card"></i> Abonnements</a>
        <a href="/superadmin/stats"     class="sa-nav-link"><i class="bi bi-graph-up"></i> Statistiques</a>
        <a href="/superadmin/settings"  class="sa-nav-link"><i class="bi bi-gear"></i> Paramètres</a>
    </nav>
    <div style="padding:8px;border-top:1px solid rgba(167,139,252,.1)">
        <a href="/logout" class="sa-nav-link" style="color:#f87171"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</div>

<!-- Main -->
<div class="sa-main">
    <div class="sa-topbar">
        <div>
            <div style="font-size:1.1rem;font-weight:700;color:#e2e8f0">Tableau de bord Super Admin</div>
            <div style="font-size:.8rem;color:#64748b"><?= date('l d F Y') ?></div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,#7c3aed,#06b6d4);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem">
                <?= strtoupper(substr(session()->get('prenom','S'),0,1).substr(session()->get('nom','A'),0,1)) ?>
            </div>
            <div style="font-size:.85rem;color:#cbd5e1"><?= esc(session()->get('prenom').' '.session()->get('nom')) ?></div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-2">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Organisations</div>
                        <div class="stat-value"><?= $stats['total_tenants'] ?></div>
                    </div>
                    <div class="stat-icon si-purple"><i class="bi bi-building"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Actives</div>
                        <div class="stat-value"><?= $stats['active_tenants'] ?></div>
                    </div>
                    <div class="stat-icon si-green"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Utilisateurs</div>
                        <div class="stat-value"><?= $stats['total_users'] ?></div>
                    </div>
                    <div class="stat-icon si-teal"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Plan Pro</div>
                        <div class="stat-value"><?= $stats['pro'] ?></div>
                    </div>
                    <div class="stat-icon si-teal"><i class="bi bi-star"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Enterprise</div>
                        <div class="stat-value"><?= $stats['enterprise'] ?></div>
                    </div>
                    <div class="stat-icon si-orange"><i class="bi bi-gem"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Expirent bientôt</div>
                        <div class="stat-value" style="color:#f87171"><?= $stats['expiring_soon'] ?></div>
                    </div>
                    <div class="stat-icon si-red"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers tenants -->
    <div class="table-card">
        <div class="tc-header">
            <div class="tc-title"><i class="bi bi-building me-2" style="color:#a78bfa"></i>Dernières organisations inscrites</div>
            <a href="/superadmin/tenants" class="btn btn-sm" style="background:rgba(124,58,237,.2);color:#c4b5fd;border:1px solid rgba(124,58,237,.3);border-radius:8px;font-size:.8rem">Voir tout</a>
        </div>
        <table class="table dark-table mb-0">
            <thead><tr>
                <th>Organisation</th><th>Slug</th><th>Plan</th><th>Utilisateurs</th><th>Expire le</th><th>Statut</th><th>Actions</th>
            </tr></thead>
            <tbody>
            <?php foreach ($tenants as $t): ?>
            <tr>
                <td>
                    <div style="font-weight:600;color:#e2e8f0"><?= esc($t['nom']) ?></div>
                    <div style="font-size:.75rem;color:#64748b"><?= esc($t['email'] ?? '') ?></div>
                </td>
                <td><code style="color:#67e8f9;font-size:.8rem"><?= esc($t['slug']) ?></code></td>
                <td>
                    <span class="bp-<?= $t['plan'] ?>"><?= strtoupper($t['plan']) ?></span>
                </td>
                <td style="color:#94a3b8"><?= $t['max_users'] ?> max</td>
                <td style="font-size:.82rem;color:<?= $t['expire_le'] && strtotime($t['expire_le']) < time() ? '#f87171' : '#94a3b8' ?>">
                    <?= $t['expire_le'] ? date('d/m/Y', strtotime($t['expire_le'])) : '∞' ?>
                </td>
                <td><span class="bs-<?= $t['actif'] ? 'active' : 'inactive' ?>"><?= $t['actif'] ? 'Actif' : 'Inactif' ?></span></td>
                <td>
                    <a href="/superadmin/tenants/<?= $t['id'] ?>/view" style="color:#a78bfa;font-size:.82rem;text-decoration:none"><i class="bi bi-eye"></i></a>
                    <a href="/superadmin/tenants/<?= $t['id'] ?>/toggle" style="color:<?= $t['actif'] ? '#f87171' : '#6ee7b7' ?>;font-size:.82rem;text-decoration:none;margin-left:8px">
                        <i class="bi bi-<?= $t['actif'] ? 'pause' : 'play' ?>-circle"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($tenants)): ?>
            <tr><td colspan="7" class="text-center" style="color:#64748b;padding:30px"><?= lang('Admin.no_orgs') ?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
