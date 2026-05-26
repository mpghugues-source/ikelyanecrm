<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-door-open me-2 text-primary"></i><?= esc($title) ?></h1>
    <a href="/admin/accueil/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i><?= lang('Accueil.add') ?>
    </a>
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
                    <?php foreach (['en_attente','en_cours','termine'] as $val): ?>
                    <option value="<?= $val ?>" <?= ($statut ?? '') == $val ? 'selected' : '' ?>><?= lang('Accueil.statuses.' . $val) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-outline-secondary"><?= lang('Common.filter') ?></button></div>
            <div class="col-auto"><a href="/admin/accueil" class="btn btn-outline-secondary"><?= lang('Common.reset') ?></a></div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Accueil.patient') ?> / <?= lang('Accueil.visitor') ?></th>
                        <th><?= lang('Accueil.operation_type') ?></th>
                        <th><?= lang('Accueil.datetime') ?></th>
                        <th><?= lang('Accueil.reason') ?></th>
                        <th><?= lang('Accueil.room') ?></th>
                        <th><?= lang('Accueil.status') ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($operations)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('Accueil.no_results') ?></td></tr>
                <?php else:
                    $opColors  = ['admission'=>'primary','sortie'=>'success','consultation'=>'info','visite'=>'secondary','urgence'=>'danger'];
                    $statColors = ['en_attente'=>'warning','en_cours'=>'info','termine'=>'success'];
                    foreach ($operations as $op):
                ?>
                    <tr>
                        <td><?= esc($op['patient_nom'] ?? $op['nom_visiteur'] ?? '—') ?></td>
                        <td><span class="badge bg-<?= $opColors[$op['type_operation']] ?? 'secondary' ?>"><?= lang('Accueil.types.' . $op['type_operation']) ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($op['date_heure'])) ?></td>
                        <td><?= esc($op['motif'] ?? '—') ?></td>
                        <td><?= esc($op['chambre'] ?? '—') ?></td>
                        <td><span class="badge bg-<?= $statColors[$op['statut']] ?? 'secondary' ?>"><?= lang('Accueil.statuses.' . $op['statut']) ?></span></td>
                        <td class="text-end">
                            <a href="/admin/accueil/<?= $op['id'] ?>" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                            <a href="/admin/accueil/<?= $op['id'] ?>/edit" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="/admin/accueil/<?= $op['id'] ?>/delete" class="d-inline" onsubmit="return confirm('<?= lang('Common.confirm_delete') ?>')">
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
