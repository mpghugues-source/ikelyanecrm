<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= esc($order['numero']) ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><?= esc($order['numero']) ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/erp">ERP</a></li><li class="breadcrumb-item"><a href="/erp/purchase-orders"><?= lang('Erp.purchase_orders') ?></a></li><li class="breadcrumb-item active"><?= esc($order['numero']) ?></li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <?php
        $stColors = ['draft'=>'secondary','sent'=>'info','partial'=>'warning','received'=>'success','cancelled'=>'danger'];
        $sc = $stColors[$order['statut']] ?? 'secondary';
        ?>
        <span class="badge bg-<?= $sc ?> fs-6"><?= lang('Erp.status_'.$order['statut']) ?></span>
        <a href="/erp/purchase-orders" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3 text-muted text-uppercase small">Fournisseur</h6>
                <?php if ($supplier): ?>
                <div class="fw-bold"><?= esc($supplier['nom']) ?></div>
                <?php if ($supplier['adresse']): ?><div class="text-muted small"><?= esc($supplier['adresse']) ?></div><?php endif; ?>
                <?php if ($supplier['telephone']): ?><div><i class="bi bi-telephone me-1"></i><?= esc($supplier['telephone']) ?></div><?php endif; ?>
                <?php if ($supplier['email']): ?><div><a href="mailto:<?= esc($supplier['email']) ?>"><?= esc($supplier['email']) ?></a></div><?php endif; ?>
                <?php else: ?><div class="text-muted">—</div><?php endif; ?>
                <hr>
                <div class="row g-2">
                    <div class="col-6"><small class="text-muted d-block"><?= lang('Erp.order_date') ?></small><?= date('d/m/Y', strtotime($order['date_commande'])) ?></div>
                    <?php if ($order['date_livraison_prevue']): ?><div class="col-6"><small class="text-muted d-block"><?= lang('Erp.delivery_date') ?></small><?= date('d/m/Y', strtotime($order['date_livraison_prevue'])) ?></div><?php endif; ?>
                    <?php if ($order['conditions_paiement']): ?><div class="col-12"><small class="text-muted d-block"><?= lang('Erp.payment_terms') ?></small><?= esc($order['conditions_paiement']) ?></div><?php endif; ?>
                </div>
                <?php if (in_array($order['statut'], ['draft','sent'])): ?>
                <hr>
                <form action="/erp/purchase-orders/<?= $order['id'] ?>/status" method="post">
                    <?= csrf_field() ?>
                    <div class="d-flex gap-2">
                        <?php if ($order['statut'] === 'draft'): ?>
                        <input type="hidden" name="statut" value="sent">
                        <button class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-send me-1"></i>Envoyer</button>
                        <?php else: ?>
                        <input type="hidden" name="statut" value="received">
                        <button class="btn btn-sm btn-success flex-grow-1"><i class="bi bi-check me-1"></i>Marquer reçu</button>
                        <?php endif; ?>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Description</th><th>Qté</th><th>Unité</th><th>Prix unit.</th><th>Total HT</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $li): ?>
                            <tr>
                                <td><?= esc($li['description']) ?></td>
                                <td><?= $li['quantite'] ?></td>
                                <td><?= esc($li['unite']) ?></td>
                                <td><?= number_format($li['prix_unitaire'],2,',',' ') ?> DZD</td>
                                <td class="fw-semibold"><?= number_format($li['montant_total'],2,',',' ') ?> DZD</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr><td colspan="4" class="text-end fw-semibold"><?= lang('Erp.subtotal') ?></td><td class="fw-bold"><?= number_format($order['montant_ht'],2,',',' ') ?> DZD</td></tr>
                            <tr><td colspan="4" class="text-end"><?= lang('Erp.vat') ?> (<?= $order['tva'] ?>%)</td><td><?= number_format($order['montant_ttc']-$order['montant_ht'],2,',',' ') ?> DZD</td></tr>
                            <tr><td colspan="4" class="text-end fw-bold fs-5"><?= lang('Erp.total') ?></td><td class="fw-bold fs-5 text-success"><?= number_format($order['montant_ttc'],2,',',' ') ?> <?= $order['devise'] ?></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($order['notes']): ?>
        <div class="mt-3 card border-0 shadow-sm"><div class="card-body"><h6 class="fw-semibold"><?= lang('Erp.notes') ?></h6><?= nl2br(esc($order['notes'])) ?></div></div>
        <?php endif; ?>
    </div>
</div>
<?php $this->endSection(); ?>
