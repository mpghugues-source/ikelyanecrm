<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $campaign ? lang('Crm.edit_campaign') : lang('Crm.new_campaign') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold"><?= $campaign ? lang('Crm.edit_campaign') : lang('Crm.new_campaign') ?></h1>
    <a href="/crm/campaigns" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Crm.cancel') ?></a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $campaign ? '/crm/campaigns/'.$campaign['id'].'/update' : '/crm/campaigns/store' ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nom de la campagne <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" required value="<?= esc($campaign['nom'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.campaign_type') ?></label>
                    <select name="type" class="form-select">
                        <?php foreach (['email','sms','event','social','other'] as $t): ?>
                        <option value="<?= $t ?>" <?= ($campaign['type']??'email') === $t ? 'selected' : '' ?>><?= lang('Crm.type_'.($t==='email'?'email_camp':$t)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.status') ?></label>
                    <select name="statut" class="form-select">
                        <?php foreach (['draft','active','completed','cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($campaign['statut']??'draft') === $s ? 'selected' : '' ?>><?= lang('Crm.status_'.$s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.budget') ?> (DZD)</label>
                    <input type="number" name="budget" class="form-control" step="0.01" min="0" value="<?= $campaign['budget'] ?? 0 ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Crm.actual_cost') ?> (DZD)</label>
                    <input type="number" name="cout_reel" class="form-control" step="0.01" min="0" value="<?= $campaign['cout_reel'] ?? 0 ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold"><?= lang('Crm.targets') ?></label>
                    <input type="number" name="nb_cibles" class="form-control" min="0" value="<?= $campaign['nb_cibles'] ?? 0 ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold"><?= lang('Crm.responses') ?></label>
                    <input type="number" name="nb_reponses" class="form-control" min="0" value="<?= $campaign['nb_reponses'] ?? 0 ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold"><?= lang('Crm.start_date') ?></label>
                    <input type="date" name="date_debut" class="form-control" value="<?= $campaign['date_debut'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold"><?= lang('Crm.end_date') ?></label>
                    <input type="date" name="date_fin" class="form-control" value="<?= $campaign['date_fin'] ?? '' ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold"><?= lang('Crm.objective') ?></label>
                    <textarea name="objectif" class="form-control" rows="2"><?= esc($campaign['objectif'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= esc($campaign['description'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i><?= lang('Crm.save') ?></button>
                <a href="/crm/campaigns" class="btn btn-outline-secondary"><?= lang('Crm.cancel') ?></a>
            </div>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>
