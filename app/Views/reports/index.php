<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="mb-0 fw-bold">Rapports & Statistiques</h4>
        <small class="text-muted">
            <?php if ($useCustomDates): ?>
                Période : <?= date('d/m/Y', strtotime($sqlDateFrom)) ?> — <?= date('d/m/Y', strtotime($sqlDateTo)) ?>
            <?php else: ?>
                Analyse de l'activité — Année <?= $year ?>
            <?php endif; ?>
        </small>
    </div>
</div>

<!-- Filtres -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#filterPanel">
        <span class="fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filtres</span>
        <i class="bi bi-chevron-down text-muted"></i>
    </div>
    <div class="collapse <?= ($date_from || $date_to) ? 'show' : '' ?>" id="filterPanel">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem">Année</label>
                    <select name="year" class="form-select form-select-sm">
                        <?php for($y = date('Y'); $y >= date('Y')-4; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem">Date début</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($date_from) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem">Date fin</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($date_to) ?>">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    <a href="/admin/reports" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <?php
    $kpis = [
        ['label'=>'Leads total','value'=>number_format($totalLeads),'icon'=>'bi-funnel-fill','color'=>'#dbeafe','icon_color'=>'#1a56db','sub'=>'Période sélectionnée'],
        ['label'=>'Leads gagnés','value'=>number_format($totalGagnes),'icon'=>'bi-trophy-fill','color'=>'#d1fae5','icon_color'=>'#059669','sub'=>'Statut "Won"'],
        ['label'=>'Taux de conversion','value'=>$tauxConversion.'%','icon'=>'bi-graph-up-arrow','color'=>'#ede9fe','icon_color'=>'#7c3aed','sub'=>'Gagnés / total'],
        ['label'=>'Contacts total','value'=>number_format($totalContacts),'icon'=>'bi-people-fill','color'=>'#fef3c7','icon_color'=>'#d97706','sub'=>'Tous inscrits'],
    ];
    foreach($kpis as $k): ?>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:.75rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px"><?= $k['label'] ?></div>
                    <div style="font-size:1.6rem;font-weight:800;color:#0f172a;margin:6px 0 2px"><?= $k['value'] ?></div>
                    <div style="font-size:.8rem;color:#64748b"><?= $k['sub'] ?></div>
                </div>
                <div style="width:44px;height:44px;border-radius:12px;background:<?= $k['color'] ?>;display:flex;align-items:center;justify-content:center;color:<?= $k['icon_color'] ?>;font-size:1.2rem">
                    <i class="bi <?= $k['icon'] ?>"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Graphiques -->
<div class="row g-3 mb-4">
    <!-- Leads mensuels -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Leads <?= $year ?></h6>
                <div class="d-flex gap-3" style="font-size:.8rem">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#1a56db;margin-right:4px"></span>Total</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#10b981;margin-right:4px"></span>Gagnés</span>
                </div>
            </div>
            <canvas id="leadsChart" height="100"></canvas>
        </div>
    </div>

    <!-- Statuts leads (donut) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-3">Répartition pipeline</h6>
            <canvas id="pipelineChart" height="160"></canvas>
            <div class="mt-3">
                <?php
                $statutColors = ['new'=>'#94a3b8','qualified'=>'#1a56db','proposition'=>'#d97706','negotiation'=>'#7c3aed','won'=>'#059669','lost'=>'#dc2626'];
                $statutLabels = ['new'=>'Nouveau','qualified'=>'Qualifié','proposition'=>'Proposition','negotiation'=>'Négociation','won'=>'Gagné','lost'=>'Perdu'];
                foreach($leadsStatuts as $s):
                    $c = $statutColors[$s['statut']] ?? '#94a3b8';
                ?>
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:.85rem">
                    <span><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:<?= $c ?>;margin-right:6px"></span><?= $statutLabels[$s['statut']] ?? $s['statut'] ?></span>
                    <span class="fw-bold"><?= $s['nb'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Nouveaux contacts par mois -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-3">Nouveaux contacts par mois</h6>
            <canvas id="contactsChart" height="140"></canvas>
        </div>
    </div>

    <!-- Performance commerciaux -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h6 class="fw-bold mb-3">Performance par commercial</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size:.88rem">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th class="border-0 py-2 ps-3" style="font-weight:700;color:#64748b;font-size:.78rem">COMMERCIAL</th>
                            <th class="border-0 py-2 text-center" style="font-weight:700;color:#64748b;font-size:.78rem">LEADS</th>
                            <th class="border-0 py-2 text-center" style="font-weight:700;color:#64748b;font-size:.78rem">GAGNÉS</th>
                            <th class="border-0 py-2 text-end pe-3" style="font-weight:700;color:#64748b;font-size:.78rem">VALEUR</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($commerciauxPerf)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">Aucun utilisateur actif</td></tr>
                    <?php else: ?>
                    <?php foreach($commerciauxPerf as $m):
                        $taux = $m['nb_leads'] > 0 ? round($m['nb_gagnes']/$m['nb_leads']*100) : 0;
                    ?>
                    <tr>
                        <td class="ps-3">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#059669,#f97316);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                                    <?= strtoupper(substr($m['nom'],0,1)) ?>
                                </div>
                                <span class="fw-semibold"><?= esc($m['nom']) ?></span>
                            </div>
                        </td>
                        <td class="text-center fw-bold"><?= $m['nb_leads'] ?></td>
                        <td class="text-center">
                            <div><?= $m['nb_gagnes'] ?></div>
                            <div style="font-size:.72rem;color:<?= $taux>=50 ? '#059669' : ($taux>=25 ? '#d97706' : '#dc2626') ?>"><?= $taux ?>%</div>
                        </td>
                        <td class="text-end pe-3 fw-bold" style="color:#059669"><?= number_format($m['valeur_gagnee'],0,',',' ') ?> DA</td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Indicateurs de performance -->
<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div style="font-size:.8rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:12px">Taux de conversion</div>
            <div style="position:relative;display:inline-block;width:100px;height:100px">
                <svg viewBox="0 0 36 36" style="width:100%;height:100%">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#e2e8f0" stroke-width="3"/>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#059669" stroke-width="3"
                          stroke-dasharray="<?= $tauxConversion ?>, 100"
                          stroke-linecap="round"/>
                    <text x="18" y="20.35" text-anchor="middle" style="font-size:8px;font-weight:800;fill:#0f172a"><?= $tauxConversion ?>%</text>
                </svg>
            </div>
            <div class="text-muted mt-2" style="font-size:.85rem">Leads gagnés / total</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div style="font-size:.8rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:12px">Taux de perte</div>
            <div style="position:relative;display:inline-block;width:100px;height:100px">
                <svg viewBox="0 0 36 36" style="width:100%;height:100%">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#e2e8f0" stroke-width="3"/>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#dc2626" stroke-width="3"
                          stroke-dasharray="<?= $tauxPerte ?>, 100"
                          stroke-linecap="round"/>
                    <text x="18" y="20.35" text-anchor="middle" style="font-size:8px;font-weight:800;fill:#0f172a"><?= $tauxPerte ?>%</text>
                </svg>
            </div>
            <div class="text-muted mt-2" style="font-size:.85rem">Leads perdus / total</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div style="font-size:.8rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:12px">Valeur pipeline</div>
            <?php $valeurMoyenne = $totalLeads > 0 ? round(($totals['valeur_totale'] ?? 0) / $totalLeads) : 0; ?>
            <div style="font-size:2.2rem;font-weight:900;color:#059669;margin:20px 0"><?= number_format($valeurMoyenne,0,',',' ') ?></div>
            <div class="text-muted" style="font-size:.85rem">Valeur moyenne / lead (DA)</div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const months = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Aoû','Sep','Oct','Nov','Déc'];

const leadsData = <?= json_encode(array_values($leadsByMonth)) ?>;
new Chart(document.getElementById('leadsChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Total leads',
                data: leadsData.map(d => d.total_leads || 0),
                backgroundColor: 'rgba(26,86,219,0.15)',
                borderColor: '#1a56db',
                borderWidth: 2,
                borderRadius: 6,
            },
            {
                label: 'Gagnés',
                data: leadsData.map(d => d.leads_gagnes || 0),
                backgroundColor: 'rgba(5,150,105,0.8)',
                borderColor: '#059669',
                borderWidth: 0,
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

const pipelineNb = <?= json_encode(array_values(array_column($leadsStatuts, 'nb'))) ?>;
const pipelineLabels = <?= json_encode(array_map(fn($s) => ucfirst($s['statut']), $leadsStatuts)) ?>;
const pipelineColors = ['#94a3b8','#1a56db','#d97706','#7c3aed','#059669','#dc2626'];
new Chart(document.getElementById('pipelineChart'), {
    type: 'doughnut',
    data: {
        labels: pipelineLabels,
        datasets: [{ data: pipelineNb, backgroundColor: pipelineColors.slice(0, pipelineNb.length), borderWidth: 0, hoverOffset: 4 }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

const contactsData = <?= json_encode(array_values($contactsByMonth)) ?>;
new Chart(document.getElementById('contactsChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Nouveaux contacts',
            data: contactsData,
            borderColor: '#f97316',
            backgroundColor: 'rgba(249,115,22,0.08)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#f97316',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});
</script>
<?= $this->endSection() ?>
