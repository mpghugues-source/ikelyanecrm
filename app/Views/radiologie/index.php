<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-x-ray me-2 text-purple"></i><?= esc($title) ?></h1>
    <div class="d-flex gap-2">
        <a href="/admin/export/radiologie/pdf" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
        </a>
        <a href="/admin/export/radiologie/csv" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
        </a>
        <a href="/admin/radiologie/create" class="btn btn-purple" style="background:#6f42c1;color:#fff">
            <i class="bi bi-plus-lg me-1"></i><?= lang('Radiologie.add') ?>
        </a>
    </div>
</div>
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-header bg-white">
        <form class="row g-2" method="GET">
            <div class="col-auto">
                <select name="statut" class="form-select">
                    <option value=""><?= lang('Common.status') ?></option>
                    <?php foreach (['en_attente','programme','realise','annule'] as $val): ?>
                    <option value="<?= $val ?>" <?= ($statut ?? '') == $val ? 'selected' : '' ?>><?= lang('Radiologie.statuses.' . $val) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-outline-secondary"><?= lang('Common.filter') ?></button></div>
            <div class="col-auto"><a href="/admin/radiologie" class="btn btn-outline-secondary"><?= lang('Common.reset') ?></a></div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Radiologie.number') ?></th>
                        <th><?= lang('Radiologie.patient') ?></th>
                        <th><?= lang('Radiologie.exam_type') ?></th>
                        <th><?= lang('Radiologie.region') ?></th>
                        <th><?= lang('Radiologie.request_date') ?></th>
                        <th><?= lang('Radiologie.status') ?></th>
                        <th><?= lang('Common.urgent') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($examens)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted"><?= lang('Radiologie.no_results') ?></td></tr>
                <?php else: foreach ($examens as $e): ?>
                    <?php
                    $colors = ['en_attente'=>'warning','programme'=>'info','realise'=>'success','annule'=>'secondary'];
                    ?>
                    <tr>
                        <td><code><?= esc($e['numero']) ?></code></td>
                        <td><?= esc($e['patient_nom'] ?? '—') ?></td>
                        <td><?= esc($e['type_examen']) ?></td>
                        <td><?= esc($e['region'] ?? '—') ?></td>
                        <td><?= date('d/m/Y', strtotime($e['date_demande'])) ?></td>
                        <td><span class="badge bg-<?= $colors[$e['statut']] ?? 'secondary' ?>"><?= lang('Radiologie.statuses.' . $e['statut']) ?></span></td>
                        <td><?= $e['urgence'] ? '<span class="badge bg-danger">' . lang('Common.urgent') . '</span>' : '' ?></td>
                        <td class="text-end">
                            <a href="/admin/radiologie/<?= $e['id'] ?>/view" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                            <a href="/admin/radiologie/<?= $e['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="/admin/radiologie/<?= $e['id'] ?>/delete" class="d-inline" onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
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
