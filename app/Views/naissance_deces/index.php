<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-journal-text me-2"></i><?= esc($title) ?></h1>
    <div class="d-flex gap-2">
        <a href="/admin/export/naissance-deces/pdf" target="_blank" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
        </a>
        <a href="/admin/export/naissance-deces/csv" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>CSV
        </a>
        <a href="/admin/naissance-deces/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i><?= lang('NaissanceDeces.add') ?>
        </a>
    </div>
</div>
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body"><div class="h2 fw-bold text-primary"><?= $naissances ?></div><div class="small text-muted"><?= lang('NaissanceDeces.births') ?></div></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body"><div class="h2 fw-bold text-dark"><?= $deces ?></div><div class="small text-muted"><?= lang('NaissanceDeces.deaths') ?></div></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <form class="row g-2" method="GET">
            <div class="col-auto">
                <select name="type" class="form-select">
                    <option value=""><?= lang('NaissanceDeces.all_events') ?></option>
                    <option value="naissance" <?= ($type ?? '') == 'naissance' ? 'selected' : '' ?>><?= lang('NaissanceDeces.only_births') ?></option>
                    <option value="deces" <?= ($type ?? '') == 'deces' ? 'selected' : '' ?>><?= lang('NaissanceDeces.only_deaths') ?></option>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-outline-secondary"><?= lang('Common.filter') ?></button></div>
            <div class="col-auto"><a href="/admin/naissance-deces" class="btn btn-outline-secondary"><?= lang('Common.reset') ?></a></div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('NaissanceDeces.event_type') ?></th>
                        <th><?= lang('NaissanceDeces.name') ?></th>
                        <th><?= lang('NaissanceDeces.firstname') ?></th>
                        <th><?= lang('NaissanceDeces.date') ?></th>
                        <th><?= lang('NaissanceDeces.place') ?></th>
                        <th><?= lang('NaissanceDeces.cert_number') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($evenements)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('NaissanceDeces.no_results') ?></td></tr>
                <?php else: foreach ($evenements as $ev): ?>
                    <tr>
                        <td>
                            <?php if ($ev['type_evenement'] == 'naissance'): ?>
                                <span class="badge bg-primary"><i class="bi bi-stars me-1"></i><?= lang('NaissanceDeces.birth') ?></span>
                            <?php else: ?>
                                <span class="badge bg-dark"><i class="bi bi-moon me-1"></i><?= lang('NaissanceDeces.death') ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($ev['nom']) ?></td>
                        <td><?= esc($ev['prenom'] ?? '—') ?></td>
                        <td><?= date('d/m/Y', strtotime($ev['date_evenement'])) ?><?= $ev['heure_evenement'] ? ' à ' . substr($ev['heure_evenement'], 0, 5) : '' ?></td>
                        <td><?= esc($ev['lieu'] ?? '—') ?></td>
                        <td><?= esc($ev['numero_certificat'] ?? '—') ?></td>
                        <td class="text-end">
                            <a href="/admin/naissance-deces/<?= $ev['id'] ?>/view" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                            <a href="/admin/naissance-deces/<?= $ev['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="/admin/naissance-deces/<?= $ev['id'] ?>/delete" class="d-inline" onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
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
