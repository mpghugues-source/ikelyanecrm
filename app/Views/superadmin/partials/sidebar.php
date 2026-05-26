<?php
/**
 * Superadmin sidebar — partial réutilisable
 * Usage : <?= $this->include('superadmin/partials/sidebar') ?>
 * Variable $activeNav attendue : 'dashboard'|'tenants'|'users'|'subscriptions'|'stats'|'settings'
 */
$activeNav = $activeNav ?? '';
$locale    = session()->get('locale') ?? 'fr';
?>
<div class="sa-sidebar">
    <div class="sa-brand">
        <div class="sa-brand-name">IkelyaneMed</div>
        <span class="sa-brand-badge">SUPER ADMIN</span>
    </div>

    <a href="/superadmin/dashboard"     class="sa-nav-link <?= $activeNav==='dashboard'     ? 'active':'' ?>"><i class="bi bi-speedometer2"></i><?= lang('SuperAdmin.nav_dashboard') ?></a>
    <a href="/superadmin/tenants"       class="sa-nav-link <?= $activeNav==='tenants'       ? 'active':'' ?>"><i class="bi bi-building"></i><?= lang('SuperAdmin.nav_clinics') ?></a>
    <a href="/superadmin/users"         class="sa-nav-link <?= $activeNav==='users'         ? 'active':'' ?>"><i class="bi bi-people"></i><?= lang('SuperAdmin.nav_users') ?></a>
    <a href="/superadmin/subscriptions" class="sa-nav-link <?= $activeNav==='subscriptions' ? 'active':'' ?>"><i class="bi bi-credit-card"></i><?= lang('SuperAdmin.nav_subscriptions') ?></a>
    <a href="/superadmin/stats"         class="sa-nav-link <?= $activeNav==='stats'         ? 'active':'' ?>"><i class="bi bi-graph-up"></i><?= lang('SuperAdmin.nav_stats') ?></a>
    <a href="/superadmin/settings"      class="sa-nav-link <?= $activeNav==='settings'      ? 'active':'' ?>"><i class="bi bi-gear"></i><?= lang('SuperAdmin.nav_settings') ?></a>

    <!-- Sélecteur de langue -->
    <div style="padding:16px 24px;border-top:1px solid rgba(255,255,255,.08);margin-top:8px">
        <div style="font-size:.72rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px">Langue / Language</div>
        <div class="d-flex gap-2">
            <a href="/lang/fr" style="display:flex;align-items:center;gap:5px;padding:5px 10px;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .2s;
                background:<?= $locale==='fr' ? '#1a56db' : 'rgba(255,255,255,.07)' ?>;
                color:<?= $locale==='fr' ? '#fff' : '#94a3b8' ?>;
                border:1px solid <?= $locale==='fr' ? '#1a56db' : 'rgba(255,255,255,.1)' ?>">
                🇫🇷 FR
            </a>
            <a href="/lang/en" style="display:flex;align-items:center;gap:5px;padding:5px 10px;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .2s;
                background:<?= $locale==='en' ? '#1a56db' : 'rgba(255,255,255,.07)' ?>;
                color:<?= $locale==='en' ? '#fff' : '#94a3b8' ?>;
                border:1px solid <?= $locale==='en' ? '#1a56db' : 'rgba(255,255,255,.1)' ?>">
                🇬🇧 EN
            </a>
        </div>
    </div>

    <div style="position:absolute;bottom:24px;left:0;right:0;padding:0 24px">
        <a href="/logout" class="sa-nav-link" style="color:#ef4444"><i class="bi bi-box-arrow-left"></i><?= lang('SuperAdmin.nav_logout') ?></a>
    </div>
</div>
