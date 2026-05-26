<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $activity ? lang('Crm.edit_activity') : lang('Crm.new_activity') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold"><?= $activity ? lang('Crm.edit_activity') : lang('Crm.new_activity') ?></h1>
    <a href="/crm/activities" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Crm.cancel') ?></a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $activity ? '/crm/activities/'.$activity['id'].'/update' : '/crm/activities/store' ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <?php foreach (['call','email','meeting','task','note'] as $t): ?>
                        <option value="<?= $t ?>" <?= ($activity['type']??'task') === $t ? 'selected' : '' ?>><?= lang('Crm.type_'.$t) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold"><?= lang('Crm.subject') ?> <span class="text-danger">*</span></label>
                    <input type="text" name="sujet" class="form-control" required value="<?= esc($activity['sujet'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?= lang('Crm.start_date') ?></label>
                    <input type="datetime-local" name="date_debut" class="form-control" value="<?= $activity['date_debut'] ? date('Y-m-d\TH:i', strtotime($activity['date_debut'])) : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?= lang('Crm.end_date') ?></label>
                    <input type="datetime-local" name="date_fin" class="form-control" value="<?= $activity['date_fin'] ? date('Y-m-d\TH:i', strtotime($activity['date_fin'])) : '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.priority') ?></label>
                    <select name="priorite" class="form-select">
                        <?php foreach (['low','medium','high'] as $p): ?>
                        <option value="<?= $p ?>" <?= ($activity['priorite']??'medium') === $p ? 'selected' : '' ?>><?= lang('Crm.priority_'.$p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.status') ?></label>
                    <select name="statut" class="form-select">
                        <?php foreach (['planned','completed','cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($activity['statut']??'planned') === $s ? 'selected' : '' ?>><?= lang('Crm.status_'.$s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.assigned_to') ?></label>
                    <select name="assigned_to" class="form-select">
                        <option value="">— Non assigné —</option>
                        <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($activity['assigned_to']??'') == $u['id'] ? 'selected' : '' ?>><?= esc($u['prenom'].' '.$u['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?= lang('Crm.related_contact') ?></label>
                    <select name="contact_id" class="form-select">
                        <option value="">—</option>
                        <?php foreach ($contacts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($activity['contact_id']??'') == $c['id'] ? 'selected' : '' ?>><?= esc($c['prenom'].' '.$c['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?= lang('Crm.related_lead') ?></label>
                    <select name="lead_id" class="form-select">
                        <option value="">—</option>
                        <?php foreach ($leads as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= ($activity['lead_id']??'') == $l['id'] ? 'selected' : '' ?>><?= esc($l['titre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold"><?= lang('Crm.notes') ?></label>
                    <textarea name="description" class="form-control" rows="3"><?= esc($activity['description'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Crm.save') ?></button>
                <a href="/crm/activities" class="btn btn-outline-secondary"><?= lang('Crm.cancel') ?></a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
