<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center mb-4">
    <a href="/admin/naissance-deces" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h1 class="h3 mb-0">
        <?php if ($evenement['type_evenement'] === 'naissance'): ?>
            <i class="bi bi-stars me-2 text-primary"></i>
        <?php else: ?>
            <i class="bi bi-moon me-2 text-dark"></i>
        <?php endif; ?>
        <?= esc($title) ?>
    </h1>
    <a href="/admin/naissance-deces/<?= $evenement['id'] ?>/edit" class="btn btn-primary ms-auto">
        <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-journal-text me-2"></i><?= lang('NaissanceDeces.event_details') ?? 'Détails de l\'événement' ?>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.event_type') ?></dt>
                    <dd class="col-sm-8">
                        <?php if ($evenement['type_evenement'] === 'naissance'): ?>
                            <span class="badge bg-primary"><i class="bi bi-stars me-1"></i><?= lang('NaissanceDeces.birth') ?></span>
                        <?php else: ?>
                            <span class="badge bg-dark"><i class="bi bi-moon me-1"></i><?= lang('NaissanceDeces.death') ?></span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.name') ?></dt>
                    <dd class="col-sm-8 fw-semibold"><?= esc($evenement['nom']) ?></dd>

                    <?php if ($evenement['prenom']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.firstname') ?></dt>
                    <dd class="col-sm-8"><?= esc($evenement['prenom']) ?></dd>
                    <?php endif; ?>

                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.date') ?></dt>
                    <dd class="col-sm-8">
                        <?= date('d/m/Y', strtotime($evenement['date_evenement'])) ?>
                        <?= $evenement['heure_evenement'] ? ' à <strong>' . substr($evenement['heure_evenement'], 0, 5) . '</strong>' : '' ?>
                    </dd>

                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.place') ?></dt>
                    <dd class="col-sm-8"><?= esc($evenement['lieu'] ?? '—') ?></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.cert_number') ?></dt>
                    <dd class="col-sm-8"><code><?= esc($evenement['numero_certificat'] ?? '—') ?></code></dd>

                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.doctor') ?? 'Médecin' ?></dt>
                    <dd class="col-sm-8"><?= esc($medecinNom ?? '—') ?></dd>

                    <?php if ($evenement['notes']): ?>
                    <dt class="col-sm-4 text-muted"><?= lang('NaissanceDeces.notes') ?? 'Notes' ?></dt>
                    <dd class="col-sm-8"><?= nl2br(esc($evenement['notes'])) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-info-circle me-2"></i><?= lang('Common.summary') ?? 'Résumé' ?>
            </div>
            <div class="card-body text-center py-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3
                    <?= $evenement['type_evenement'] === 'naissance' ? 'bg-primary' : 'bg-dark' ?>"
                    style="width:64px;height:64px">
                    <i class="bi <?= $evenement['type_evenement'] === 'naissance' ? 'bi-stars' : 'bi-moon' ?> fs-2 text-white"></i>
                </div>
                <h5 class="mb-1"><?= esc($evenement['nom'] . ' ' . ($evenement['prenom'] ?? '')) ?></h5>
                <p class="text-muted small mb-0"><?= date('d/m/Y', strtotime($evenement['date_evenement'])) ?></p>
                <?php if ($evenement['lieu']): ?>
                <p class="text-muted small"><?= esc($evenement['lieu']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="/admin/naissance-deces/<?= $evenement['id'] ?>/edit" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
            </a>
            <a href="/admin/naissance-deces" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?? 'Retour à la liste' ?>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
