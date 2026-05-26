<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-people me-2 text-secondary"></i><?= esc($title) ?></h1>
    <div class="d-flex gap-2">
        <a href="/admin/export/rh/pdf" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
        </a>
        <a href="/admin/export/rh/csv" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
        </a>
        <a href="/admin/rh/create" class="btn btn-secondary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('RH.add') ?>
        </a>
    </div>
</div>
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-header bg-white">
        <form class="row g-2" method="GET">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="<?= lang('RH.search') ?>..." value="<?= esc($search ?? '') ?>">
                </div>
            </div>
            <div class="col-auto"><button class="btn btn-outline-secondary"><?= lang('Common.filter') ?></button></div>
            <div class="col-auto"><a href="/admin/rh" class="btn btn-outline-secondary"><?= lang('Common.reset') ?></a></div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('RH.name') ?></th>
                        <th><?= lang('RH.firstname') ?></th>
                        <th><?= lang('RH.position') ?></th>
                        <th><?= lang('RH.department') ?></th>
                        <th><?= lang('RH.contract') ?></th>
                        <th><?= lang('RH.hire_date') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($employes)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('RH.no_results') ?></td></tr>
                <?php else: foreach ($employes as $emp): ?>
                    <tr>
                        <td><strong><?= esc($emp['nom']) ?></strong></td>
                        <td><?= esc($emp['prenom']) ?></td>
                        <td><?= esc($emp['poste']) ?></td>
                        <td><?= esc($emp['departement'] ?? '—') ?></td>
                        <td><span class="badge bg-secondary"><?= esc($emp['contrat']) ?></span></td>
                        <td><?= $emp['date_embauche'] ? date('d/m/Y', strtotime($emp['date_embauche'])) : '—' ?></td>
                        <td class="text-end">
                            <a href="/admin/rh/<?= $emp['id'] ?>/view" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                            <a href="/admin/rh/<?= $emp['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="/admin/rh/<?= $emp['id'] ?>/delete" class="d-inline" onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
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
