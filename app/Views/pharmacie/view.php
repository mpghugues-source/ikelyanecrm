<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/pharmacie" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0"><i class="bi bi-capsule me-2 text-success"></i><?= esc($title) ?></h1>
    <a href="/admin/pharmacie/<?= $medicament['id'] ?>/edit" class="btn btn-primary ms-auto">
        <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-info-circle me-2 text-success"></i><?= lang('Pharmacie.details') ?? 'Informations générales' ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= lang('Pharmacie.name') ?></dt>
                    <dd class="col-sm-8 fw-semibold"><?= esc($medicament['nom']) ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Pharmacie.category') ?></dt>
                    <dd class="col-sm-8"><?= esc($medicament['categorie'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Pharmacie.form') ?></dt>
                    <dd class="col-sm-8"><?= esc($medicament['forme'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Pharmacie.dosage') ?></dt>
                    <dd class="col-sm-8"><?= esc($medicament['dosage'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Pharmacie.supplier') ?></dt>
                    <dd class="col-sm-8"><?= esc($medicament['fournisseur'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('Pharmacie.expiry_date') ?></dt>
                    <dd class="col-sm-8">
                        <?php if ($medicament['date_expiration']): ?>
                            <?php
                            $expDate = new DateTime($medicament['date_expiration']);
                            $today   = new DateTime();
                            $expired = $expDate < $today;
                            $soonExp = !$expired && $expDate->diff($today)->days <= 30;
                            ?>
                            <span class="<?= $expired ? 'text-danger fw-bold' : ($soonExp ? 'text-warning fw-bold' : '') ?>">
                                <?= date('d/m/Y', strtotime($medicament['date_expiration'])) ?>
                                <?= $expired ? ' <span class="badge bg-danger">Expiré</span>' : ($soonExp ? ' <span class="badge bg-warning text-dark">Bientôt</span>' : '') ?>
                            </span>
                        <?php else: ?>—<?php endif; ?>
                    </dd>

                    <?php if ($medicament['notes']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('Pharmacie.notes') ?></dt>
                    <dd class="col-sm-8"><?= nl2br(esc($medicament['notes'])) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-boxes me-2 text-success"></i><?= lang('Pharmacie.stock_info') ?? 'Gestion du stock' ?>
            </div>
            <div class="card-body text-center">
                <?php $lowStock = $medicament['stock_actuel'] <= $medicament['stock_minimum']; ?>
                <div class="display-4 fw-bold <?= $lowStock ? 'text-danger' : 'text-success' ?>">
                    <?= $medicament['stock_actuel'] ?>
                </div>
                <p class="text-muted small mb-3"><?= lang('Pharmacie.current_stock') ?></p>
                <?php if ($lowStock): ?>
                <div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-triangle me-1"></i><?= lang('Pharmacie.low_stock_alert', [$medicament['stock_minimum']]) ?? 'Stock bas (min. ' . $medicament['stock_minimum'] . ')' ?></div>
                <?php endif; ?>
                <hr>
                <div class="d-flex justify-content-between text-sm">
                    <span class="text-muted"><?= lang('Pharmacie.min_stock') ?></span>
                    <strong><?= $medicament['stock_minimum'] ?></strong>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted"><?= lang('Pharmacie.unit_price') ?></span>
                    <strong><?= number_format($medicament['prix_unitaire'], 2) ?> DA</strong>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="/admin/pharmacie/<?= $medicament['id'] ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
            </a>
            <a href="/admin/pharmacie" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?? 'Retour à la liste' ?>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
