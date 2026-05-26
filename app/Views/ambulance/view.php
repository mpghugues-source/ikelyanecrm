<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/ambulances" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><i class="bi bi-truck me-2 text-danger"></i><?= esc($title) ?></h1>
    <a href="/admin/ambulances/<?= $vehicule['id'] ?>/edit" class="btn btn-primary ms-auto">
        <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
    </a>
</div>

<?php
$statColors = ['disponible'=>'success','en_mission'=>'warning','maintenance'=>'info','hors_service'=>'danger'];
$sc = $statColors[$vehicule['statut']] ?? 'secondary';
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-truck me-2 text-danger"></i><?= lang('Ambulance.vehicle_info') ?? 'Informations du véhicule' ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= lang('Ambulance.plate') ?? 'Immatriculation' ?></dt>
                    <dd class="col-sm-8 fw-bold fs-5"><?= esc($vehicule['immatriculation']) ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Ambulance.vehicle_type') ?? 'Type' ?></dt>
                    <dd class="col-sm-8"><?= esc($vehicule['type_vehicule'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Ambulance.model') ?? 'Modèle' ?></dt>
                    <dd class="col-sm-8"><?= esc($vehicule['modele'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Common.status') ?></dt>
                    <dd class="col-sm-8"><span class="badge bg-<?= $sc ?>"><?= lang('Ambulance.statuses.' . $vehicule['statut']) ?></span></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Ambulance.driver_name') ?? 'Chauffeur' ?></dt>
                    <dd class="col-sm-8"><?= esc($vehicule['chauffeur_nom'] ?? '—') ?></dd>

                    <?php if ($vehicule['chauffeur_tel']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('Ambulance.driver_phone') ?? 'Tél. chauffeur' ?></dt>
                    <dd class="col-sm-8">
                        <a href="tel:<?= esc($vehicule['chauffeur_tel']) ?>"><?= esc($vehicule['chauffeur_tel']) ?></a>
                    </dd>
                    <?php endif; ?>

                    <dt class="col-sm-4 text-muted"><?= lang('Ambulance.revision_date') ?></dt>
                    <dd class="col-sm-8">
                        <?php if ($vehicule['date_revision']): ?>
                            <?php
                            $revDate = new DateTime($vehicule['date_revision']);
                            $today   = new DateTime();
                            $overdue = $revDate < $today;
                            ?>
                            <span class="<?= $overdue ? 'text-danger fw-bold' : '' ?>">
                                <?= date('d/m/Y', strtotime($vehicule['date_revision'])) ?>
                                <?= $overdue ? ' <span class="badge bg-danger">Dépassée</span>' : '' ?>
                            </span>
                        <?php else: ?>—<?php endif; ?>
                    </dd>

                    <?php if ($vehicule['notes']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('Ambulance.notes') ?? 'Notes' ?></dt>
                    <dd class="col-sm-8"><?= nl2br(esc($vehicule['notes'])) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-activity me-2 text-danger"></i><?= lang('Common.status') ?>
            </div>
            <div class="card-body text-center py-4">
                <span class="badge bg-<?= $sc ?> fs-5 px-4 py-2"><?= lang('Ambulance.statuses.' . $vehicule['statut']) ?></span>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="/admin/ambulances/<?= $vehicule['id'] ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
            </a>
            <a href="/admin/ambulances" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?? 'Retour à la liste' ?>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
