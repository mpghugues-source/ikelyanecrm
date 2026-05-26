<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Erp.new_po') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold"><?= lang('Erp.new_po') ?></h1>
    <a href="/erp/purchase-orders" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Erp.cancel') ?></a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="/erp/purchase-orders/store" method="post" id="poForm">
            <?= csrf_field() ?>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold"><?= lang('Erp.suppliers') ?> <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select">
                        <option value="">—</option>
                        <?php foreach ($suppliers as $s): ?><option value="<?= $s['id'] ?>"><?= esc($s['nom']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.order_date') ?> <span class="text-danger">*</span></label><input type="date" name="date_commande" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.delivery_date') ?></label><input type="date" name="date_livraison_prevue" class="form-control"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.vat') ?></label><input type="number" name="tva" id="tvaInput" class="form-control" step="0.01" min="0" max="100" value="19" oninput="calcTotal()"></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.payment_terms') ?></label><input type="text" name="conditions_paiement" class="form-control" placeholder="30 jours..."></div>
                <div class="col-md-4"><label class="form-label fw-semibold"><?= lang('Erp.notes') ?></label><input type="text" name="notes" class="form-control"></div>
            </div>

            <h6 class="fw-semibold mb-3">Lignes de commande</h6>
            <div id="poLines">
                <div class="row g-2 align-items-end mb-2 po-line">
                    <div class="col-md-3"><label class="form-label small">Article</label>
                        <select name="item_id[]" class="form-select form-select-sm item-select">
                            <option value="">— Saisie libre —</option>
                            <?php foreach ($items as $item): ?><option value="<?= $item['id'] ?>" data-price="<?= $item['prix_achat'] ?>" data-unit="<?= esc($item['unite']) ?>"><?= esc($item['code'].' — '.$item['nom']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label small">Description <span class="text-danger">*</span></label><input type="text" name="description[]" class="form-control form-control-sm" required></div>
                    <div class="col-md-1"><label class="form-label small"><?= lang('Admin.qty') ?></label><input type="number" name="quantite[]" class="form-control form-control-sm qty-input" step="0.001" min="0" value="1" oninput="calcTotal()"></div>
                    <div class="col-md-1"><label class="form-label small"><?= lang('Admin.unit') ?></label><input type="text" name="unite[]" class="form-control form-control-sm unit-input"></div>
                    <div class="col-md-2"><label class="form-label small">Prix unit. (DZD)</label><input type="number" name="prix_unitaire[]" class="form-control form-control-sm price-input" step="0.01" min="0" value="0" oninput="calcTotal()"></div>
                    <div class="col-md-1"><label class="form-label small">Total HT</label><input type="text" class="form-control form-control-sm line-total" readonly></div>
                    <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-line" style="margin-top:1.5rem"><i class="bi bi-trash"></i></button></div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mb-4" onclick="addLine()"><i class="bi bi-plus me-1"></i><?= lang('Erp.add_line') ?></button>

            <div class="card bg-light border-0 p-3 mb-4" style="max-width:350px;margin-left:auto">
                <div class="d-flex justify-content-between mb-1"><span><?= lang('Erp.subtotal') ?></span><span id="subtotalDisplay" class="fw-semibold">0.00 DZD</span></div>
                <div class="d-flex justify-content-between mb-1"><span><?= lang('Erp.vat') ?></span><span id="vatDisplay">0.00 DZD</span></div>
                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2"><span><?= lang('Erp.total') ?></span><span id="totalDisplay" class="text-success">0.00 DZD</span></div>
            </div>

            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i><?= lang('Erp.save') ?></button>
        </form>
    </div>
</div>

<script>
const itemData = {<?php foreach ($items as $item): ?>'<?= $item['id'] ?>': {price: <?= $item['prix_achat'] ?>, unit: '<?= esc($item['unite']) ?>'},<?php endforeach; ?>};
document.querySelectorAll('.item-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const row = this.closest('.po-line');
        if (this.value && itemData[this.value]) {
            row.querySelector('.price-input').value = itemData[this.value].price;
            row.querySelector('.unit-input').value  = itemData[this.value].unit;
        }
        calcTotal();
    });
});
function addLine() {
    const tpl = document.querySelector('.po-line').cloneNode(true);
    tpl.querySelectorAll('input').forEach(i => { if (i.type !== 'number' || i.classList.contains('qty-input')) i.value = i.classList.contains('qty-input') ? 1 : ''; });
    tpl.querySelector('.price-input').value = '0';
    tpl.querySelector('.line-total').value  = '';
    tpl.querySelector('.remove-line').addEventListener('click', function() { this.closest('.po-line').remove(); calcTotal(); });
    tpl.querySelector('.item-select').addEventListener('change', function() {
        const row = this.closest('.po-line');
        if (this.value && itemData[this.value]) { row.querySelector('.price-input').value = itemData[this.value].price; row.querySelector('.unit-input').value = itemData[this.value].unit; }
        calcTotal();
    });
    document.getElementById('poLines').appendChild(tpl);
}
document.querySelectorAll('.remove-line').forEach(btn => btn.addEventListener('click', function() { this.closest('.po-line').remove(); calcTotal(); }));
function calcTotal() {
    let ht = 0;
    document.querySelectorAll('.po-line').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;
        row.querySelector('.line-total').value = total.toFixed(2);
        ht += total;
    });
    const tva = parseFloat(document.getElementById('tvaInput').value) || 0;
    const vatAmt = ht * tva / 100;
    const ttc = ht + vatAmt;
    document.getElementById('subtotalDisplay').textContent = ht.toFixed(2) + ' DZD';
    document.getElementById('vatDisplay').textContent = vatAmt.toFixed(2) + ' DZD';
    document.getElementById('totalDisplay').textContent  = ttc.toFixed(2) + ' DZD';
}
calcTotal();
</script>
<?php $this->endSection(); ?>
