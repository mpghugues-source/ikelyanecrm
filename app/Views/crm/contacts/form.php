<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= $contact ? lang('Crm.edit_contact') : lang('Crm.new_contact') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><?= $contact ? lang('Crm.edit_contact') : lang('Crm.new_contact') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="/crm">CRM</a></li>
            <li class="breadcrumb-item"><a href="/crm/contacts"><?= lang('Crm.contacts') ?></a></li>
            <li class="breadcrumb-item active"><?= $contact ? lang('Crm.edit_contact') : lang('Crm.new_contact') ?></li>
        </ol></nav>
    </div>
    <a href="/crm/contacts" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Crm.cancel') ?></a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="<?= $contact ? '/crm/contacts/'.$contact['id'].'/update' : '/crm/contacts/store' ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><?= lang('Crm.contact_type') ?> <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <?php foreach (['lead','contact','patient','supplier','partner'] as $t): ?>
                                <option value="<?= $t ?>" <?= ($contact['type']??'contact') === $t ? 'selected' : '' ?>><?= lang('Crm.type_'.$t) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><?= lang('Crm.first_name') ?></label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc($contact['prenom'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><?= lang('Crm.last_name') ?> <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required value="<?= esc($contact['nom'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= esc($contact['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Crm.notes') ?> — Tél.</label>
                            <input type="text" name="telephone" class="form-control" value="<?= esc($contact['telephone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Crm.company') ?></label>
                            <input type="text" name="entreprise" class="form-control" value="<?= esc($contact['entreprise'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Crm.position') ?></label>
                            <input type="text" name="poste" class="form-control" value="<?= esc($contact['poste'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Crm.city') ?></label>
                            <input type="text" name="ville" class="form-control" value="<?= esc($contact['ville'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Crm.source') ?></label>
                            <input type="text" name="source" class="form-control" placeholder="Web, Référence, Publicité..." value="<?= esc($contact['source'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Crm.status') ?></label>
                            <select name="statut" class="form-select">
                                <option value="active" <?= ($contact['statut']??'active') === 'active' ? 'selected' : '' ?>><?= lang('Crm.status_active') ?></option>
                                <option value="inactive" <?= ($contact['statut']??'') === 'inactive' ? 'selected' : '' ?>><?= lang('Crm.status_inactive') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><?= lang('Crm.country') ?></label>
                            <input type="text" name="pays" class="form-control" value="<?= esc($contact['pays'] ?? 'Algérie') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold"><?= lang('Crm.address') ?></label>
                            <textarea name="adresse" class="form-control" rows="2"><?= esc($contact['adresse'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold"><?= lang('Crm.notes') ?></label>
                            <textarea name="notes" class="form-control" rows="3"><?= esc($contact['notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i><?= lang('Crm.save') ?></button>
                        <a href="/crm/contacts" class="btn btn-outline-secondary"><?= lang('Crm.cancel') ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Guide</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><span class="badge bg-warning-subtle text-warning me-1"><?= lang('Crm.type_lead') ?></span><?= lang('Crm.type_lead') ?> — prospect non encore qualifié</li>
                    <li class="mb-2"><span class="badge bg-primary-subtle text-primary me-1"><?= lang('Crm.type_contact') ?></span>Contact commercial actif</li>
                    <li class="mb-2"><span class="badge bg-info-subtle text-info me-1"><?= lang('Crm.type_patient') ?></span>Patient de la clinique</li>
                    <li class="mb-2"><span class="badge bg-secondary-subtle text-secondary me-1"><?= lang('Crm.type_supplier') ?></span>Fournisseur / prestataire</li>
                    <li><span class="badge bg-success-subtle text-success me-1"><?= lang('Crm.type_partner') ?></span>Partenaire stratégique</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
