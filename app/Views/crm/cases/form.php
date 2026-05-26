<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $case ? lang('Crm.edit_case') : lang('Crm.new_case') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold"><?= $case ? lang('Crm.edit_case') : lang('Crm.new_case') ?></h1>
    <a href="/crm/cases" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Crm.cancel') ?></a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $case ? '/crm/cases/'.$case['id'].'/update' : '/crm/cases/store' ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                    <input type="text" name="sujet" class="form-control" required value="<?= esc($case['sujet'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.related_contact') ?></label>
                    <select name="contact_id" class="form-select">
                        <option value="">—</option>
                        <?php foreach ($contacts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($case['contact_id']??'') == $c['id'] ? 'selected' : '' ?>><?= esc($c['prenom'].' '.$c['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.priority') ?></label>
                    <select name="priorite" class="form-select">
                        <?php foreach (['low','medium','high','urgent'] as $p): ?>
                        <option value="<?= $p ?>" <?= ($case['priorite']??'medium') === $p ? 'selected' : '' ?>><?= lang('Crm.priority_'.$p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.status') ?></label>
                    <select name="statut" class="form-select">
                        <?php foreach (['open','in_progress','resolved','closed'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($case['statut']??'open') === $s ? 'selected' : '' ?>><?= lang('Crm.status_'.$s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Type de cas</label>
                    <input type="text" name="type" class="form-control" value="<?= esc($case['type'] ?? '') ?>" placeholder="Réclamation, Demande, Incident...">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?= lang('Crm.assigned_to') ?></label>
                    <select name="assigned_to" class="form-select">
                        <option value="">—</option>
                        <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($case['assigned_to']??'') == $u['id'] ? 'selected' : '' ?>><?= esc($u['prenom'].' '.$u['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= esc($case['description'] ?? '') ?></textarea>
                </div>
                <?php if ($case && in_array($case['statut'], ['resolved','closed'])): ?>
                <div class="col-12">
                    <label class="form-label fw-semibold"><?= lang('Crm.resolution') ?></label>
                    <textarea name="resolution" class="form-control" rows="3"><?= esc($case['resolution'] ?? '') ?></textarea>
                </div>
                <?php endif; ?>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i><?= lang('Crm.save') ?></button>
                <a href="/crm/cases" class="btn btn-outline-secondary"><?= lang('Crm.cancel') ?></a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
