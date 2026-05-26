<?php
$role    = session()->get('role');
$current = current_url(true)->getPath();
\Config\Services::language()->setLocale(session()->get('locale') ?? 'fr');
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= $role === 'super_admin' ? '/superadmin/dashboard' : '/admin/dashboard' ?>" class="brand-link text-decoration-none">
            <div class="brand-logo">
                <i class="bi bi-diagram-3-fill text-white fs-5"></i>
            </div>
            <div class="brand-text">
                <span class="brand-name">IkelyaneCRM</span>
                <small class="brand-sub d-block" style="color:rgba(165,180,252,.7);font-size:.7rem">
                    <?= esc(session()->get('tenant_nom') ?? 'CRM & ERP') ?>
                </small>
            </div>
        </a>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            <?php if (session()->get('avatar')): ?>
                <img src="/<?= esc(session()->get('avatar')) ?>" alt="Avatar" class="rounded-circle" width="38" height="38">
            <?php else: ?>
                <div class="avatar-initials"><?= strtoupper(substr(session()->get('prenom','?'),0,1) . substr(session()->get('nom',''),0,1)) ?></div>
            <?php endif; ?>
        </div>
        <div class="user-info ms-2">
            <div class="user-name"><?= esc(session()->get('prenom') . ' ' . session()->get('nom')) ?></div>
            <span class="role-badge">
                <?php
                $locale = session()->get('locale') ?? 'fr';
                $roleLabels = [
                    'fr' => ['super_admin'=>'Super Admin','admin'=>'Administrateur','manager'=>'Manager','commercial'=>'Commercial','support'=>'Support'],
                    'en' => ['super_admin'=>'Super Admin','admin'=>'Administrator','manager'=>'Manager','commercial'=>'Sales','support'=>'Support'],
                ];
                echo $roleLabels[$locale][$role] ?? ucfirst($role);
                ?>
            </span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if ($role === 'super_admin'): ?>
        <!-- ── SUPER ADMIN ──────────────────────────────── -->
        <div class="nav-section-title"><?= lang('Nav.superadmin') ?></div>
        <a href="/superadmin/dashboard" class="nav-link <?= str_contains($current, '/superadmin/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i><span><?= lang('Nav.dashboard') ?></span>
        </a>
        <a href="/superadmin/tenants" class="nav-link <?= str_contains($current, '/superadmin/tenants') ? 'active' : '' ?>">
            <i class="bi bi-building"></i><span><?= lang('Nav.tenants') ?></span>
        </a>
        <a href="/superadmin/users" class="nav-link <?= str_contains($current, '/superadmin/users') ? 'active' : '' ?>">
            <i class="bi bi-people"></i><span><?= lang('Nav.users') ?></span>
        </a>
        <a href="/superadmin/subscriptions" class="nav-link <?= str_contains($current, '/superadmin/subscriptions') ? 'active' : '' ?>">
            <i class="bi bi-credit-card"></i><span><?= lang('Nav.subscriptions') ?></span>
        </a>
        <a href="/superadmin/stats" class="nav-link <?= str_contains($current, '/superadmin/stats') ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i><span><?= lang('Nav.statistics') ?></span>
        </a>
        <a href="/superadmin/settings" class="nav-link <?= str_contains($current, '/superadmin/settings') ? 'active' : '' ?>">
            <i class="bi bi-gear"></i><span><?= lang('Nav.settings') ?></span>
        </a>

        <?php else: ?>
        <!-- ── PRINCIPAL ────────────────────────────────── -->
        <div class="nav-section-title"><?= lang('Nav.principal') ?></div>
        <a href="/admin/dashboard" class="nav-link <?= str_contains($current, '/admin/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i><span><?= lang('Nav.dashboard') ?></span>
        </a>
        <?php if (in_array($role, ['admin','manager'])): ?>
        <a href="/admin/users" class="nav-link <?= str_contains($current, '/admin/users') ? 'active' : '' ?>">
            <i class="bi bi-person-gear"></i><span><?= lang('Nav.users') ?></span>
        </a>
        <a href="/admin/reports" class="nav-link <?= str_contains($current, '/admin/reports') ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i><span><?= lang('Nav.reports') ?></span>
        </a>
        <?php endif; ?>

        <!-- ── CRM ─────────────────────────────────────── -->
        <div class="nav-section-title" style="color:rgba(167,139,250,.7);margin-top:4px">
            <i class="bi bi-people-fill" style="font-size:.65rem;margin-right:4px"></i><?= lang('Nav.crm_section') ?>
        </div>
        <a href="/crm" class="nav-link <?= ($current === '/crm' || $current === '/crm/') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i><span><?= lang('Nav.crm') ?></span>
        </a>
        <a href="/crm/contacts" class="nav-link <?= str_contains($current, '/crm/contacts') ? 'active' : '' ?>">
            <i class="bi bi-person-lines-fill"></i><span><?= lang('Nav.crm_contacts') ?></span>
        </a>
        <a href="/crm/leads" class="nav-link <?= str_contains($current, '/crm/leads') ? 'active' : '' ?>">
            <i class="bi bi-funnel-fill"></i><span><?= lang('Nav.crm_leads') ?></span>
        </a>
        <a href="/crm/activities" class="nav-link <?= str_contains($current, '/crm/activities') ? 'active' : '' ?>">
            <i class="bi bi-lightning-charge-fill"></i><span><?= lang('Nav.crm_activities') ?></span>
        </a>
        <a href="/crm/cases" class="nav-link <?= str_contains($current, '/crm/cases') ? 'active' : '' ?>">
            <i class="bi bi-ticket-detailed-fill"></i><span><?= lang('Nav.crm_cases') ?></span>
        </a>
        <a href="/crm/campaigns" class="nav-link <?= str_contains($current, '/crm/campaigns') ? 'active' : '' ?>">
            <i class="bi bi-megaphone-fill"></i><span><?= lang('Nav.crm_campaigns') ?></span>
        </a>

        <!-- ── ERP ─────────────────────────────────────── -->
        <?php if (in_array($role, ['admin','manager'])): ?>
        <div class="nav-section-title" style="color:rgba(52,211,153,.7);margin-top:4px">
            <i class="bi bi-building-fill-gear" style="font-size:.65rem;margin-right:4px"></i><?= lang('Nav.erp_section') ?>
        </div>
        <a href="/erp" class="nav-link <?= ($current === '/erp' || $current === '/erp/') ? 'active' : '' ?>">
            <i class="bi bi-graph-up-arrow"></i><span><?= lang('Nav.erp') ?></span>
        </a>
        <a href="/erp/finance/transactions" class="nav-link <?= str_contains($current, '/erp/finance') ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i><span><?= lang('Nav.erp_finance') ?></span>
        </a>
        <a href="/erp/inventory" class="nav-link <?= str_contains($current, '/erp/inventory') ? 'active' : '' ?>">
            <i class="bi bi-boxes"></i><span><?= lang('Nav.erp_inventory') ?></span>
        </a>
        <a href="/erp/suppliers" class="nav-link <?= str_contains($current, '/erp/suppliers') ? 'active' : '' ?>">
            <i class="bi bi-truck"></i><span><?= lang('Nav.erp_suppliers') ?></span>
        </a>
        <a href="/erp/purchase-orders" class="nav-link <?= str_contains($current, '/erp/purchase-orders') ? 'active' : '' ?>">
            <i class="bi bi-cart-check-fill"></i><span><?= lang('Nav.erp_po') ?></span>
        </a>
        <a href="/erp/projects" class="nav-link <?= str_contains($current, '/erp/projects') ? 'active' : '' ?>">
            <i class="bi bi-kanban-fill"></i><span><?= lang('Nav.erp_projects') ?></span>
        </a>
        <?php endif; ?>

        <!-- ── COMPTE ───────────────────────────────────── -->
        <div class="nav-section-title" style="margin-top:4px"><?= lang('Nav.account') ?></div>
        <a href="/admin/abonnement" class="nav-link <?= str_contains($current, '/admin/abonnement') ? 'active' : '' ?>">
            <?php if ($role === 'admin'):
                $_tExp = \Config\Database::connect()->table('tenants')->select('expire_le')->where('id', session()->get('tenant_id'))->get()->getRowArray();
                $_days = $_tExp && $_tExp['expire_le'] ? (int)ceil((strtotime($_tExp['expire_le']) - time()) / 86400) : null;
            endif; ?>
            <i class="bi bi-credit-card<?= (isset($_days) && $_days !== null && $_days <= 7) ? '-fill text-warning' : '' ?>"></i>
            <span><?= lang('Nav.subscription') ?><?= (isset($_days) && $_days !== null && $_days <= 7) ? ' <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">' . ($_days <= 0 ? '!' : $_days.'j') . '</span>' : '' ?></span>
        </a>
        <a href="/admin/settings" class="nav-link <?= str_contains($current, '/admin/settings') ? 'active' : '' ?>">
            <i class="bi bi-gear-fill"></i><span><?= lang('Nav.settings') ?></span>
        </a>
        <a href="/messages" class="nav-link <?= str_contains($current, '/messages') ? 'active' : '' ?>">
            <i class="bi bi-envelope-fill"></i><span><?= lang('Nav.messages') ?></span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="/profile" class="nav-link <?= str_contains($current, '/profile') ? 'active' : '' ?>">
            <i class="bi bi-person-circle"></i><span><?= lang('Nav.profile') ?></span>
        </a>
        <a href="/logout" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i><span><?= lang('Nav.logout') ?></span>
        </a>
    </div>
</aside>
