<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-flask me-2 text-info"></i><?= esc($title) ?></h1>
    <div class="d-flex gap-2">
        <a href="/admin/export/laboratoire/pdf" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
        </a>
        <a href="/admin/export/laboratoire/csv" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
        </a>
        <a href="/admin/laboratoire/create" class="btn btn-info text-white">
            <i class="bi bi-plus-lg me-1"></i><?= lang('Laboratoire.add') ?>
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
                    <?php foreach (['en_attente','en_cours','resultat_disponible','annule'] as $val): ?>
                    <option value="<?= $val ?>" <?= ($statut ?? '') == $val ? 'selected' : '' ?>><?= lang('Laboratoire.statuses.' . $val) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-outline-secondary"><?= lang('Common.filter') ?></button></div>
            <div class="col-auto"><a href="/admin/laboratoire" class="btn btn-outline-secondary"><?= lang('Common.reset') ?></a></div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Laboratoire.number') ?></th>
                        <th><?= lang('Laboratoire.patient') ?></th>
                        <th><?= lang('Laboratoire.type') ?></th>
                        <th><?= lang('Laboratoire.request_date') ?></th>
                        <th><?= lang('Laboratoire.status') ?></th>
                        <th><?= lang('Laboratoire.urgent') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($analyses)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('Laboratoire.no_results') ?></td></tr>
                <?php else: foreach ($analyses as $a): ?>
                    <?php
                    $statutColors = ['en_attente'=>'warning','en_cours'=>'info','resultat_disponible'=>'success','annule'=>'secondary'];
                    ?>
                    <tr>
                        <td><code><?= esc($a['numero']) ?></code></td>
                        <td><?= esc($a['patient_nom'] ?? '—') ?></td>
                        <td><?= esc($a['type_analyse']) ?></td>
                        <td><?= date('d/m/Y', strtotime($a['date_demande'])) ?></td>
                        <td><span class="badge bg-<?= $statutColors[$a['statut']] ?? 'secondary' ?>"><?= lang('Laboratoire.statuses.' . $a['statut']) ?></span></td>
                        <td><?= $a['urgence'] ? '<span class="badge bg-danger">' . lang('Common.urgent') . '</span>' : '' ?></td>
                        <td class="text-end">
                            <a href="/admin/laboratoire/<?= $a['id'] ?>/view" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                            <a href="/admin/laboratoire/<?= $a['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="/admin/laboratoire/<?= $a['id'] ?>/delete" class="d-inline" onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
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
