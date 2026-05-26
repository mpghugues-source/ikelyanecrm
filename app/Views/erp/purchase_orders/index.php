<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.purchase_orders') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-cart-check-fill text-success me-2"></i><?= lang('Erp.purchase_orders') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item active"><?= lang('Erp.purchase_orders') ?></li></ol></nav>
    </div>
    <a href="/erp/purchase-orders/create" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i><?= lang('Erp.new_po') ?></a>
</div>
<?php if (session()->getFlashdata('success')): ?><div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th><?= lang('Erp.po_number') ?></th><th><?= lang('Erp.suppliers') ?></th><th><?= lang('Erp.order_date') ?></th><th><?= lang('Erp.delivery_date') ?></th><th><?= lang('Erp.total') ?></th><th><?= lang('Erp.status') ?></th><th width="120"><?= lang('Erp.actions') ?></th></tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('Erp.no_records') ?></td></tr>
                    <?php else:
                    $stColors = ['draft'=>'secondary','sent'=>'info','partial'=>'warning','received'=>'success','cancelled'=>'danger'];
                    foreach ($orders as $o): $sc = $stColors[$o['statut']] ?? 'secondary'; ?>
                    <tr>
                        <td><a href="/erp/purchase-orders/<?= $o['id'] ?>/view" class="fw-bold text-decoration-none"><?= esc($o['numero']) ?></a></td>
                        <td><?= esc($o['supplier_nom'] ?? '—') ?></td>
                        <td><?= date('d/m/Y', strtotime($o['date_commande'])) ?></td>
                        <td><?= $o['date_livraison_prevue'] ? date('d/m/Y', strtotime($o['date_livraison_prevue'])) : '—' ?></td>
                        <td class="fw-bold"><?= number_format($o['montant_ttc'],2,',',' ') ?> <?= $o['devise'] ?></td>
                        <td><span class="badge bg-<?= $sc ?>"><?= lang('Erp.status_'.$o['statut']) ?></span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/erp/purchase-orders/<?= $o['id'] ?>/view" class="btn btn-outline-info" title="<?= lang('Erp.view') ?>"><i class="bi bi-eye"></i></a>
                                <?php if ($o['statut'] === 'draft'): ?>
                                <form action="/erp/purchase-orders/<?= $o['id'] ?>/status" method="post" class="d-inline">
                                    <?= csrf_field() ?><input type="hidden" name="statut" value="sent">
                                    <button type="submit" class="btn btn-outline-primary btn-sm" title="Envoyer"><i class="bi bi-send"></i></button>
                                </form>
                                <?php endif; ?>
                                <?php if ($o['statut'] === 'sent'): ?>
                                <form action="/erp/purchase-orders/<?= $o['id'] ?>/status" method="post" class="d-inline">
                                    <?= csrf_field() ?><input type="hidden" name="statut" value="received">
                                    <button type="submit" class="btn btn-outline-success btn-sm" title="Marquer reçu"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <?php endif; ?>
                                <?php if (in_array($o['statut'], ['draft','sent'])): ?>
                                <a href="/erp/purchase-orders/<?= $o['id'] ?>/cancel" class="btn btn-outline-danger" onclick="return confirm('<?= lang('Erp.confirm_delete') ?>')" title="Annuler"><i class="bi bi-x-lg"></i></a>
                                <?php endif; ?>
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
