<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
        .chart-card { background:#fff;border-radius:16px;padding:24px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="sa-sidebar">
    <div class="sa-brand"><div class="sa-brand-name">IkelyaneMed</div><span class="sa-brand-badge">SUPER ADMIN</span></div>
    <a href="/superadmin/dashboard" class="sa-nav-link"><i class="bi bi-speedometer2"></i>Dashboard</a>
    <a href="/superadmin/tenants" class="sa-nav-link"><i class="bi bi-building"></i>Cliniques</a>
    <a href="/superadmin/users" class="sa-nav-link"><i class="bi bi-people"></i>Utilisateurs</a>
    <a href="/superadmin/subscriptions" class="sa-nav-link"><i class="bi bi-credit-card"></i>Abonnements</a>
    <a href="/superadmin/stats" class="sa-nav-link active"><i class="bi bi-graph-up"></i>Statistiques</a>
    <a href="/superadmin/settings" class="sa-nav-link"><i class="bi bi-gear"></i>Paramètres</a>
    <div style="position:absolute;bottom:24px;left:0;right:0;padding:0 24px">
        <a href="/logout" class="sa-nav-link" style="color:#ef4444"><i class="bi bi-box-arrow-left"></i>Déconnexion</a>
    </div>
</div>
<div class="sa-main">
    <div class="mb-4">
        <h4 class="mb-0" style="font-weight:800">Statistiques de la plateforme</h4>
        <small class="text-muted">Vue globale de toute l'activité IkelyaneMed</small>
    </div>

    <!-- KPIs -->
    <div class="row g-3 mb-4">
        <?php
        $kpis = [
            ['label'=>'Cliniques actives',     'value'=>number_format($global['tenants_actifs']),   'icon'=>'bi-building',      'bg'=>'#dbeafe','fg'=>'#1a56db'],
            ['label'=>'Médecins actifs',        'value'=>number_format($global['total_medecins']),   'icon'=>'bi-person-badge',  'bg'=>'#ede9fe','fg'=>'#7c3aed'],
            ['label'=>'Patients gérés',         'value'=>number_format($global['total_patients']),   'icon'=>'bi-person-heart',  'bg'=>'#d1fae5','fg'=>'#059669'],
            ['label'=>'RDV réalisés',           'value'=>number_format($global['rdv_termines']),     'icon'=>'bi-calendar-check','bg'=>'#fef3c7','fg'=>'#d97706'],
            ['label'=>'Ordonnances émises',     'value'=>number_format($global['total_ordonnances']),'icon'=>'bi-file-medical',  'bg'=>'#fce7f3','fg'=>'#db2777'],
            ['label'=>'Revenus facturés',       'value'=>number_format($global['revenus_plateforme']).' DA','icon'=>'bi-cash-stack','bg'=>'#dcfce7','fg'=>'#16a34a'],
        ];
        foreach($kpis as $k):
        ?>
        <div class="col-6 col-lg-4 col-xl-2">
            <div class="stat-card">
                <div style="width:40px;height:40px;border-radius:12px;background:<?= $k['bg'] ?>;display:flex;align-items:center;justify-content:center;color:<?= $k['fg'] ?>;font-size:1.1rem;margin-bottom:10px">
                    <i class="bi <?= $k['icon'] ?>"></i>
                </div>
                <div style="font-size:1.4rem;font-weight:800;color:#0f172a"><?= $k['value'] ?></div>
                <div style="font-size:.75rem;color:#94a3b8;font-weight:600"><?= $k['label'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-3 mb-4">
        <!-- Chart RDV mensuel -->
        <div class="col-lg-8">
            <div class="chart-card">
                <h6 style="font-weight:700;margin-bottom:20px"><i class="bi bi-calendar3 me-2 text-primary"></i>Rendez-vous / mois (6 derniers mois)</h6>
                <canvas id="rdvChart" height="90"></canvas>
            </div>
        </div>
        <!-- Top cliniques -->
        <div class="col-lg-4">
            <div class="chart-card h-100">
                <h6 style="font-weight:700;margin-bottom:20px"><i class="bi bi-trophy me-2 text-warning"></i>Top 5 cliniques</h6>
                <?php foreach($topTenants as $i => $t): ?>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:28px;height:28px;border-radius:50%;background:<?= ['#1a56db','#7c3aed','#059669','#d97706','#db2777'][$i] ?>;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0">
                        <?= $i+1 ?>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight:600;font-size:.88rem"><?= esc($t['nom']) ?></div>
                        <div class="progress mt-1" style="height:4px;border-radius:4px">
                            <?php $maxRdv = max(1, $topTenants[0]['nb_rdv']); ?>
                            <div class="progress-bar" style="width:<?= round($t['nb_rdv']/$maxRdv*100) ?>%;background:<?= ['#1a56db','#7c3aed','#059669','#d97706','#db2777'][$i] ?>"></div>
                        </div>
                    </div>
                    <div style="font-weight:700;font-size:.9rem;color:#0f172a;flex-shrink:0"><?= $t['nb_rdv'] ?> RDV</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
const rdvData = <?= json_encode($rdvMensuel) ?>;
new Chart(document.getElementById('rdvChart'), {
    type: 'bar',
    data: {
        labels: rdvData.map(d => d.mois),
        datasets: [
            {
                label: 'Total RDV',
                data: rdvData.map(d => d.nb),
                backgroundColor: 'rgba(26,86,219,0.15)',
                borderColor: '#1a56db',
                borderWidth: 2,
                borderRadius: 6,
            },
            {
                label: 'Terminés',
                data: rdvData.map(d => d.termines),
                backgroundColor: 'rgba(5,150,105,0.15)',
                borderColor: '#059669',
                borderWidth: 2,
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
