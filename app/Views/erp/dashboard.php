<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?>IkelyaneERP — <?= lang('Erp.dashboard') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex align-items-center mb-4 gap-3">
    <div class="erp-brand-icon"><i class="bi bi-building-fill-gear"></i></div>
    <div>
        <h1 class="h3 mb-0 fw-bold">IkelyaneERP</h1>
        <small class="text-muted"><?= lang('Erp.erp_full') ?></small>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <?php
    $locale = session()->get('locale') ?? 'fr';
    $currency = 'DZD';
    $kpi_items = [
        ['icon'=>'bi-graph-up-arrow','color'=>'success','label'=>lang('Erp.yearly_revenue'),'value'=>number_format($kpis['yearly_revenue'],0,',',' ').' '.$currency,'link'=>'/erp/finance/transactions'],
        ['icon'=>'bi-graph-down-arrow','color'=>'danger','label'=>lang('Erp.yearly_expenses'),'value'=>number_format($kpis['yearly_expenses'],0,',',' ').' '.$currency,'link'=>'/erp/finance/transactions'],
        ['icon'=>'bi-calculator-fill','color'=>($kpis['net_result']>=0?'success':'danger'),'label'=>lang('Erp.net_result'),'value'=>number_format($kpis['net_result'],0,',',' ').' '.$currency,'link'=>'/erp/finance/budgets'],
        ['icon'=>'bi-exclamation-triangle-fill','color'=>'warning','label'=>lang('Erp.low_stock_items'),'value'=>$kpis['low_stock'],'link'=>'/erp/inventory'],
        ['icon'=>'bi-cart-fill','color'=>'info','label'=>lang('Erp.pending_po'),'value'=>$kpis['pending_po'],'link'=>'/erp/purchase-orders'],
        ['icon'=>'bi-kanban-fill','color'=>'primary','label'=>lang('Erp.active_projects'),'value'=>$kpis['active_projects'],'link'=>'/erp/projects'],
        ['icon'=>'bi-truck','color'=>'secondary','label'=>lang('Erp.total_suppliers'),'value'=>$kpis['total_suppliers'],'link'=>'/erp/suppliers'],
    ];
    foreach ($kpi_items as $k): ?>
    <div class="col-6 col-md-3 col-xl-auto flex-xl-fill">
        <a href="<?= $k['link'] ?>" class="text-decoration-none">
            <div class="card kpi-card border-0 shadow-sm h-100">
                <div class="card-body text-center p-3">
                    <div class="kpi-icon bg-<?= $k['color'] ?>-subtle text-<?= $k['color'] ?> mb-2 mx-auto"><i class="bi <?= $k['icon'] ?>"></i></div>
                    <div class="fw-bold"><?= $k['value'] ?></div>
                    <small class="text-muted"><?= $k['label'] ?></small>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <!-- Monthly Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-bar-chart-fill me-2 text-success"></i><?= date('Y') ?> — Revenus vs Dépenses</h6>
            </div>
            <div class="card-body"><canvas id="financeChart" height="80"></canvas></div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-receipt me-2 text-info"></i><?= lang('Erp.transactions') ?></h6>
                <a href="/erp/finance/transactions" class="btn btn-sm btn-outline-secondary"><?= lang('Erp.view') ?></a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recent_transactions)): ?>
                    <div class="text-center text-muted py-4"><?= lang('Erp.no_records') ?></div>
                    <?php else: foreach (array_slice($recent_transactions, 0, 6) as $tx): ?>
                    <div class="list-group-item border-0 px-3 py-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center <?= $tx['type']==='income' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?>" style="width:32px;height:32px;min-width:32px">
                                <i class="bi <?= $tx['type']==='income' ? 'bi-arrow-down-left' : 'bi-arrow-up-right' ?> small"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="small text-truncate fw-semibold"><?= esc($tx['description']) ?></div>
                                <small class="text-muted"><?= date('d/m', strtotime($tx['date_transaction'])) ?></small>
                            </div>
                            <span class="fw-semibold <?= $tx['type']==='income' ? 'text-success' : 'text-danger' ?> small text-nowrap">
                                <?= $tx['type']==='income' ? '+' : '-' ?><?= number_format($tx['montant'],0,'.',',') ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <?php if (!empty($low_stock_items)): ?>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm border-warning border-start border-4">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 fw-semibold text-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= lang('Erp.low_stock') ?> (<?= count($low_stock_items) ?>)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive"><table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th><?= lang('Erp.item_name') ?></th><th><?= lang('Erp.stock_qty') ?></th><th><?= lang('Erp.min_qty') ?></th></tr></thead>
                    <tbody>
                        <?php foreach (array_slice($low_stock_items, 0, 8) as $item): ?>
                        <tr><td><?= esc($item['nom']) ?></td><td class="text-danger fw-bold"><?= $item['quantite_stock'] ?> <?= $item['unite'] ?></td><td class="text-muted"><?= $item['quantite_min'] ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Projects -->
    <div class="col-lg-<?= !empty($low_stock_items) ? '6' : '12' ?>">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-kanban me-2 text-primary"></i><?= lang('Erp.projects') ?></h6>
                <a href="/erp/projects/create" class="btn btn-sm btn-primary"><?= lang('Erp.new_project') ?></a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recent_projects)): ?>
                <div class="text-center text-muted py-4"><?= lang('Erp.no_records') ?></div>
                <?php else: foreach ($recent_projects as $p):
                    $stColors = ['planning'=>'secondary','active'=>'success','on_hold'=>'warning','completed'=>'primary','cancelled'=>'danger'];
                    $sc = $stColors[$p['statut']] ?? 'secondary';
                ?>
                <div class="px-3 py-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <a href="/erp/projects/<?= $p['id'] ?>/view" class="fw-semibold text-decoration-none"><?= esc($p['nom']) ?></a>
                        <span class="badge bg-<?= $sc ?>"><?= lang('Erp.status_'.$p['statut']) ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-<?= $sc ?>" style="width:<?= $p['avancement'] ?>%"></div></div>
                        <small class="text-muted text-nowrap"><?= $p['avancement'] ?>%</small>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.erp-brand-icon { width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#198754,#20c997);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem; }
.kpi-icon { width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem; }
.kpi-card:hover { transform:translateY(-2px);transition:.2s; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
<?php
$incomeByMonth  = array_fill(1, 12, 0);
$expenseByMonth = array_fill(1, 12, 0);
foreach ($monthly as $row) {
    if ($row['type'] === 'income')  $incomeByMonth[(int)$row['mois']]  = (float)$row['total'];
    if ($row['type'] === 'expense') $expenseByMonth[(int)$row['mois']] = (float)$row['total'];
}
?>
const incomeData  = <?= json_encode(array_values($incomeByMonth)) ?>;
const expenseData = <?= json_encode(array_values($expenseByMonth)) ?>;
new Chart(document.getElementById('financeChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            { label: '<?= lang('Erp.yearly_revenue') ?>', data: incomeData, backgroundColor: 'rgba(25,135,84,.7)', borderRadius: 4 },
            { label: '<?= lang('Erp.yearly_expenses') ?>', data: expenseData, backgroundColor: 'rgba(220,53,69,.7)', borderRadius: 4 },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
});
</script>
<?php $this->endSection(); ?>
