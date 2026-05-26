<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $project ? lang('Erp.edit_project') : lang('Erp.new_project') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold"><?= $project ? lang('Erp.edit_project') : lang('Erp.new_project') ?></h1>
    <a href="/erp/projects" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Erp.cancel') ?></a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $project ? '/erp/projects/'.$project['id'].'/update' : '/erp/projects/store' ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-12"><label class="form-label fw-semibold"><?= lang('Erp.project') ?> — Nom <span class="text-danger">*</span></label><input type="text" name="nom" class="form-control" required value="<?= esc($project['nom'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.status') ?></label>
                    <select name="statut" class="form-select">
                        <?php foreach (['planning','active','on_hold','completed','cancelled'] as $s): ?><option value="<?= $s ?>" <?= ($project['statut']??'planning') === $s ? 'selected' : '' ?>><?= lang('Erp.status_'.$s) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.priority') ?></label>
                    <select name="priorite" class="form-select">
                        <?php foreach (['low','medium','high','critical'] as $p): ?><option value="<?= $p ?>" <?= ($project['priorite']??'medium') === $p ? 'selected' : '' ?>><?= lang('Erp.priority_'.$p) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.project_manager') ?></label>
                    <select name="chef_projet" class="form-select">
                        <option value="">—</option>
                        <?php foreach ($users as $u): ?><option value="<?= $u['id'] ?>" <?= ($project['chef_projet']??'') == $u['id'] ? 'selected' : '' ?>><?= esc($u['prenom'].' '.$u['nom']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.start_date') ?></label><input type="date" name="date_debut" class="form-control" value="<?= $project['date_debut'] ?? '' ?>"></div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.end_date') ?></label><input type="date" name="date_fin" class="form-control" value="<?= $project['date_fin'] ?? '' ?>"></div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.budget') ?> (DZD)</label><input type="number" name="budget" class="form-control" step="0.01" value="<?= $project['budget'] ?? 0 ?>"></div>
                <div class="col-md-3"><label class="form-label fw-semibold"><?= lang('Erp.actual_cost') ?> (DZD)</label><input type="number" name="cout_reel" class="form-control" step="0.01" value="<?= $project['cout_reel'] ?? 0 ?>"></div>
                <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="4"><?= esc($project['description'] ?? '') ?></textarea></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Erp.save') ?></button>
                <a href="/erp/projects" class="btn btn-outline-secondary"><?= lang('Erp.cancel') ?></a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
