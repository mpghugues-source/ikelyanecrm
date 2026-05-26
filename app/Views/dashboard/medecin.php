<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$nomDoc = $medecin ? ('Dr. ' . session()->get('prenom') . ' ' . session()->get('nom')) : session()->get('prenom');
$heure  = (int)date('H');
$salut  = $heure < 12 ? lang('Dashboard.good_morning') : ($heure < 18 ? lang('Dashboard.good_afternoon') : lang('Dashboard.good_evening'));
$_jours = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi'];
$_mois  = ['January'=>'Janvier','February'=>'Février','March'=>'Mars','April'=>'Avril','May'=>'Mai','June'=>'Juin','July'=>'Juillet','August'=>'Août','September'=>'Septembre','October'=>'Octobre','November'=>'Novembre','December'=>'Décembre'];
$dateFr = $_jours[date('l')].' '.date('d').' '.$_mois[date('F')].' '.date('Y');
$moisFr = $_mois[date('F')].' '.date('Y');
?>

<!-- Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h4 class="mb-0 fw-bold"><?= $salut ?>, <?= esc($nomDoc) ?> 👋</h4>
        <small class="text-muted"><?= $dateFr ?> · <?= $rdv_today ?> <?= lang('Dashboard.today_appointments') ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="/medecin/appointments/calendar" class="btn btn-outline-primary rounded-3 btn-sm">
            <i class="bi bi-calendar3 me-1"></i><?= lang('Dashboard.my_calendar') ?>
        </a>
    </div>
</div>

