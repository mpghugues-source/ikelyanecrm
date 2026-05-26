<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $lead ? lang('Crm.edit_lead') : lang('Crm.new_lead') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><?= $lead ? lang('Crm.edit_lead') : lang('Crm.new_lead') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item"><a href="/crm/leads"><?= lang('Crm.leads') ?></a></li><li class="breadcrumb-item active"><?= $lead ? lang('Crm.edit_lead') : lang('Crm.new_lead') ?></li></ol></nav>
    </div>
    <a href="/crm/leads" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Crm.cancel') ?></a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $lead ? '/crm/leads/'.$lead['id'].'/update' : '/crm/leads/store' ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold"><?= lang('Crm.lead') ?> — Titre <span class="text-danger">*</span></label>
                    <input type="text" name="titre" class="form-control" required value="<?= esc($lead['titre'] ?? '') ?>" placeholder="Ex: Équipement Bloc opératoire — Clinique Atlas">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?= lang('Crm.related_contact') ?></label>
                    <select name="contact_id" class="form-select">
                        <option value="">— <?= lang('Crm.all') ?> —</option>
                        <?php foreach ($contacts as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($lead['contact_id']??'') == $c['id'] ? 'selected' : '' ?>><?= esc($c['prenom'].' '.$c['nom']) ?> <?= $c['entreprise'] ? '('.$c['entreprise'].')' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold"><?= lang('Crm.assigned_to') ?></label>
                    <select name="assigned_to" class="form-select">
                        <option value="">— Non assigné —</option>
                        <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($lead['assigned_to']??'') == $u['id'] ? 'selected' : '' ?>><?= esc($u['prenom'].' '.$u['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.est_value') ?></label>
                    <div class="input-group">
                        <input type="number" name="valeur_estimee" class="form-control" step="0.01" min="0" value="<?= $lead['valeur_estimee'] ?? 0 ?>">
                        <select name="devise" class="form-select" style="max-width:90px">
                            <option value="DZD" <?= ($lead['devise']??'DZD') === 'DZD' ? 'selected' : '' ?>>DZD</option>
                            <option value="EUR" <?= ($lead['devise']??'') === 'EUR' ? 'selected' : '' ?>>EUR</option>
                            <option value="USD" <?= ($lead['devise']??'') === 'USD' ? 'selected' : '' ?>>USD</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.probability') ?></label>
                    <div class="input-group">
                        <input type="range" name="probabilite" class="form-range mt-2" min="0" max="100" step="5" value="<?= $lead['probabilite'] ?? 50 ?>" oninput="document.getElementById('probVal').textContent=this.value+'%'">
                        <span class="input-group-text" id="probVal"><?= ($lead['probabilite']??50) ?>%</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.status') ?></label>
                    <select name="statut" class="form-select">
                        <?php foreach (['new','qualified','proposition','negotiation','won','lost'] as $st): ?>
                        <option value="<?= $st ?>" <?= ($lead['statut']??'new') === $st ? 'selected' : '' ?>><?= lang('Crm.stage_'.$st) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.close_date') ?></label>
                    <input type="date" name="date_cloture_prevue" class="form-control" value="<?= $lead['date_cloture_prevue'] ?? '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.source') ?></label>
                    <input type="text" name="source" class="form-control" value="<?= esc($lead['source'] ?? '') ?>" placeholder="Web, Référence...">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.loss_reason') ?></label>
                    <input type="text" name="raison_perte" class="form-control" value="<?= esc($lead['raison_perte'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold"><?= lang('Crm.notes') ?></label>
                    <textarea name="notes" class="form-control" rows="3"><?= esc($lead['notes'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-check-lg me-1"></i><?= lang('Crm.save') ?></button>
                <a href="/crm/leads" class="btn btn-outline-secondary"><?= lang('Crm.cancel') ?></a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
