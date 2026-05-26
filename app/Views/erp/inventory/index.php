<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.inventory') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-boxes text-warning me-2"></i><?= lang('Erp.inventory') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item active"><?= lang('Erp.inventory') ?></li></ol></nav>
    </div>
    <a href="/erp/inventory/create" class="btn btn-warning"><i class="bi bi-plus-lg me-1"></i><?= lang('Erp.new_item') ?></a>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<?php if (!empty($low_stock)): ?>
<div class="alert alert-warning d-flex align-items-center mb-3">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong><?= count($low_stock) ?> article(s)</strong>&nbsp;en dessous du stock minimum.
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body py-2 border-bottom">
        <input type="text" id="searchInput" class="form-control form-control-sm w-25" placeholder="<?= lang('Erp.item_name') ?>...">
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Erp.item_code') ?></th>
                        <th><?= lang('Erp.item_name') ?></th>
                        <th><?= lang('Erp.category') ?></th>
                        <th><?= lang('Erp.stock_qty') ?></th>
                        <th><?= lang('Erp.min_qty') ?></th>
                        <th><?= lang('Erp.purchase_price') ?></th>
                        <th><?= lang('Erp.location') ?></th>
                        <th width="120"><?= lang('Erp.actions') ?></th>
                    </tr>
                </thead>
                <tbody id="inventoryTable">
                    <?php if (empty($items)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted"><?= lang('Erp.no_records') ?></td></tr>
                    <?php else: foreach ($items as $item):
                        $isLow = $item['quantite_stock'] <= $item['quantite_min'];
                    ?>
                    <tr class="<?= $isLow ? 'table-warning' : '' ?>">
                        <td><code><?= esc($item['code']) ?></code></td>
                        <td class="fw-semibold"><?= esc($item['nom']) ?><?= $isLow ? ' <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Stock faible"></i>' : '' ?></td>
                        <td><?= esc($item['categorie'] ?? '—') ?></td>
                        <td class="<?= $isLow ? 'text-danger fw-bold' : 'text-success fw-semibold' ?>"><?= $item['quantite_stock'] ?> <?= $item['unite'] ?></td>
                        <td class="text-muted"><?= $item['quantite_min'] ?> <?= $item['unite'] ?></td>
                        <td><?= number_format($item['prix_achat'],2) ?> DZD</td>
                        <td><?= esc($item['emplacement'] ?? '—') ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/erp/inventory/<?= $item['id'] ?>/movement" class="btn btn-outline-success" title="<?= lang('Erp.movement') ?>"><i class="bi bi-arrow-left-right"></i></a>
                                <a href="/erp/inventory/<?= $item['id'] ?>/edit" class="btn btn-outline-primary" title="<?= lang('Erp.edit') ?>"><i class="bi bi-pencil"></i></a>
                                <a href="/erp/inventory/<?= $item['id'] ?>/delete" class="btn btn-outline-danger" onclick="return confirm('<?= lang('Erp.confirm_delete') ?>')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#inventoryTable tr').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
<?php $this->endSection(); ?>
