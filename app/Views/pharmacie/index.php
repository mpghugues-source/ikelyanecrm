<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-capsule me-2 text-success"></i><?= esc($title) ?></h1>
    <div class="d-flex gap-2">
        <a href="/admin/export/pharmacie/pdf" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
        </a>
        <a href="/admin/export/pharmacie/csv" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
        </a>
        <a href="/admin/pharmacie/create" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i><?= lang('Common.add') ?>
        </a>
    </div>
</div>

<?php if ($stockBas > 0): ?>
<div class="alert alert-warning d-flex align-items-center mb-4">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <?= lang('Pharmacie.low_stock_alert', [$stockBas]) ?>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-white">
        <form class="row g-2" method="GET">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="<?= lang('Common.search') ?>..." value="<?= esc($search ?? '') ?>">
                </div>
            </div>
            <div class="col-auto"><button class="btn btn-outline-secondary"><?= lang('Common.filter') ?></button></div>
            <div class="col-auto"><a href="/admin/pharmacie" class="btn btn-outline-secondary"><?= lang('Common.reset') ?></a></div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Pharmacie.name') ?></th>
                        <th><?= lang('Pharmacie.category') ?></th>
                        <th><?= lang('Pharmacie.form') ?> / <?= lang('Pharmacie.dosage') ?></th>
                        <th class="text-end"><?= lang('Pharmacie.current_stock') ?></th>
                        <th class="text-end"><?= lang('Pharmacie.min_stock') ?></th>
                        <th class="text-end"><?= lang('Pharmacie.unit_price') ?></th>
                        <th><?= lang('Pharmacie.expiry_date') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($medicaments)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted"><?= lang('Pharmacie.no_results') ?></td></tr>
                <?php else: foreach ($medicaments as $m): ?>
                    <tr>
                        <td><strong><?= esc($m['nom']) ?></strong></td>
                        <td><?= esc($m['categorie'] ?? '—') ?></td>
                        <td><?= esc($m['forme'] ?? '') ?> <?= esc($m['dosage'] ?? '') ?></td>
                        <td class="text-end">
                            <span class="badge bg-<?= $m['stock_actuel'] <= $m['stock_minimum'] ? 'danger' : 'success' ?>">
                                <?= $m['stock_actuel'] ?>
                            </span>
                        </td>
                        <td class="text-end"><?= $m['stock_minimum'] ?></td>
                        <td class="text-end"><?= number_format($m['prix_unitaire'], 2) ?> DA</td>
                        <td><?= $m['date_expiration'] ? date('d/m/Y', strtotime($m['date_expiration'])) : '—' ?></td>
                        <td class="text-end">
                            <a href="/admin/pharmacie/<?= $m['id'] ?>/view" class="btn btn-sm btn-outline-secondary me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/admin/pharmacie/<?= $m['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="/admin/pharmacie/<?= $m['id'] ?>/delete" class="d-inline"
                                  onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
