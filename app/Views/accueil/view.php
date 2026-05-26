<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$opColors   = ['admission'=>'primary','sortie'=>'success','consultation'=>'info','visite'=>'secondary','urgence'=>'danger'];
$statColors = ['en_attente'=>'warning','en_cours'=>'info','termine'=>'success'];
$op = $operation;
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="/admin/accueil" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h1 class="h3 mb-0"><i class="bi bi-door-open me-2 text-primary"></i><?= esc($title) ?></h1>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/accueil/<?= $op['id'] ?>/edit" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i><?= lang('Common.edit') ?>
        </a>
        <form method="POST" action="/admin/accueil/<?= $op['id'] ?>/delete" class="d-inline"
              onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
            <?= csrf_field() ?>
            <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i><?= lang('Common.delete') ?></button>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Informations principales -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:14px">
            <div class="card-header border-0 py-3 px-4 bg-white d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-700"><i class="bi bi-info-circle me-2 text-primary"></i><?= lang('Accueil.operation_type') ?></h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-<?= $opColors[$op['type_operation']] ?? 'secondary' ?> px-3 py-2" style="font-size:.85rem">
                        <?= lang('Accueil.types.' . $op['type_operation']) ?>
                    </span>
                    <span class="badge bg-<?= $statColors[$op['statut']] ?? 'secondary' ?> px-3 py-2" style="font-size:.85rem">
                        <?= lang('Accueil.statuses.' . $op['statut']) ?>
                    </span>
                </div>
            </div>
            <div class="card-body px-4 py-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">
                            <?= lang('Accueil.datetime') ?>
                        </div>
                        <div class="fw-600"><?= date('d/m/Y à H:i', strtotime($op['date_heure'])) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">
                            <?= lang('Accueil.room') ?>
                        </div>
                        <div class="fw-600"><?= $op['chambre'] ? esc($op['chambre']) : '<span class="text-muted">—</span>' ?></div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">
                            <?= lang('Accueil.reason') ?>
                        </div>
                        <div><?= $op['motif'] ? esc($op['motif']) : '<span class="text-muted">—</span>' ?></div>
                    </div>
                    <?php if ($op['notes']): ?>
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">
                            <?= lang('Accueil.notes') ?>
                        </div>
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;white-space:pre-wrap"><?= esc($op['notes']) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Changer le statut rapidement -->
        <div class="card border-0 shadow-sm mt-4" style="border-radius:14px">
            <div class="card-header border-0 py-3 px-4 bg-white">
                <h6 class="mb-0 fw-700"><i class="bi bi-arrow-repeat me-2 text-primary"></i>Mettre à jour le statut</h6>
            </div>
            <div class="card-body px-4 py-3">
                <form method="POST" action="/admin/accueil/<?= $op['id'] ?>/update" class="d-flex gap-3 align-items-end flex-wrap">
                    <?= csrf_field() ?>
                    <!-- Champs cachés pour conserver les autres valeurs -->
                    <input type="hidden" name="patient_id"     value="<?= esc($op['patient_id']) ?>">
                    <input type="hidden" name="nom_visiteur"   value="<?= esc($op['nom_visiteur']) ?>">
                    <input type="hidden" name="type_operation" value="<?= esc($op['type_operation']) ?>">
                    <input type="hidden" name="date_heure"     value="<?= esc($op['date_heure']) ?>">
                    <input type="hidden" name="chambre"        value="<?= esc($op['chambre']) ?>">
                    <input type="hidden" name="motif"          value="<?= esc($op['motif']) ?>">
                    <input type="hidden" name="notes"          value="<?= esc($op['notes']) ?>">
                    <div>
                        <label class="form-label fw-600 mb-1"><?= lang('Accueil.status') ?></label>
                        <select name="statut" class="form-select" style="width:auto">
                            <?php foreach (['en_attente','en_cours','termine'] as $val): ?>
                            <option value="<?= $val ?>" <?= $op['statut'] === $val ? 'selected' : '' ?>>
                                <?= lang('Accueil.statuses.' . $val) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i><?= lang('Common.update') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Panneau patient / visiteur -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:14px">
            <div class="card-header border-0 py-3 px-4 bg-white">
                <h6 class="mb-0 fw-700"><i class="bi bi-person me-2 text-primary"></i>
                    <?= $op['patient_id'] ? lang('Accueil.patient') : lang('Accueil.visitor') ?>
                </h6>
            </div>
            <div class="card-body px-4 py-3">
                <?php if ($op['patient_id']): ?>
                    <div class="fw-700 mb-1" style="font-size:1.05rem"><?= esc($op['patient_nom'] ?? '—') ?></div>
                    <?php if ($op['date_naissance']): ?>
                    <div class="text-muted small mb-1">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= date('d/m/Y', strtotime($op['date_naissance'])) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($op['groupe_sanguin']): ?>
                    <div class="mb-1">
                        <span class="badge bg-danger"><?= esc($op['groupe_sanguin']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($op['patient_tel']): ?>
                    <div class="text-muted small">
                        <i class="bi bi-telephone me-1"></i><?= esc($op['patient_tel']) ?>
                    </div>
                    <?php endif; ?>
                    <a href="/admin/patients/<?= $op['patient_id'] ?>" class="btn btn-sm btn-outline-primary mt-3 w-100">
                        <i class="bi bi-person-lines-fill me-1"></i>Voir le dossier patient
                    </a>
                <?php else: ?>
                    <div class="fw-700 mb-1" style="font-size:1.05rem">
                        <?= $op['nom_visiteur'] ? esc($op['nom_visiteur']) : '<span class="text-muted">Visiteur anonyme</span>' ?>
                    </div>
                    <div class="text-muted small"><i class="bi bi-person-x me-1"></i>Visiteur externe (non patient)</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Méta -->
        <div class="card border-0 shadow-sm mt-3" style="border-radius:14px">
            <div class="card-body px-4 py-3">
                <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Enregistré le</div>
                <div class="fw-600 mb-3"><?= date('d/m/Y à H:i', strtotime($op['created_at'])) ?></div>
                <div class="text-muted mb-1" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Dernière modification</div>
                <div class="fw-600"><?= date('d/m/Y à H:i', strtotime($op['updated_at'])) ?></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
