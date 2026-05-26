<!DOCTYPE html>
<html lang="<?= session()->get('locale') ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'IkelyaneCRM') ?> — IkelyaneCRM</title>
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
    <meta name="theme-color" content="#1a56db">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="IkelyaneCRM">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/app.css" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <?= view('layouts/partials/sidebar') ?>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Topbar -->
            <?= view('layouts/partials/topbar') ?>

            <!-- Page Content -->
            <div class="page-content">
                <!-- Bannière abonnement -->
                <?php
                $_role = session()->get('role');
                if (!in_array($_role, ['super_admin', 'patient', null])) :
                    $_tenantId = (int) session()->get('tenant_id');
                    $_db = \Config\Database::connect();
                    $_t  = $_db->table('tenants')->select('plan, expire_le')->where('id', $_tenantId)->get()->getRowArray();
                    if ($_t && $_t['expire_le'] && $_t['plan'] !== 'starter') :
                        $_daysLeft = (int) ceil((strtotime($_t['expire_le']) - time()) / 86400);
                        if ($_daysLeft <= 30) :
                ?>
                <?php if ($_daysLeft <= 0) : ?>
                <div class="alert alert-danger border-0 rounded-0 mb-0 d-flex align-items-center gap-3" style="border-left:4px solid #dc2626 !important;border-radius:0 !important;padding:12px 24px">
                    <i class="bi bi-exclamation-octagon-fill flex-shrink-0" style="font-size:1.2rem"></i>
                    <div class="flex-grow-1">
                        <strong>Abonnement expiré</strong> — Votre période de grâce prend fin bientôt. Renouvelez maintenant pour éviter l'interruption de service.
                    </div>
                    <a href="/abonnement/expire" class="btn btn-sm btn-danger fw-700 flex-shrink-0">Renouveler</a>
                </div>
                <?php elseif ($_daysLeft <= 7) : ?>
                <div class="alert alert-warning border-0 rounded-0 mb-0 d-flex align-items-center gap-3" style="border-left:4px solid #d97706 !important;border-radius:0 !important;padding:12px 24px;background:#fff7ed">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0" style="color:#d97706;font-size:1.2rem"></i>
                    <div class="flex-grow-1" style="color:#92400e">
                        <strong>Abonnement expire dans <?= $_daysLeft ?> jour<?= $_daysLeft > 1 ? 's' : '' ?></strong> — Renouvelez rapidement pour éviter l'interruption.
                    </div>
                    <a href="/abonnement/expire" class="btn btn-sm fw-700 flex-shrink-0" style="background:#d97706;color:#fff;border:none">Renouveler</a>
                </div>
                <?php else : ?>
                <div class="alert border-0 rounded-0 mb-0 d-flex align-items-center gap-3 alert-dismissible fade show" style="border-left:4px solid #1a56db !important;border-radius:0 !important;padding:12px 24px;background:#eff6ff">
                    <i class="bi bi-info-circle-fill flex-shrink-0" style="color:#1a56db;font-size:1.1rem"></i>
                    <div class="flex-grow-1" style="color:#1e40af;font-size:.9rem">
                        Votre abonnement <strong><?= ucfirst($_t['plan']) ?></strong> expire dans <strong><?= $_daysLeft ?> jours</strong>.
                        <a href="/abonnement/expire" style="color:#1a56db;font-weight:700;margin-left:8px">Renouveler →</a>
                    </div>
                    <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <?php endif; endif; endif; ?>

                <!-- Flash messages -->
                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= esc(session()->getFlashdata('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                <?php if ($errors = session()->getFlashdata('errors')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach ((array)$errors as $err): ?>
                        <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/app.js"></script>
    <!-- Notifications JS -->
    <script>
    function loadNotifications() {
        fetch('/api/notifications')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('notifBadge');
                const list  = document.getElementById('notifList');
                if (!badge || !list) return;

                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }

                if (!data.notifications || data.notifications.length === 0) {
                    list.innerHTML = '<div class="text-center text-muted py-4" style="font-size:.85rem"><i class="bi bi-bell-slash" style="font-size:1.5rem;opacity:.4;display:block;margin-bottom:8px"></i>Aucune nouvelle notification</div>';
                    return;
                }

                const colorMap = { primary:'#1a56db', success:'#059669', warning:'#d97706', danger:'#dc2626', info:'#0284c7' };
                list.innerHTML = data.notifications.map(n => `
                    <a href="/notifications/read/${n.id}" style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-bottom:1px solid #f1f5f9;text-decoration:none;background:${n.lu ? '#fff' : '#f0f7ff'}">
                        <div style="width:32px;height:32px;border-radius:50%;background:${colorMap[n.couleur]||'#1a56db'}20;color:${colorMap[n.couleur]||'#1a56db'};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.9rem">
                            <i class="bi ${n.icone}"></i>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="font-size:.85rem;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${n.titre}</div>
                            <div style="font-size:.78rem;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${n.message}</div>
                            <div style="font-size:.72rem;color:#94a3b8;margin-top:2px">${timeAgo(n.created_at)}</div>
                        </div>
                        ${!n.lu ? '<div style="width:7px;height:7px;border-radius:50%;background:#1a56db;flex-shrink:0;margin-top:4px"></div>' : ''}
                    </a>
                `).join('');
            })
            .catch(() => {});
    }

    function markAllRead() {
        fetch('/api/notifications/read-all', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','<?= csrf_header() ?>':'<?= csrf_hash() ?>'} })
            .then(() => {
                document.getElementById('notifBadge').style.display = 'none';
                loadNotifications();
            });
    }

    function timeAgo(dateStr) {
        const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
        if (diff < 60) return 'À l\'instant';
        if (diff < 3600) return Math.floor(diff/60) + ' min';
        if (diff < 86400) return Math.floor(diff/3600) + 'h';
        return Math.floor(diff/86400) + 'j';
    }

    // Charger le badge au démarrage
    fetch('/api/notifications').then(r=>r.json()).then(data => {
        const badge = document.getElementById('notifBadge');
        if (badge && data.count > 0) { badge.textContent = data.count; badge.style.display = ''; }
    }).catch(()=>{});
    </script>
    <?= $this->renderSection('scripts') ?>
    <!-- PWA Service Worker + Auto-Update -->
    <script>
    (function () {
        if (!('serviceWorker' in navigator)) return;

        let swReg;

        navigator.serviceWorker.register('/sw.js').then(reg => {
            swReg = reg;

            // Vérifier une mise à jour immédiatement au chargement
            reg.update();

            // Vérifier périodiquement (toutes les 60 secondes)
            setInterval(() => reg.update(), 60000);

            reg.addEventListener('updatefound', () => {
                const newWorker = reg.installing;
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        showUpdateBanner();
                    }
                });
            });
        }).catch(() => {});

        // Recharger la page quand le nouveau SW prend le contrôle
        let refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (!refreshing) { refreshing = true; window.location.reload(); }
        });

        function showUpdateBanner() {
            const bar = document.createElement('div');
            bar.id = 'pwa-update-bar';
            bar.style.cssText = 'position:fixed;bottom:0;left:0;right:0;z-index:9999;background:#1a56db;color:#fff;display:flex;align-items:center;justify-content:space-between;padding:12px 20px;font-size:.875rem;box-shadow:0 -2px 12px rgba(0,0,0,.2)';
            bar.innerHTML = '<span><i class="bi bi-arrow-clockwise me-2"></i>Une nouvelle version d\'IkelyaneCRM est disponible.</span>'
                + '<button onclick="applyUpdate()" style="background:#fff;color:#1a56db;border:none;border-radius:6px;padding:6px 16px;font-weight:600;cursor:pointer;margin-left:12px">Mettre à jour</button>';
            document.body.appendChild(bar);
        }

        window.applyUpdate = function () {
            if (swReg && swReg.waiting) {
                swReg.waiting.postMessage({ type: 'SKIP_WAITING' });
            }
        };
    })();
    </script>
</body>
</html>
