/* ═══════════════════════════════════════════════════════════
   IKELYANEMED — Main JavaScript
   ═══════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar Toggle ──────────────────────────────────────
    const sidebar   = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const main      = document.getElementById('mainContent');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                if (sidebar.classList.contains('collapsed')) {
                    sidebar.style.width = '70px';
                    if (main) main.style.marginLeft = '70px';
                    sidebar.querySelectorAll('.nav-link span, .brand-text, .nav-section-title, .sidebar-user .user-info').forEach(el => {
                        el.style.display = 'none';
                    });
                } else {
                    sidebar.style.width = '';
                    if (main) main.style.marginLeft = '';
                    sidebar.querySelectorAll('.nav-link span, .brand-text, .nav-section-title, .sidebar-user .user-info').forEach(el => {
                        el.style.display = '';
                    });
                }
            }
        });
    }

    // Click outside sidebar on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 768 && sidebar && !sidebar.contains(e.target) && toggleBtn && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    });

    // ── Auto-dismiss alerts ─────────────────────────────────
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // ── Confirm dialogs ─────────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            const msg = el.getAttribute('data-confirm') || 'Confirmer cette action ?';
            if (! confirm(msg)) {
                e.preventDefault();
                return false;
            }
        });
    });

});
