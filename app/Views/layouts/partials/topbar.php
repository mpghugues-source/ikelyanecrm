<?php $locale = session()->get('locale') ?? 'fr'; ?>
<header class="topbar">
    <button class="btn sidebar-toggle me-3" id="sidebarToggle">
        <i class="bi bi-list fs-5"></i>
    </button>

    <div class="topbar-breadcrumb">
        <h5 class="mb-0 fw-semibold"><?= esc($title ?? 'IkelyaneMed') ?></h5>
    </div>

    <div class="topbar-actions ms-auto d-flex align-items-center gap-2">
        <!-- Recherche rapide -->
        <div class="d-none d-md-block">
            <div class="input-group input-group-sm" style="width:220px">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control bg-light border-0" placeholder="<?= lang('Common.search') ?>...">
            </div>
        </div>

        <!-- Date du jour -->
        <span class="badge bg-light text-dark d-none d-lg-inline-block">
            <i class="bi bi-calendar3 me-1"></i>
            <?= date('d/m/Y') ?>
        </span>

        <!-- Sélecteur de langue -->
        <div class="dropdown">
            <button class="btn btn-light px-2 py-1 rounded-3 d-flex align-items-center gap-1" data-bs-toggle="dropdown" title="Language / Langue">
                <?php if ($locale === 'en'): ?>
                    <span style="font-size:1.1rem">🇬🇧</span>
                    <span class="d-none d-md-inline small fw-medium">EN</span>
                <?php else: ?>
                    <span style="font-size:1.1rem">🇫🇷</span>
                    <span class="d-none d-md-inline small fw-medium">FR</span>
                <?php endif; ?>
                <i class="bi bi-chevron-down small text-muted"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-1">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 <?= $locale === 'fr' ? 'active' : '' ?>" href="/lang/fr">
                        <span style="font-size:1.1rem">🇫🇷</span> Français
                        <?php if ($locale === 'fr'): ?><i class="bi bi-check ms-auto"></i><?php endif; ?>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 <?= $locale === 'en' ? 'active' : '' ?>" href="/lang/en">
                        <span style="font-size:1.1rem">🇬🇧</span> English
                        <?php if ($locale === 'en'): ?><i class="bi bi-check ms-auto"></i><?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Notifications -->
        <div class="dropdown">
            <button class="btn btn-light position-relative rounded-3 px-2 py-1" id="notifBtn" data-bs-toggle="dropdown" onclick="loadNotifications()">
                <i class="bi bi-bell fs-5"></i>
                <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;display:none">0</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-0" style="width:340px;overflow:hidden" id="notifDropdown">
                <div class="d-flex justify-content-between align-items-center px-3 py-2" style="background:#f8fafc;border-bottom:1px solid #e2e8f0">
                    <span class="fw-bold" style="font-size:.9rem"><?= lang('Nav.notifications', [], null, 'Notifications') ?></span>
                    <button class="btn btn-link btn-sm text-muted p-0" onclick="markAllRead()" style="font-size:.8rem"><?= $locale === 'en' ? 'Mark all read' : 'Tout marquer lu' ?></button>
                </div>
                <div id="notifList" style="max-height:360px;overflow-y:auto">
                    <div class="text-center text-muted py-4" style="font-size:.85rem">
                        <i class="bi bi-bell-slash" style="font-size:1.5rem;opacity:.4;display:block;margin-bottom:8px"></i>
                        <?= $locale === 'en' ? 'No notifications' : 'Aucune notification' ?>
                    </div>
                </div>
                <div style="border-top:1px solid #e2e8f0;padding:8px 12px;text-align:center">
                    <a href="/notifications" style="font-size:.82rem;color:#1a56db;text-decoration:none;font-weight:600">
                        <?= $locale === 'en' ? 'View all notifications' : 'Voir toutes les notifications' ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profil -->
        <div class="dropdown">
            <button class="btn btn-light d-flex align-items-center gap-2 py-1 px-2 rounded-pill" data-bs-toggle="dropdown">
                <div class="avatar-sm">
                    <?= strtoupper(substr(session()->get('prenom','?'),0,1) . substr(session()->get('nom',''),0,1)) ?>
                </div>
                <span class="d-none d-md-inline fw-medium small"><?= esc(session()->get('prenom')) ?></span>
                <i class="bi bi-chevron-down small text-muted"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-1">
                <li><h6 class="dropdown-header"><?= esc(session()->get('prenom') . ' ' . session()->get('nom')) ?></h6></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="bi bi-person me-2"></i><?= $locale === 'en' ? 'My profile' : 'Mon profil' ?></a></li>
                <?php if (in_array(session()->get('role'), ['admin','super_admin'])): ?>
                <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>"><i class="bi bi-gear me-2"></i><?= lang('Nav.settings') ?></a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i><?= lang('Nav.logout') ?></a></li>
            </ul>
        </div>
    </div>
</header>
