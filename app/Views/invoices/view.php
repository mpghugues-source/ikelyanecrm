<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <h4><i class="bi bi-receipt text-primary me-2"></i><?= esc($facture['numero']) ?></h4>
        <small class="text-muted"><?= date('d/m/Y', strtotime($facture['date_facture'])) ?></small>
    </div>
    <div class="d-flex gap-2">
        <?php if ($facture['statut'] !== 'paye'): ?>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payModal">
            <i class="bi bi-cash me-1"></i>Encaisser
        </button>
        <?php endif; ?>
        <a href="/admin/invoices/print/<?= $facture['id'] ?>" target="_blank" class="btn btn-outline-primary">
            <i class="bi bi-printer me-1"></i>Imprimer
        </a>
        <a href="/admin/invoices" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold small text-uppercase text-muted mb-3">Informations</h6>
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Patient</dt><dd class="col-7 fw-semibold"><?= esc($facture['patient_nom']) ?></dd>
                    <dt class="col-5 text-muted">Tél.</dt><dd class="col-7"><?= esc($facture['patient_tel'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted">Médecin</dt><dd class="col-7"><?= $facture['medecin_nom'] ? 'Dr. '.esc($facture['medecin_nom']) : '—' ?></dd>
                    <dt class="col-5 text-muted">Date</dt><dd class="col-7"><?= date('d/m/Y', strtotime($facture['date_facture'])) ?></dd>
                    <dt class="col-5 text-muted">Total</dt><dd class="col-7 fw-bold text-primary"><?= number_format($facture['total'],2) ?> DA</dd>
                    <dt class="col-5 text-muted">Payé</dt><dd class="col-7 text-success"><?= number_format($facture['montant_paye'],2) ?> DA</dd>
                    <dt class="col-5 text-muted">Reste</dt><dd class="col-7 text-danger"><?= number_format($facture['total']-$facture['montant_paye'],2) ?> DA</dd>
                    <dt class="col-5 text-muted">Statut</dt><dd class="col-7"><span class="badge-statut statut-<?= $facture['statut'] ?>"><?= ucfirst(str_replace('_',' ',$facture['statut'])) ?></span></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-list-ul me-2 text-primary"></i>Détail des prestations</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Description</th><th>Qté</th><th>Prix unit.</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= esc($item['description']) ?></td>
                                <td><?= $item['quantite'] ?></td>
                                <td><?= number_format($item['prix_unitaire'],2) ?> DA</td>
                                <td class="fw-semibold"><?= number_format($item['total'],2) ?> DA</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr><td colspan="3" class="text-end">Sous-total</td><td><?= number_format($facture['sous_total'],2) ?> DA</td></tr>
                            <?php if ($facture['remise'] > 0): ?>
                            <tr><td colspan="3" class="text-end text-danger">Remise</td><td class="text-danger">-<?= number_format($facture['remise'],2) ?> DA</td></tr>
                            <?php endif; ?>
                            <tr class="fw-bold"><td colspan="3" class="text-end fs-6">TOTAL</td><td class="text-primary fs-6"><?= number_format($facture['total'],2) ?> DA</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Paiement -->
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Encaisser</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/invoices/pay/<?= $facture['id'] ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Montant encaissé (DA)</label>
                        <input type="number" name="montant" class="form-control" value="<?= $facture['total'] - $facture['montant_paye'] ?>" step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mode de paiement</label>
                        <select name="mode_paiement" class="form-select">
                            <option value="especes">Espèces</option>
                            <option value="cheque">Chèque</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="virement">Virement</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-1"></i>Valider le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
