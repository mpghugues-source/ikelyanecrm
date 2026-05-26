<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-receipt text-primary me-2"></i><?= lang('Nav.my_invoices') ?></h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($factures)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-receipt fs-1 d-block mb-2 opacity-25"></i>
            <?= lang('Invoices.no_results') ?>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><?= lang('Invoices.number') ?></th>
                        <th><?= lang('Invoices.date') ?></th>
                        <th><?= lang('Invoices.total') ?></th>
                        <th><?= lang('Invoices.paid') ?></th>
                        <th><?= lang('Invoices.balance') ?></th>
                        <th><?= lang('Invoices.status') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($factures as $f): ?>
                    <tr>
                        <td><code><?= esc($f['numero']) ?></code></td>
                        <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
                        <td class="fw-bold"><?= number_format($f['total'], 2) ?> DA</td>
                        <td class="text-success"><?= number_format($f['montant_paye'], 2) ?> DA</td>
                        <td class="text-danger"><?= number_format($f['total'] - $f['montant_paye'], 2) ?> DA</td>
                        <td>
                            <span class="badge-statut statut-<?= $f['statut'] ?>">
                                <?= lang('Invoices.statuses.' . $f['statut']) ?: ucfirst(str_replace('_', ' ', $f['statut'])) ?>
                            </span>
                        </td>
                        <td>
                            <a href="/patient/invoices/print/<?= $f['id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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
