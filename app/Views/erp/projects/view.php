<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= esc($project['nom']) ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><?= esc($project['nom']) ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item"><a href="/erp/projects"><?= lang('Erp.projects') ?></a></li><li class="breadcrumb-item active"><?= esc($project['nom']) ?></li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="/erp/projects/<?= $project['id'] ?>/edit" class="btn btn-primary"><i class="bi bi-pencil me-1"></i><?= lang('Erp.edit') ?></a>
        <a href="/erp/projects" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i></a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <?php $stColors = ['planning'=>'secondary','active'=>'success','on_hold'=>'warning','completed'=>'primary','cancelled'=>'danger']; $sc = $stColors[$project['statut']] ?? 'secondary'; ?>
                <div class="mb-3">
                    <span class="badge bg-<?= $sc ?> mb-2"><?= lang('Erp.status_'.$project['statut']) ?></span>
                    <div class="d-flex justify-content-between small mb-1"><span><?= lang('Erp.progress') ?></span><span><?= $project['avancement'] ?>%</span></div>
                    <div class="progress" style="height:10px"><div class="progress-bar bg-<?= $sc ?>" style="width:<?= $project['avancement'] ?>%;border-radius:5px"></div></div>
                </div>
                <hr>
                <dl class="row g-1 small mb-0">
                    <?php if ($project['chef_nom']): ?><dt class="col-5 text-muted"><?= lang('Erp.project_manager') ?></dt><dd class="col-7 fw-semibold"><?= esc($project['chef_nom']) ?></dd><?php endif; ?>
                    <?php if ($project['date_debut']): ?><dt class="col-5 text-muted"><?= lang('Erp.start_date') ?></dt><dd class="col-7"><?= date('d/m/Y', strtotime($project['date_debut'])) ?></dd><?php endif; ?>
                    <?php if ($project['date_fin']): ?><dt class="col-5 text-muted"><?= lang('Erp.end_date') ?></dt><dd class="col-7"><?= date('d/m/Y', strtotime($project['date_fin'])) ?></dd><?php endif; ?>
                    <?php if ($project['budget'] > 0): ?>
                    <dt class="col-5 text-muted"><?= lang('Erp.budget') ?></dt><dd class="col-7 fw-semibold"><?= number_format($project['budget'],0,'.',',') ?> DZD</dd>
                    <dt class="col-5 text-muted"><?= lang('Erp.actual_cost') ?></dt><dd class="col-7 fw-semibold text-<?= $project['cout_reel'] > $project['budget'] ? 'danger' : 'success' ?>"><?= number_format($project['cout_reel'],0,'.',',') ?> DZD</dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-list-check me-2"></i>Tâches (<?= count($tasks) ?>)</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal"><i class="bi bi-plus me-1"></i><?= lang('Erp.new_task') ?></button>
            </div>
            <div class="card-body p-0">
                <?php if (empty($tasks)): ?>
                <div class="text-center py-4 text-muted"><i class="bi bi-list-check fs-3 d-block mb-2"></i><?= lang('Admin.no_tasks') ?></div>
                <?php else:
                $taskStColors = ['todo'=>'secondary','in_progress'=>'warning','done'=>'success','cancelled'=>'danger'];
                foreach ($tasks as $task): $tc = $taskStColors[$task['statut']] ?? 'secondary'; ?>
                <div class="list-group-item border-0 border-bottom px-3 py-2 d-flex align-items-start gap-3">
                    <div class="mt-1">
                        <input type="checkbox" class="form-check-input" <?= $task['statut'] === 'done' ? 'checked disabled' : '' ?>>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-semibold <?= $task['statut'] === 'done' ? 'text-decoration-line-through text-muted' : '' ?>"><?= esc($task['titre']) ?></span>
                            <span class="badge bg-<?= $tc ?> ms-2 flex-shrink-0"><?= lang('Erp.status_'.$task['statut']) ?></span>
                        </div>
                        <small class="text-muted">
                            <?= $task['assigned_name'] ? esc($task['assigned_name']) : '' ?>
                            <?= $task['date_echeance'] ? ' · '.date('d/m/Y', strtotime($task['date_echeance'])) : '' ?>
                        </small>
                    </div>
                    <div class="btn-group btn-group-sm flex-shrink-0">
                        <?php if ($task['statut'] !== 'done'): ?>
                        <form action="/erp/projects/<?= $project['id'] ?>/tasks/<?= $task['id'] ?>/update" method="post" class="d-inline">
                            <?= csrf_field() ?><input type="hidden" name="titre" value="<?= esc($task['titre']) ?>"><input type="hidden" name="statut" value="done"><input type="hidden" name="priorite" value="<?= $task['priorite'] ?>">
                            <button type="submit" class="btn btn-outline-success" title="Terminer"><i class="bi bi-check"></i></button>
                        </form>
                        <?php endif; ?>
                        <a href="/erp/projects/<?= $project['id'] ?>/tasks/<?= $task['id'] ?>/delete" class="btn btn-outline-danger" onclick="return confirm('?')"><i class="bi bi-trash"></i></a>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><?= lang('Erp.new_task') ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="/erp/projects/<?= $project['id'] ?>/tasks/store" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold"><?= lang('Erp.task_title') ?> <span class="text-danger">*</span></label><input type="text" name="titre" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.priority') ?></label>
                            <select name="priorite" class="form-select">
                                <option value="low"><?= lang('Erp.priority_low') ?></option>
                                <option value="medium" selected><?= lang('Erp.priority_medium') ?></option>
                                <option value="high"><?= lang('Erp.priority_high') ?></option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.status') ?></label>
                            <select name="statut" class="form-select">
                                <option value="todo"><?= lang('Erp.status_todo') ?></option>
                                <option value="in_progress"><?= lang('Erp.status_in_progress') ?></option>
                                <option value="done"><?= lang('Erp.status_done') ?></option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.due_date') ?></label><input type="date" name="date_echeance" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.assigned_to') ?></label>
                            <select name="assigned_to" class="form-select">
                                <option value="">—</option>
                                <?php foreach ($users as $u): ?><option value="<?= $u['id'] ?>"><?= esc($u['prenom'].' '.$u['nom']) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold"><?= lang('Erp.est_hours') ?></label><input type="number" name="heures_estimees" class="form-control" step="0.5" min="0" value="0"></div>
                        <div class="col-12"><label class="form-label fw-semibold"><?= lang('Common.description') ?></label><textarea name="description" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= lang('Erp.cancel') ?></button>
                    <button type="submit" class="btn btn-primary"><?= lang('Erp.save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
