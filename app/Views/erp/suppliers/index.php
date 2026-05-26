<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.suppliers') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-truck text-secondary me-2"></i><?= lang('Erp.suppliers') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item active"><?= lang('Erp.suppliers') ?></li></ol></nav>
    </div>
    <a href="/erp/suppliers/create" class="btn btn-secondary"><i class="bi bi-plus-lg me-1"></i><?= lang('Erp.new_supplier') ?></a>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Erp.supplier_name') ?></th>
                        <th>Email</th>
                        <th>Tél.</th>
                        <th><?= lang('Erp.city') ?? 'Ville' ?></th>
                        <th><?= lang('Erp.tax_id') ?></th>
                        <th><?= lang('Erp.payment_terms') ?></th>
                        <th><?= lang('Erp.status') ?></th>
                        <th width="100"><?= lang('Erp.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($suppliers)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted"><?= lang('Erp.no_records') ?></td></tr>
                    <?php else: foreach ($suppliers as $s): ?>
                    <tr>
                        <td class="fw-semibold"><?= esc($s['nom']) ?><?php if ($s['contact_principal']): ?><br><small class="text-muted"><?= esc($s['contact_principal']) ?></small><?php endif; ?></td>
                        <td><?= $s['email'] ? '<a href="mailto:'.esc($s['email']).'">'.esc($s['email']).'</a>' : '—' ?></td>
                        <td><?= esc($s['telephone'] ?? '—') ?></td>
                        <td><?= esc($s['ville'] ?? '—') ?></td>
                        <td><?= esc($s['ice'] ?? '—') ?></td>
                        <td><?= esc($s['conditions_paiement'] ?? '—') ?></td>
                        <td><span class="badge bg-<?= $s['statut'] === 'active' ? 'success' : 'secondary' ?>"><?= lang('Erp.'.($s['statut'] === 'active' ? 'active' : 'inactive')) ?></span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/erp/suppliers/<?= $s['id'] ?>/edit" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <a href="/erp/suppliers/<?= $s['id'] ?>/delete" class="btn btn-outline-danger" onclick="return confirm('<?= lang('Erp.confirm_delete') ?>')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
