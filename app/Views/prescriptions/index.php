<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = $base_url ?? '/admin'; ?>

<div class="page-header">
    <h4><i class="bi bi-file-medical text-primary me-2"></i>Ordonnances</h4>
    <a href="<?= $base ?>/prescriptions/create/0" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle ordonnance
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($ordonnances)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-file-medical fs-1 d-block mb-2 opacity-25"></i>
            Aucune ordonnance
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>N° Ordonnance</th><th>Patient</th><th>Médecin</th><th>Date</th><th>Validité</th><th>Statut</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($ordonnances as $o): ?>
                    <tr>
                        <td><code><?= esc($o['numero']) ?></code></td>
                        <td class="fw-semibold"><?= esc($o['patient_nom']) ?></td>
                        <td>Dr. <?= esc($o['medecin_nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($o['date_ordonnance'])) ?></td>
                        <td><?= $o['validite_jours'] ?> jours</td>
                        <td><span class="badge-statut statut-<?= $o['statut'] ?>"><?= ucfirst($o['statut']) ?></span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= $base ?>/prescriptions/view/<?= $o['id'] ?>" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="<?= $base ?>/prescriptions/print/<?= $o['id'] ?>" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
