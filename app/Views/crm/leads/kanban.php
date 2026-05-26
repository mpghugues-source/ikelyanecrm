<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Crm.kanban') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-kanban-fill text-warning me-2"></i><?= lang('Crm.kanban') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item"><a href="/crm/leads"><?= lang('Crm.leads') ?></a></li><li class="breadcrumb-item active"><?= lang('Crm.kanban') ?></li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="/crm/leads" class="btn btn-outline-secondary"><i class="bi bi-list-ul me-1"></i>Liste</a>
        <a href="/crm/leads/create" class="btn btn-warning"><i class="bi bi-plus-lg me-1"></i><?= lang('Crm.new_lead') ?></a>
    </div>
</div>

<?php
$stages = ['new'=>['label'=>lang('Crm.stage_new'),'color'=>'secondary','bg'=>'#6c757d'],
           'qualified'=>['label'=>lang('Crm.stage_qualified'),'color'=>'info','bg'=>'#0dcaf0'],
           'proposition'=>['label'=>lang('Crm.stage_proposition'),'color'=>'primary','bg'=>'#0d6efd'],
           'negotiation'=>['label'=>lang('Crm.stage_negotiation'),'color'=>'warning','bg'=>'#ffc107'],
           'won'=>['label'=>lang('Crm.stage_won'),'color'=>'success','bg'=>'#198754'],
           'lost'=>['label'=>lang('Crm.stage_lost'),'color'=>'danger','bg'=>'#dc3545']];
?>

<div class="kanban-board d-flex gap-3 overflow-auto pb-3">
    <?php foreach ($stages as $key => $s): ?>
    <div class="kanban-column flex-shrink-0" style="width:280px">
        <div class="kanban-header rounded-top p-2 text-white text-center fw-semibold mb-2" style="background:<?= $s['bg'] ?>">
            <?= $s['label'] ?>
            <span class="badge bg-white text-dark ms-2"><?= count($board[$key] ?? []) ?></span>
        </div>
        <div class="kanban-cards" data-stage="<?= $key ?>">
            <?php foreach ($board[$key] ?? [] as $lead): ?>
            <div class="card border-0 shadow-sm mb-2 kanban-card" draggable="true" data-id="<?= $lead['id'] ?>">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0 fw-semibold small"><?= esc($lead['titre']) ?></h6>
                        <a href="/crm/leads/<?= $lead['id'] ?>/edit" class="text-muted"><i class="bi bi-pencil-square" style="font-size:.7rem"></i></a>
                    </div>
                    <?php if ($lead['contact_name']): ?>
                    <div class="text-muted small mb-1"><i class="bi bi-person me-1"></i><?= esc($lead['contact_name']) ?></div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-bold text-success small"><?= number_format($lead['valeur_estimee'],0,'.',',') ?> <?= $lead['devise'] ?></span>
                        <div class="progress" style="width:50px;height:5px"><div class="progress-bar bg-<?= $s['color'] ?>" style="width:<?= $lead['probabilite'] ?>%"></div></div>
                        <small class="text-muted"><?= $lead['probabilite'] ?>%</small>
                    </div>
                    <?php if ($lead['date_cloture_prevue']): ?>
                    <div class="text-muted" style="font-size:.7rem"><i class="bi bi-calendar me-1"></i><?= date('d/m/Y', strtotime($lead['date_cloture_prevue'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
.kanban-board { min-height: 500px; }
.kanban-column { min-height: 200px; }
.kanban-cards { min-height: 100px; background: rgba(0,0,0,.03); border-radius: 8px; padding: 8px; }
.kanban-card { cursor: grab; border-left: 4px solid transparent !important; transition: .2s; }
.kanban-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.15) !important; }
.kanban-card.dragging { opacity: .5; cursor: grabbing; }
.kanban-cards.drag-over { background: rgba(13,110,253,.1); border: 2px dashed #0d6efd; }
</style>

<script>
let draggedId = null;
document.querySelectorAll('.kanban-card').forEach(card => {
    card.addEventListener('dragstart', e => { draggedId = card.dataset.id; card.classList.add('dragging'); });
    card.addEventListener('dragend', e => { card.classList.remove('dragging'); });
});
document.querySelectorAll('.kanban-cards').forEach(col => {
    col.addEventListener('dragover', e => { e.preventDefault(); col.classList.add('drag-over'); });
    col.addEventListener('dragleave', () => col.classList.remove('drag-over'));
    col.addEventListener('drop', e => {
        e.preventDefault();
        col.classList.remove('drag-over');
        const stage = col.dataset.stage;
        if (draggedId) {
            fetch('/crm/leads/update-status', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
                body: new URLSearchParams({id: draggedId, statut: stage, '<?= csrf_token() ?>': '<?= csrf_hash() ?>'})
            }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
        }
    });
});
</script>
<?php $this->endSection(); ?>
