<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-receipt text-primary me-2"></i>Facturation</h4>
    <a href="/admin/invoices/create/0" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle facture
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($factures)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-receipt fs-1 d-block mb-2 opacity-25"></i>
            Aucune facture
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>N° Facture</th><th>Patient</th><th>Médecin</th><th>Date</th><th>Total</th><th>Payé</th><th>Reste</th><th>Statut</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($factures as $f): ?>
                    <tr>
                        <td><code><?= esc($f['numero']) ?></code></td>
                        <td class="fw-semibold"><?= esc($f['patient_nom']) ?></td>
                        <td><?= $f['medecin_nom'] ? 'Dr. '.esc($f['medecin_nom']) : '—' ?></td>
                        <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
                        <td class="fw-bold"><?= number_format($f['total'], 2) ?> DA</td>
                        <td class="text-success"><?= number_format($f['montant_paye'], 2) ?> DA</td>
                        <td class="text-danger"><?= number_format($f['total'] - $f['montant_paye'], 2) ?> DA</td>
                        <td><span class="badge-statut statut-<?= $f['statut'] ?>"><?= ucfirst(str_replace('_',' ',$f['statut'])) ?></span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/invoices/view/<?= $f['id'] ?>" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="/admin/invoices/print/<?= $f['id'] ?>" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></a>
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
