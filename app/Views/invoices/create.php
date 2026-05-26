<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-receipt text-primary me-2"></i>Nouvelle facture</h4>
    <a href="/admin/invoices" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<form action="/admin/invoices/store" method="POST">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <!-- En-tête -->
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-info-circle me-2 text-primary"></i>Informations</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient *</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                <?php foreach ($patients as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $patient_id == $p['id'] ? 'selected' : '' ?>><?= esc($p['prenom'] . ' ' . $p['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Médecin</label>
                            <select name="medecin_id" class="form-select">
                                <option value="">Sélectionner...</option>
                                <?php foreach ($medecins as $m): ?>
                                <option value="<?= $m['id'] ?>">Dr. <?= esc($m['user_prenom'] . ' ' . $m['user_nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de facturation</label>
                            <input type="date" name="date_facture" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lignes de facturation -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title"><i class="bi bi-list-ul me-2 text-primary"></i>Prestations</h6>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addInvoiceItem">
                        <i class="bi bi-plus-lg me-1"></i>Ajouter une ligne
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th style="width:80px">Qté</th>
                                    <th style="width:120px">Prix unit. (DA)</th>
                                    <th style="width:120px">Total (DA)</th>
                                    <th style="width:50px"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <tr class="invoice-item-row">
                                    <td>
                                        <select name="items[0][service_id]" class="form-select form-select-sm service-select mb-1" onchange="fillService(this)">
                                            <option value="">Service personnalisé...</option>
                                            <?php foreach ($services as $s): ?>
                                            <option value="<?= $s['id'] ?>" data-prix="<?= $s['prix'] ?>"><?= esc($s['nom']) ?> — <?= number_format($s['prix']) ?> DA</option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="items[0][description]" class="form-control form-control-sm desc-input" placeholder="Description" required>
                                    </td>
                                    <td><input type="number" name="items[0][quantite]" class="form-control form-control-sm qty-input" value="1" min="1"></td>
                                    <td><input type="number" name="items[0][prix_unitaire]" class="form-control form-control-sm price-input" step="0.01" placeholder="0.00"></td>
                                    <td><input type="number" name="items[0][total]" class="form-control form-control-sm line-total" readonly placeholder="0.00"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Totaux -->
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-calculator me-2 text-success"></i>Totaux</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Sous-total</span>
                        <strong id="sousTotal">0.00 DA</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Remise (DA)</label>
                        <input type="number" name="remise" id="remiseInput" class="form-control form-control-sm" value="0" step="0.01" min="0">
                    </div>
                    <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-2">
                        <span>Total</span>
                        <span class="text-primary" id="grandTotal">0.00 DA</span>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title">Notes</h6></div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="3" placeholder="Remarques..."></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-save me-1"></i>Créer la facture
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
let itemIndex = 1;

function fillService(sel) {
    const opt = sel.options[sel.selectedIndex];
    const prix = parseFloat(opt.getAttribute('data-prix') || 0);
    const row = sel.closest('tr');
    if (prix > 0) {
        const p = row.querySelector('.price-input');
        if (p) { p.value = prix.toFixed(2); }
        const d = row.querySelector('.desc-input');
        if (d && opt.text && opt.text !== 'Service personnalisé...') d.value = opt.text.split(' — ')[0];
    }
    updateRow(row);
    updateTotals();
}

function updateRow(row) {
    const qty   = parseFloat(row.querySelector('.qty-input')?.value || 0);
    const price = parseFloat(row.querySelector('.price-input')?.value || 0);
    const total = row.querySelector('.line-total');
    if (total) total.value = (qty * price).toFixed(2);
}

function updateTotals() {
    let sub = 0;
    document.querySelectorAll('.line-total').forEach(el => sub += parseFloat(el.value || 0));
    const remise = parseFloat(document.getElementById('remiseInput').value || 0);
    document.getElementById('sousTotal').textContent  = sub.toLocaleString('fr-DZ', {minimumFractionDigits:2}) + ' DA';
    document.getElementById('grandTotal').textContent = Math.max(0, sub - remise).toLocaleString('fr-DZ', {minimumFractionDigits:2}) + ' DA';
}

function addRow() {
    const tbody = document.getElementById('invoiceItems');
    const firstRow = tbody.querySelector('tr.invoice-item-row');
    const newRow = firstRow.cloneNode(true);
    // Update input names with new index
    newRow.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, '[' + itemIndex + ']');
        if (!el.classList.contains('line-total')) el.value = '';
    });
    // Reset service select to first option
    const sel = newRow.querySelector('.service-select');
    if (sel) sel.selectedIndex = 0;
    // Add remove button
    const lastTd = newRow.querySelector('td:last-child');
    lastTd.innerHTML = '<button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest(\'tr\').remove();updateTotals()"><i class="bi bi-trash"></i></button>';
    tbody.appendChild(newRow);
    itemIndex++;
}

// Event delegation
document.getElementById('invoiceItems').addEventListener('input', e => {
    const row = e.target.closest('tr.invoice-item-row');
    if (row) { updateRow(row); updateTotals(); }
});
document.getElementById('remiseInput').addEventListener('input', updateTotals);
document.getElementById('addInvoiceItem').addEventListener('click', addRow);
</script>
<?= $this->endSection() ?>
