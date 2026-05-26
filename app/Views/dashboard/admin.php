<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-speedometer2 me-2 text-primary"></i><?= lang('Nav.dashboard') ?></h4>
    <div class="d-flex gap-2">
        <span class="text-muted small"><?= date('l, d F Y') ?></span>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Contacts</div>
                    <div class="stat-number"><?= number_format($kpis['total_contacts']) ?></div>
                    <small class="text-muted">Total</small>
                </div>
                <div class="stat-icon purple"><i class="bi bi-person-lines-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Leads actifs</div>
                    <div class="stat-number"><?= number_format($kpis['active_leads']) ?></div>
                    <small class="text-muted">En pipeline</small>
                </div>
                <div class="stat-icon teal"><i class="bi bi-funnel-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Tickets ouverts</div>
                    <div class="stat-number"><?= number_format($kpis['open_cases']) ?></div>
                    <small class="text-muted">À traiter</small>
                </div>
                <div class="stat-icon orange"><i class="bi bi-ticket-detailed-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Campagnes</div>
                    <div class="stat-number"><?= number_format($kpis['active_campaigns']) ?></div>
                    <small class="text-muted">Actives</small>
                </div>
                <div class="stat-icon blue"><i class="bi bi-megaphone-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pipeline</div>
                    <div class="stat-number" style="font-size:1.3rem"><?= number_format($kpis['pipeline_value'], 0, ',', ' ') ?></div>
                    <small class="text-muted">DA valeur</small>
                </div>
                <div class="stat-icon green"><i class="bi bi-currency-exchange"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Gagnés ce mois</div>
                    <div class="stat-number"><?= number_format($kpis['won_this_month']) ?></div>
                    <small class="text-muted">Leads convertis</small>
                </div>
                <div class="stat-icon green"><i class="bi bi-trophy-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Pipeline Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title"><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Revenus mensuels (DA)</h6>
                <span class="badge" style="background:var(--primary-light);color:var(--primary)">6 derniers mois</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Pipeline Summary -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title"><i class="bi bi-funnel-fill me-2" style="color:var(--teal)"></i>Pipeline par étape</h6>
            </div>
            <div class="card-body p-0">
                <?php
                $stageColors = [
                    'new'         => ['bg'=>'#ede9fe','color'=>'#7c3aed','label'=>'Nouveau'],
                    'contacted'   => ['bg'=>'#dbeafe','color'=>'#2563eb','label'=>'Contacté'],
                    'qualified'   => ['bg'=>'#cffafe','color'=>'#0e7490','label'=>'Qualifié'],
                    'proposal'    => ['bg'=>'#ffedd5','color'=>'#ea580c','label'=>'Proposition'],
                    'negotiation' => ['bg'=>'#fef3c7','color'=>'#d97706','label'=>'Négociation'],
                    'won'         => ['bg'=>'#d1fae5','color'=>'#059669','label'=>'Gagné'],
                    'lost'        => ['bg'=>'#fee2e2','color'=>'#dc2626','label'=>'Perdu'],
                ];
                foreach ($pipeline as $stage => $data):
                    $c = $stageColors[$stage] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','label'=>ucfirst($stage)];
                ?>
                <div class="d-flex align-items-center justify-content-between px-3 py-2" style="border-bottom:1px solid #f8f9fa">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill" style="background:<?= $c['bg'] ?>;color:<?= $c['color'] ?>;font-size:.7rem"><?= $c['label'] ?></span>
                        <span class="text-muted" style="font-size:.8rem"><?= $data['count'] ?> lead<?= $data['count'] > 1 ? 's' : '' ?></span>
                    </div>
                    <span style="font-size:.82rem;font-weight:700;color:#1a202c"><?= number_format($data['total_value'], 0, ',', ' ') ?> DA</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-0">
    <!-- Leads récents -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title"><i class="bi bi-funnel me-2 text-primary"></i>Leads récents</h6>
                <a href="/crm/leads" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr>
                        <th>Lead</th><th>Contact</th><th>Valeur</th><th>Statut</th>
                    </tr></thead>
                    <tbody>
                    <?php foreach ($recent_leads as $lead): ?>
                    <tr>
                        <td>
                            <a href="/crm/leads/<?= $lead['id'] ?>/view" class="fw-600 text-decoration-none text-dark" style="font-size:.875rem">
                                <?= esc($lead['titre']) ?>
                            </a>
                        </td>
                        <td style="font-size:.82rem;color:#64748b"><?= esc($lead['contact_name'] ?? $lead['contact_nom'] ?? '—') ?></td>
                        <td style="font-size:.82rem;font-weight:700;color:var(--primary)"><?= number_format($lead['valeur'] ?? 0, 0, ',', ' ') ?> DA</td>
                        <td>
                            <span class="badge-statut statut-<?= $lead['statut'] ?>">
                                <?= ucfirst(str_replace('_',' ',$lead['statut'])) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recent_leads)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucun lead pour le moment</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Activités à venir -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title"><i class="bi bi-lightning-charge-fill me-2" style="color:var(--teal)"></i>Activités à venir</h6>
                <a href="/crm/activities" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($upcoming)): ?>
                <div class="text-center text-muted py-4" style="font-size:.875rem">
                    <i class="bi bi-calendar-check" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                    Aucune activité planifiée
                </div>
                <?php endif; ?>
                <ul class="timeline p-3">
                <?php
                $typeIcons = ['call'=>'bi-telephone-fill','email'=>'bi-envelope-fill','meeting'=>'bi-people-fill','task'=>'bi-check2-square','note'=>'bi-sticky-fill','demo'=>'bi-display'];
                foreach (array_slice($upcoming, 0, 5) as $act):
                    $icon = $typeIcons[$act['type']] ?? 'bi-clock';
                ?>
                <li class="timeline-item">
                    <div class="timeline-icon"><i class="bi <?= $icon ?>"></i></div>
                    <div class="timeline-content">
                        <div class="timeline-title"><?= esc($act['titre']) ?></div>
                        <div class="timeline-meta">
                            <?= date('d/m H:i', strtotime($act['date_echeance'])) ?>
                            <?php if (!empty($act['contact_nom'])): ?> · <?= esc($act['contact_nom']) ?><?php endif; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($revenue_chart, 'label')) ?>,
        datasets: [{
            label: 'Revenus (DA)',
            data: <?= json_encode(array_column($revenue_chart, 'value')) ?>,
            backgroundColor: 'rgba(124,58,237,.15)',
            borderColor: '#7c3aed',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
<?= $this->endSection() ?>
