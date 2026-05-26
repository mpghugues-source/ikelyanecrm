<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Header -->
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
    <a href="/admin/export/revenue/pdf" target="_blank" class="btn btn-sm btn-danger rounded-3">
        <i class="bi bi-file-earmark-pdf me-1"></i>PDF
    </a>
</div>

<!-- Panneau de filtres avancés -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#filterPanel">
        <span class="fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filtres avancés</span>
        <i class="bi bi-chevron-down text-muted"></i>
    </div>
    <div class="collapse <?= ($date_from || $date_to || $medecin_id || $filterType) ? 'show' : '' ?>" id="filterPanel">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem;letter-spacing:.5px">Année</label>
                    <select name="year" class="form-select form-select-sm">
                        <?php for($y = date('Y'); $y >= date('Y')-4; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem;letter-spacing:.5px">Date début</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($date_from) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem;letter-spacing:.5px">Date fin</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($date_to) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem;letter-spacing:.5px">Médecin</label>
                    <select name="medecin_id" class="form-select form-select-sm">
                        <option value="">— Tous les médecins —</option>
                        <?php foreach($medecinsList as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $medecin_id == $m['id'] ? 'selected' : '' ?>><?= esc($m['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted text-uppercase" style="font-size:.72rem;letter-spacing:.5px">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">— Tous —</option>
                        <option value="rdv" <?= $filterType === 'rdv' ? 'selected' : '' ?>>RDV</option>
                        <option value="factures" <?= $filterType === 'factures' ? 'selected' : '' ?>>Factures</option>
                        <option value="patients" <?= $filterType === 'patients' ? 'selected' : '' ?>>Patients</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-2">
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
        ['label'=>'Revenus encaissés','value'=>number_format($totals['total_paye'],0,',',' ').' DA','icon'=>'bi-cash-stack','color'=>'#d1fae5','icon_color'=>'#059669','sub'=>number_format($totals['total_facture'],0,',',' ').' DA facturés'],
        ['label'=>'Rendez-vous','value'=>number_format($totalRdv),'icon'=>'bi-calendar-check','color'=>'#dbeafe','icon_color'=>'#1a56db','sub'=>$terminesCount.' terminés'],
        ['label'=>'Taux d\'occupation','value'=>$tauxOccupation.'%','icon'=>'bi-graph-up-arrow','color'=>'#ede9fe','icon_color'=>'#7c3aed','sub'=>'RDV terminés / total'],
        ['label'=>'Patients total','value'=>number_format($totalPatients),'icon'=>'bi-people-fill','color'=>'#fef3c7','icon_color'=>'#d97706','sub'=>'Tous inscrits'],
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
    <!-- Revenus mensuels -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Revenus <?= $year ?></h6>
                <div class="d-flex gap-3" style="font-size:.8rem">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#1a56db;margin-right:4px"></span>Facturé</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:#10b981;margin-right:4px"></span>Encaissé</span>
                </div>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    <!-- Statuts RDV (donut) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-3">Répartition RDV</h6>
            <canvas id="rdvChart" height="160"></canvas>
            <div class="mt-3">
                <?php
                $statutColors = ['planifie'=>'#94a3b8','confirme'=>'#1a56db','en_cours'=>'#d97706','termine'=>'#059669','annule'=>'#dc2626','absent'=>'#7c3aed'];
                $statutLabels = ['planifie'=>'Planifié','confirme'=>'Confirmé','en_cours'=>'En cours','termine'=>'Terminé','annule'=>'Annulé','absent'=>'Absent'];
                foreach($rdvStatuts as $s):
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
    <!-- Nouveaux patients par mois -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-3">Nouveaux patients par mois</h6>
            <canvas id="patientsChart" height="140"></canvas>
        </div>
    </div>

    <!-- Performance médecins -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h6 class="fw-bold mb-3">Performance par médecin</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size:.88rem">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th class="border-0 py-2 ps-3" style="font-weight:700;color:#64748b;font-size:.78rem">MÉDECIN</th>
                            <th class="border-0 py-2" style="font-weight:700;color:#64748b;font-size:.78rem">SPÉCIALITÉ</th>
                            <th class="border-0 py-2 text-center" style="font-weight:700;color:#64748b;font-size:.78rem">RDV</th>
                            <th class="border-0 py-2 text-center" style="font-weight:700;color:#64748b;font-size:.78rem">TERMINÉS</th>
                            <th class="border-0 py-2 text-end pe-3" style="font-weight:700;color:#64748b;font-size:.78rem">REVENUS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($medecinsPerf)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Aucun médecin actif</td></tr>
                    <?php else: ?>
                    <?php foreach($medecinsPerf as $m):
                        $taux = $m['nb_rdv'] > 0 ? round($m['nb_termines']/$m['nb_rdv']*100) : 0;
                    ?>
                    <tr>
                        <td class="ps-3">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                                    <?= strtoupper(substr($m['nom'],0,1)) ?>
                                </div>
                                <span class="fw-semibold">Dr. <?= esc($m['nom']) ?></span>
                            </div>
                        </td>
                        <td class="text-muted"><?= esc($m['specialite'] ?? '—') ?></td>
                        <td class="text-center fw-bold"><?= $m['nb_rdv'] ?></td>
                        <td class="text-center">
                            <div><?= $m['nb_termines'] ?></div>
                            <div style="font-size:.72rem;color:<?= $taux>=70 ? '#059669' : ($taux>=40 ? '#d97706' : '#dc2626') ?>"><?= $taux ?>%</div>
                        </td>
                        <td class="text-end pe-3 fw-bold" style="color:#059669"><?= number_format($m['revenus'],0,',',' ') ?> DA</td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Revenus par médecin (filtrés) -->
<?php if (!empty($revenueByMedecin)): ?>
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h6 class="fw-bold mb-3">
                Revenus par médecin
                <?php if ($useCustomDates): ?>
                <small class="text-muted fw-normal">(<?= date('d/m/Y', strtotime($sqlDateFrom)) ?> → <?= date('d/m/Y', strtotime($sqlDateTo)) ?>)</small>
                <?php endif; ?>
            </h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size:.88rem">
                    <thead style="background:#f8fafc">
                        <tr>
                            <th class="border-0 py-2 ps-3" style="font-weight:700;color:#64748b;font-size:.78rem">MÉDECIN</th>
                            <th class="border-0 py-2 text-center" style="font-weight:700;color:#64748b;font-size:.78rem">FACTURES</th>
                            <th class="border-0 py-2 text-end pe-3" style="font-weight:700;color:#64748b;font-size:.78rem">REVENUS ENCAISSÉS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalRevMed = array_sum(array_column($revenueByMedecin, 'revenus'));
                    foreach($revenueByMedecin as $rm):
                        $pct = $totalRevMed > 0 ? round($rm['revenus'] / $totalRevMed * 100) : 0;
                    ?>
                    <tr>
                        <td class="ps-3">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;flex-shrink:0">
                                    <?= strtoupper(substr($rm['nom'],0,1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= esc($rm['nom']) ?></div>
                                    <div class="progress mt-1" style="height:4px;width:120px">
                                        <div class="progress-bar bg-primary" style="width:<?= $pct ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center fw-bold"><?= $rm['nb_factures'] ?></td>
                        <td class="text-end pe-3 fw-bold" style="color:#059669"><?= number_format($rm['revenus'],0,',',' ') ?> DA</td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Indicateurs de performance -->
<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div style="font-size:.8rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:12px">Taux d'occupation</div>
            <div style="position:relative;display:inline-block;width:100px;height:100px">
                <svg viewBox="0 0 36 36" style="width:100%;height:100%">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#e2e8f0" stroke-width="3"/>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#059669" stroke-width="3"
                          stroke-dasharray="<?= $tauxOccupation ?>, 100"
                          stroke-linecap="round"/>
                    <text x="18" y="20.35" text-anchor="middle" style="font-size:8px;font-weight:800;fill:#0f172a"><?= $tauxOccupation ?>%</text>
                </svg>
            </div>
            <div class="text-muted mt-2" style="font-size:.85rem">RDV honorés / total planifiés</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div style="font-size:.8rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:12px">Taux d'annulation</div>
            <div style="position:relative;display:inline-block;width:100px;height:100px">
                <svg viewBox="0 0 36 36" style="width:100%;height:100%">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#e2e8f0" stroke-width="3"/>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none" stroke="#dc2626" stroke-width="3"
                          stroke-dasharray="<?= $tauxAnnulation ?>, 100"
                          stroke-linecap="round"/>
                    <text x="18" y="20.35" text-anchor="middle" style="font-size:8px;font-weight:800;fill:#0f172a"><?= $tauxAnnulation ?>%</text>
                </svg>
            </div>
            <div class="text-muted mt-2" style="font-size:.85rem">RDV annulés / total planifiés</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
            <div style="font-size:.8rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:12px">Revenu moyen / RDV</div>
            <?php $moyenneRdv = $totalRdv > 0 ? round($totals['total_paye'] / $totalRdv) : 0; ?>
            <div style="font-size:2.2rem;font-weight:900;color:#1a56db;margin:20px 0"><?= number_format($moyenneRdv,0,',',' ') ?></div>
            <div class="text-muted" style="font-size:.85rem">Dinars algériens / consultation</div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Aoû','Sep','Oct','Nov','Déc'];

// Revenus mensuels
const revenueData = <?= json_encode(array_values($revenueByMonth)) ?>;
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Facturé',
                data: revenueData.map(d => d.total_facture || 0),
                backgroundColor: 'rgba(26,86,219,0.15)',
                borderColor: '#1a56db',
                borderWidth: 2,
                borderRadius: 6,
            },
            {
                label: 'Encaissé',
                data: revenueData.map(d => d.total_paye || 0),
                backgroundColor: 'rgba(16,185,129,0.8)',
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
            y: { grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString('fr-DZ') + ' DA' } },
            x: { grid: { display: false } }
        }
    }
});

// Répartition RDV (donut)
const rdvData = <?= json_encode(array_values(array_column($rdvStatuts, 'nb'))) ?>;
const rdvLabels = <?= json_encode(array_map(fn($s) => ucfirst($s['statut']), $rdvStatuts)) ?>;
const rdvColors = ['#94a3b8','#1a56db','#d97706','#059669','#dc2626','#7c3aed'];
new Chart(document.getElementById('rdvChart'), {
    type: 'doughnut',
    data: {
        labels: rdvLabels,
        datasets: [{ data: rdvData, backgroundColor: rdvColors.slice(0, rdvData.length), borderWidth: 0, hoverOffset: 4 }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// Nouveaux patients
const patientsData = <?= json_encode(array_values($patientsByMonth)) ?>;
new Chart(document.getElementById('patientsChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Nouveaux patients',
            data: patientsData,
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,0.08)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#7c3aed',
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
