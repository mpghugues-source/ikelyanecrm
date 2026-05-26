<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-file-medical text-primary me-2"></i><?= lang('Prescriptions.my_title') ?></h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($ordonnances)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-file-medical fs-1 d-block mb-2 opacity-25"></i>
            <?= lang('Prescriptions.no_results') ?>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><?= lang('Prescriptions.number') ?></th>
                        <th><?= lang('Prescriptions.date') ?></th>
                        <th><?= lang('Prescriptions.doctor') ?></th>
                        <th><?= lang('Prescriptions.validity') ?></th>
                        <th><?= lang('Prescriptions.status') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordonnances as $o): ?>
                    <tr>
                        <td><code><?= esc($o['numero']) ?></code></td>
                        <td><?= date('d/m/Y', strtotime($o['date_ordonnance'])) ?></td>
                        <td>Dr. <?= esc($o['medecin_nom']) ?></td>
                        <td><?= $o['validite_jours'] ?> <?= lang('Prescriptions.days') ?></td>
                        <td><span class="badge-statut statut-<?= $o['statut'] ?>"><?= ucfirst($o['statut']) ?></span></td>
                        <td>
                            <a href="/patient/prescriptions/print/<?= $o['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-printer"></i>
                            </a>
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
