<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = $base_url ?? '/admin'; ?>

<div class="page-header">
    <div>
        <h4><i class="bi bi-file-medical text-primary me-2"></i><?= esc($ordonnance['numero']) ?></h4>
        <small class="text-muted"><?= date('d/m/Y', strtotime($ordonnance['date_ordonnance'])) ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= $base ?>/prescriptions/print/<?= $ordonnance['id'] ?>" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-printer me-1"></i>Imprimer
        </a>
        <a href="<?= $base ?>/prescriptions" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-muted small text-uppercase">Informations</h6>
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Patient</dt><dd class="col-7 fw-semibold"><?= esc($ordonnance['patient_nom']) ?></dd>
                    <dt class="col-5 text-muted">Médecin</dt><dd class="col-7">Dr. <?= esc($ordonnance['medecin_nom']) ?></dd>
                    <dt class="col-5 text-muted">Spécialité</dt><dd class="col-7"><?= esc($ordonnance['specialite'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted">Date</dt><dd class="col-7"><?= date('d/m/Y', strtotime($ordonnance['date_ordonnance'])) ?></dd>
                    <dt class="col-5 text-muted">Validité</dt><dd class="col-7"><?= $ordonnance['validite_jours'] ?> jours</dd>
                    <dt class="col-5 text-muted">Statut</dt><dd class="col-7"><span class="badge-statut statut-<?= $ordonnance['statut'] ?>"><?= ucfirst($ordonnance['statut']) ?></span></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-capsule me-2 text-danger"></i>Médicaments prescrits</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Médicament</th><th>Posologie</th><th>Fréquence</th><th>Durée</th><th>Qté</th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($item['medicament_nom'] ?? $item['medicament_libre'] ?? '—') ?></td>
                                <td><?= esc($item['posologie']) ?></td>
                                <td><?= esc($item['frequence'] ?? '—') ?></td>
                                <td><?= esc($item['duree'] ?? '—') ?></td>
                                <td><?= $item['quantite'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($ordonnance['instructions']): ?>
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-2 small text-uppercase text-muted">Instructions</h6>
                <p class="mb-0"><?= esc($ordonnance['instructions']) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
