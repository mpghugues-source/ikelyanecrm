<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/rh" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><i class="bi bi-person-badge me-2 text-secondary"></i><?= esc($title) ?></h1>
    <a href="/admin/rh/<?= $employe['id'] ?>/edit" class="btn btn-primary ms-auto">
        <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-person me-2 text-secondary"></i><?= lang('RH.employee_info') ?? 'Informations employé' ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= lang('RH.name') ?></dt>
                    <dd class="col-sm-8 fw-semibold"><?= esc($employe['nom']) ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('RH.firstname') ?></dt>
                    <dd class="col-sm-8"><?= esc($employe['prenom']) ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('RH.position') ?></dt>
                    <dd class="col-sm-8"><?= esc($employe['poste']) ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('RH.department') ?></dt>
                    <dd class="col-sm-8"><?= esc($employe['departement'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('RH.contract') ?></dt>
                    <dd class="col-sm-8"><span class="badge bg-secondary"><?= esc($employe['contrat'] ?? '—') ?></span></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('RH.hire_date') ?></dt>
                    <dd class="col-sm-8"><?= $employe['date_embauche'] ? date('d/m/Y', strtotime($employe['date_embauche'])) : '—' ?></dd>

                    <?php if ($employe['salaire']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('RH.salary') ?? 'Salaire' ?></dt>
                    <dd class="col-sm-8"><?= number_format($employe['salaire'], 2, ',', ' ') ?> DA</dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-telephone me-2 text-secondary"></i><?= lang('RH.contact') ?? 'Contact' ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= lang('Common.phone') ?></dt>
                    <dd class="col-sm-8">
                        <?php if ($employe['telephone']): ?>
                            <a href="tel:<?= esc($employe['telephone']) ?>"><?= esc($employe['telephone']) ?></a>
                        <?php else: ?>—<?php endif; ?>
                    </dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Common.email') ?></dt>
                    <dd class="col-sm-8">
                        <?php if ($employe['email']): ?>
                            <a href="mailto:<?= esc($employe['email']) ?>"><?= esc($employe['email']) ?></a>
                        <?php else: ?>—<?php endif; ?>
                    </dd>

                    <?php if ($employe['notes']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('RH.notes') ?? 'Notes' ?></dt>
                    <dd class="col-sm-8"><?= nl2br(esc($employe['notes'])) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-briefcase me-2 text-secondary"></i><?= lang('RH.position') ?>
            </div>
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px">
                    <i class="bi bi-person-fill fs-2 text-secondary"></i>
                </div>
                <h5 class="mb-1"><?= esc($employe['prenom'] . ' ' . $employe['nom']) ?></h5>
                <p class="text-muted mb-1"><?= esc($employe['poste']) ?></p>
                <?php if ($employe['departement']): ?>
                <small class="text-muted"><?= esc($employe['departement']) ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="/admin/rh/<?= $employe['id'] ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
            </a>
            <a href="/admin/rh" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?? 'Retour à la liste' ?>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
