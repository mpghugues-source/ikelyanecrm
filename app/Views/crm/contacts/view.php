<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= esc($contact['prenom'].' '.$contact['nom']) ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><?= esc($contact['prenom'].' '.$contact['nom']) ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item"><a href="/crm/contacts"><?= lang('Crm.contacts') ?></a></li><li class="breadcrumb-item active"><?= esc($contact['nom']) ?></li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="/crm/contacts/<?= $contact['id'] ?>/edit" class="btn btn-primary"><i class="bi bi-pencil me-1"></i><?= lang('Crm.edit') ?></a>
        <a href="/crm/contacts" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i></a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center p-4">
            <?php $typeColors = ['lead'=>'warning','contact'=>'primary','patient'=>'info','supplier'=>'secondary','partner'=>'success']; $tc = $typeColors[$contact['type']] ?? 'secondary'; ?>
            <div class="mx-auto mb-3 rounded-circle bg-<?= $tc ?>-subtle text-<?= $tc ?> d-flex align-items-center justify-content-center fw-bold fs-2" style="width:80px;height:80px">
                <?= strtoupper(substr($contact['prenom']??'',0,1).substr($contact['nom'],0,1)) ?>
            </div>
            <h5 class="mb-1"><?= esc($contact['prenom'].' '.$contact['nom']) ?></h5>
            <span class="badge bg-<?= $tc ?> mb-2"><?= lang('Crm.type_'.$contact['type']) ?></span>
            <?php if ($contact['poste']): ?><div class="text-muted small"><?= esc($contact['poste']) ?></div><?php endif; ?>
            <?php if ($contact['entreprise']): ?><div class="fw-semibold"><?= esc($contact['entreprise']) ?></div><?php endif; ?>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3 text-muted text-uppercase small">Coordonnées</h6>
                <div class="row g-3">
                    <?php if ($contact['email']): ?><div class="col-md-6"><small class="text-muted d-block">Email</small><a href="mailto:<?= esc($contact['email']) ?>"><?= esc($contact['email']) ?></a></div><?php endif; ?>
                    <?php if ($contact['telephone']): ?><div class="col-md-6"><small class="text-muted d-block">Téléphone</small><?= esc($contact['telephone']) ?></div><?php endif; ?>
                    <?php if ($contact['ville']): ?><div class="col-md-6"><small class="text-muted d-block"><?= lang('Crm.city') ?></small><?= esc($contact['ville']) ?></div><?php endif; ?>
                    <?php if ($contact['pays']): ?><div class="col-md-6"><small class="text-muted d-block"><?= lang('Crm.country') ?></small><?= esc($contact['pays']) ?></div><?php endif; ?>
                    <?php if ($contact['source']): ?><div class="col-md-6"><small class="text-muted d-block"><?= lang('Crm.source') ?></small><?= esc($contact['source']) ?></div><?php endif; ?>
                    <?php if ($contact['notes']): ?><div class="col-12"><small class="text-muted d-block"><?= lang('Crm.notes') ?></small><p class="mb-0"><?= nl2br(esc($contact['notes'])) ?></p></div><?php endif; ?>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <a href="/crm/activities/create" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus me-1"></i><?= lang('Crm.new_activity') ?></a>
                    <a href="/crm/leads/create" class="btn btn-sm btn-outline-warning"><i class="bi bi-plus me-1"></i><?= lang('Crm.new_lead') ?></a>
                    <a href="/crm/cases/create" class="btn btn-sm btn-outline-danger"><i class="bi bi-plus me-1"></i><?= lang('Crm.new_case') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