<!-- Prochain patient -->
<?php if (!empty($prochainRdv)): ?>
<div class="card border-0 rounded-4 mb-4" style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div style="font-size:.8rem;opacity:.8;font-weight:600;text-transform:uppercase;letter-spacing:.5px"><?= lang('Dashboard.next_patient') ?></div>
                <div style="font-size:1.5rem;font-weight:800;margin:4px 0"><?= esc($prochainRdv['patient_prenom'].' '.$prochainRdv['patient_nom']) ?></div>
                <div style="opacity:.85;font-size:.9rem">
                    <i class="bi bi-clock me-1"></i><?= substr($prochainRdv['heure_rdv'],0,5) ?>
                    <span class="mx-2">·</span>
                    <i class="bi bi-chat-text me-1"></i><?= esc($prochainRdv['motif'] ?? lang('Common.not_specified')) ?>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="/medecin/patients/view/<?= $prochainRdv['patient_id'] ?>" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:10px">
                    <i class="bi bi-folder me-1"></i><?= lang('Common.file') ?>
                </a>
                <a href="/admin/medical-records/create/<?= $prochainRdv['patient_id'] ?>" class="btn btn-sm" style="background:#fff;color:#1a56db;border:none;border-radius:10px;font-weight:700">
                    <i class="bi bi-plus me-1"></i><?= lang('Common.consultation') ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <?php
    $kpis = [
        ['label'=>lang('Dashboard.today_appointments'),'value'=>$rdv_today,'sub'=>date('d/m/Y'),'icon'=>'bi-calendar-event','bg'=>'#dbeafe','color'=>'#1a56db'],
        ['label'=>lang('Dashboard.appointments_month'),'value'=>$statsMonth['total']??0,'sub'=>($statsMonth['termines']??0).' '.lang('Dashboard.completed'),'icon'=>'bi-calendar-check','bg'=>'#d1fae5','color'=>'#059669'],
        ['label'=>lang('Dashboard.attendance_rate'),'value'=>(($statsMonth['total']??0)>0 ? round(($statsMonth['termines']??0)/($statsMonth['total'])*100) : 0).'%','sub'=>($statsMonth['absents']??0).' '.lang('Dashboard.absences'),'icon'=>'bi-person-check','bg'=>'#ede9fe','color'=>'#7c3aed'],
        ['label'=>lang('Dashboard.monthly_revenue'),'value'=>number_format($revenuMois,0,',',' ').' DA','sub'=>$moisFr,'icon'=>'bi-cash-stack','bg'=>'#fef3c7','color'=>'#d97706'],
    ];
    foreach($kpis as $k): ?>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:.75rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px"><?= $k['label'] ?></div>
                    <div style="font-size:1.5rem;font-weight:800;color:#0f172a;margin:6px 0 2px"><?= $k['value'] ?></div>
                    <div style="font-size:.8rem;color:#64748b"><?= $k['sub'] ?></div>
                </div>
                <div style="width:42px;height:42px;border-radius:12px;background:<?= $k['bg'] ?>;display:flex;align-items:center;justify-content:center;color:<?= $k['color'] ?>;font-size:1.1rem;flex-shrink:0">
                    <i class="bi <?= $k['icon'] ?>"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <!-- Planning du jour -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i><?= lang('Dashboard.daily_schedule') ?></h6>
                <a href="/medecin/appointments" class="btn btn-sm" style="background:#f1f5f9;border:none;border-radius:8px;font-weight:600;font-size:.8rem"><?= lang('Dashboard.view_all') ?></a>
            </div>
            <?php if(empty($rdvsToday)): ?>
            <div class="text-center text-muted py-4">
                <i class="bi bi-calendar-x" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                <?= lang('Dashboard.no_appointments_today') ?>
            </div>
            <?php else:
            $statutColors = ['planifie'=>['#dbeafe','#1a56db'],'confirme'=>['#d1fae5','#059669'],'en_cours'=>['#fef3c7','#d97706'],'termine'=>['#f1f5f9','#64748b'],'annule'=>['#fee2e2','#dc2626'],'absent'=>['#fce7f3','#db2777']];
            $statutIcons  = ['planifie'=>'bi-clock','confirme'=>'bi-check-circle','en_cours'=>'bi-play-circle','termine'=>'bi-check-circle-fill','annule'=>'bi-x-circle','absent'=>'bi-person-x'];
            foreach($rdvsToday as $rdv):
                $sc = $statutColors[$rdv['statut']] ?? ['#f1f5f9','#64748b'];
                $ic = $statutIcons[$rdv['statut']] ?? 'bi-circle';
            ?>
            <div class="d-flex gap-3 mb-3 align-items-start" style="opacity:<?= in_array($rdv['statut'],['termine','annule']) ? '.5' : '1' ?>">
                <div style="text-align:center;flex-shrink:0;width:44px">
                    <div style="font-size:.9rem;font-weight:800;color:#0f172a"><?= substr($rdv['heure_rdv'],0,5) ?></div>
                    <div style="font-size:.7rem;color:#94a3b8"><?= $rdv['duree']??30 ?>min</div>
                </div>
                <div style="width:3px;background:<?= $sc[1] ?>;border-radius:2px;flex-shrink:0;align-self:stretch;min-height:48px"></div>
                <div class="flex-grow-1 p-3 rounded-3" style="background:<?= $sc[0] ?>">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:#0f172a"><?= esc($rdv['patient_prenom'].' '.$rdv['patient_nom']) ?></div>
                            <div style="font-size:.8rem;color:#64748b;margin-top:2px"><?= esc($rdv['motif'] ?? lang('Common.consultation')) ?></div>
                        </div>
                        <span style="background:rgba(255,255,255,.7);padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:600;color:<?= $sc[1] ?>;white-space:nowrap">
                            <i class="bi <?= $ic ?> me-1"></i><?= ucfirst($rdv['statut']) ?>
                        </span>
                    </div>
                    <?php if(in_array($rdv['statut'],['planifie','confirme'])): ?>
                    <div class="d-flex gap-1 mt-2">
                        <a href="/medecin/patients/view/<?= $rdv['patient_id'] ?>" class="btn btn-sm" style="background:rgba(255,255,255,.8);border:none;border-radius:6px;font-size:.78rem;padding:3px 10px">
                            <i class="bi bi-folder me-1"></i><?= lang('Common.file') ?>
                        </a>
                        <a href="/admin/medical-records/create/<?= $rdv['patient_id'] ?>" class="btn btn-sm" style="background:<?= $sc[1] ?>;color:#fff;border:none;border-radius:6px;font-size:.78rem;padding:3px 10px">
                            <i class="bi bi-plus me-1"></i><?= lang('Dashboard.consult') ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Sidebar droite -->
    <div class="col-lg-5">
        <!-- Mini chart semaine -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2 text-primary"></i><?= lang('Dashboard.appointments_week') ?></h6>
            <canvas id="weekChart" height="90"></canvas>
        </div>

        <!-- Patients récents -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i><?= lang('Dashboard.recent_patients') ?></h6>
                <a href="/medecin/patients" style="font-size:.8rem;color:#1a56db;text-decoration:none;font-weight:600"><?= lang('Dashboard.view_all') ?> →</a>
            </div>
            <?php if(empty($recentPatients)): ?>
            <p class="text-muted text-center small py-2"><?= lang('Dashboard.no_recent_patients') ?></p>
            <?php else: ?>
            <div class="d-flex flex-column gap-2">
                <?php foreach($recentPatients as $p):
                    $age = $p['date_naissance'] ? (int)date_diff(new DateTime($p['date_naissance']), new DateTime())->y : null;
                ?>
                <a href="/medecin/patients/view/<?= $p['id'] ?>" class="d-flex align-items-center gap-3 p-2 rounded-3 text-decoration-none" style="background:#f8fafc">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                        <?= strtoupper(substr($p['prenom'],0,1).substr($p['nom'],0,1)) ?>
                    </div>
                    <div class="flex-grow-1" style="min-width:0">
                        <div style="font-weight:600;font-size:.87rem;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= esc($p['prenom'].' '.$p['nom']) ?></div>
                        <div style="font-size:.75rem;color:#94a3b8"><?= $age ? $age.' '.lang('Common.years_old') : '' ?></div>
                    </div>
                    <div style="font-size:.75rem;color:#94a3b8;flex-shrink:0"><?= date('d/m', strtotime($p['derniere_visite'])) ?></div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('weekChart'), {
    type: 'bar',
    data: {
        labels: ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'],
        datasets: [{
            data: <?= json_encode(array_values($weekData)) ?>,
            backgroundColor: function(ctx) {
                const today  = (new Date().getDay() + 6) % 7; // 0=Lun
                return ctx.dataIndex === today ? '#1a56db' : 'rgba(26,86,219,0.15)';
            },
            borderRadius: 6,
            borderSkipped: false,
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
