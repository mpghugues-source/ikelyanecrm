<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?>IkelyaneCRM — <?= lang('Crm.dashboard') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex align-items-center mb-4 gap-3">
    <div class="crm-brand-icon"><i class="bi bi-diagram-3-fill"></i></div>
    <div>
        <h1 class="h3 mb-0 fw-bold">IkelyaneCRM</h1>
        <small class="text-muted"><?= lang('Crm.crm_full') ?></small>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <?php
    $kpi_cards = [
        ['icon'=>'bi-people-fill','color'=>'primary','label'=>lang('Crm.total_contacts'),'value'=>number_format($kpis['total_contacts']),'link'=>'/crm/contacts'],
        ['icon'=>'bi-funnel-fill','color'=>'warning','label'=>lang('Crm.active_leads'),'value'=>number_format($kpis['active_leads']),'link'=>'/crm/leads'],
        ['icon'=>'bi-ticket-detailed-fill','color'=>'danger','label'=>lang('Crm.open_cases'),'value'=>number_format($kpis['open_cases']),'link'=>'/crm/cases'],
        ['icon'=>'bi-megaphone-fill','color'=>'success','label'=>lang('Crm.active_campaigns'),'value'=>number_format($kpis['active_campaigns']),'link'=>'/crm/campaigns'],
        ['icon'=>'bi-currency-dollar','color'=>'info','label'=>lang('Crm.pipeline_value'),'value'=>number_format($kpis['pipeline_value'],2).' DZD','link'=>'/crm/leads'],
        ['icon'=>'bi-trophy-fill','color'=>'success','label'=>lang('Crm.won_value'),'value'=>number_format($kpis['won_value'],2).' DZD','link'=>'/crm/leads'],
    ];
    foreach ($kpi_cards as $card): ?>
    <div class="col-6 col-md-4 col-xl-2">
        <a href="<?= $card['link'] ?>" class="text-decoration-none">
            <div class="card kpi-card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="kpi-icon bg-<?= $card['color'] ?>-subtle text-<?= $card['color'] ?> mb-2 mx-auto">
                        <i class="bi <?= $card['icon'] ?>"></i>
                    </div>
                    <div class="fw-bold fs-5"><?= $card['value'] ?></div>
                    <small class="text-muted"><?= $card['label'] ?></small>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <!-- Sales Pipeline -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-warning"></i><?= lang('Crm.pipeline') ?></h6>
                <a href="/crm/leads/kanban" class="btn btn-sm btn-outline-secondary"><?= lang('Crm.kanban') ?></a>
            </div>
            <div class="card-body p-3">
                <?php
                $stages = ['new'=>['label'=>lang('Crm.stage_new'),'color'=>'secondary'],
                           'qualified'=>['label'=>lang('Crm.stage_qualified'),'color'=>'info'],
                           'proposition'=>['label'=>lang('Crm.stage_proposition'),'color'=>'primary'],
                           'negotiation'=>['label'=>lang('Crm.stage_negotiation'),'color'=>'warning'],
                           'won'=>['label'=>lang('Crm.stage_won'),'color'=>'success'],
                           'lost'=>['label'=>lang('Crm.stage_lost'),'color'=>'danger']];
                foreach ($stages as $key => $s):
                    $count = $pipeline[$key]['count'] ?? 0;
                    $value = $pipeline[$key]['total_value'] ?? 0;
                ?>
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-<?= $s['color'] ?> me-2" style="width:90px;font-size:.7rem"><?= $s['label'] ?></span>
                    <div class="flex-grow-1 progress me-2" style="height:6px">
                        <div class="progress-bar bg-<?= $s['color'] ?>" style="width:<?= $count > 0 ? min(100, $count * 15) : 0 ?>%"></div>
                    </div>
                    <span class="text-muted small"><?= $count ?> — <?= number_format($value/1000,1) ?>k</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Upcoming Activities -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-check me-2 text-primary"></i><?= lang('Crm.upcoming') ?></h6>
                <a href="/crm/activities/create" class="btn btn-sm btn-primary"><?= lang('Crm.new_activity') ?></a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($upcoming)): ?>
                <div class="text-center text-muted py-4"><i class="bi bi-calendar-x fs-3 d-block mb-2"></i><?= lang('Crm.no_records') ?></div>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($upcoming, 0, 6) as $act):
                        $typeIcons = ['call'=>'bi-telephone','email'=>'bi-envelope','meeting'=>'bi-people','task'=>'bi-check2-square','note'=>'bi-sticky'];
                        $icon = $typeIcons[$act['type']] ?? 'bi-circle';
                    ?>
                    <div class="list-group-item border-0 px-3 py-2">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mt-1 text-primary"><i class="bi <?= $icon ?>"></i></div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate small"><?= esc($act['sujet']) ?></div>
                                <small class="text-muted"><?= date('d/m H:i', strtotime($act['date_debut'])) ?><?= $act['contact_name'] ? ' · '.esc($act['contact_name']) : '' ?></small>
                            </div>
                            <span class="badge bg-<?= $act['priorite'] === 'high' ? 'danger' : ($act['priorite'] === 'medium' ? 'warning' : 'secondary') ?> badge-sm"><?= $act['priorite'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Leads -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-star-fill me-2 text-warning"></i><?= lang('Crm.leads') ?></h6>
                <a href="/crm/leads/create" class="btn btn-sm btn-warning"><?= lang('Crm.new_lead') ?></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><?= lang('Crm.lead') ?></th>
                                <th><?= lang('Crm.related_contact') ?></th>
                                <th><?= lang('Crm.est_value') ?></th>
                                <th><?= lang('Crm.probability') ?></th>
                                <th><?= lang('Crm.status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_leads)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3"><?= lang('Crm.no_records') ?></td></tr>
                            <?php else: foreach ($recent_leads as $l):
                                $statusColors = ['new'=>'secondary','qualified'=>'info','proposition'=>'primary','negotiation'=>'warning','won'=>'success','lost'=>'danger'];
                                $sc = $statusColors[$l['statut']] ?? 'secondary';
                            ?>
                            <tr>
                                <td><a href="/crm/leads/<?= $l['id'] ?>/edit" class="fw-semibold text-decoration-none"><?= esc($l['titre']) ?></a></td>
                                <td><?= esc($l['contact_name'] ?? '—') ?></td>
                                <td class="fw-semibold"><?= number_format($l['valeur_estimee'],2) ?> <?= $l['devise'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:5px"><div class="progress-bar bg-<?= $sc ?>" style="width:<?= $l['probabilite'] ?>%"></div></div>
                                        <small><?= $l['probabilite'] ?>%</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-<?= $sc ?>"><?= lang('Crm.stage_'.$l['statut']) ?></span></td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.crm-brand-icon { width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0d6efd,#0dcaf0);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem; }
.kpi-icon { width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem; }
.kpi-card:hover { transform:translateY(-2px);transition:.2s; }
</style>
<?php $this->endSection(); ?>
