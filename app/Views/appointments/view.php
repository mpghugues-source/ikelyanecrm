<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-calendar-check text-primary me-2"></i>Détail du rendez-vous</h4>
    <div>
        <a href="<?= base_url('admin/appointments/edit/' . $rdv['id']) ?>" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil me-1"></i>Modifier
        </a>
        <a href="<?= base_url('admin/appointments') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations du rendez-vous</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Numéro</label>
                        <p class="fw-bold"><code><?= esc($rdv['numero'] ?? 'RDV-' . str_pad($rdv['id'], 5, '0', STR_PAD_LEFT)) ?></code></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Statut</label>
                        <p><span class="badge-statut statut-<?= $rdv['statut'] ?>"><?= ucfirst(str_replace('_', ' ', $rdv['statut'])) ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Date</label>
                        <p class="fw-bold"><i class="bi bi-calendar3 me-1 text-primary"></i><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Heure</label>
                        <p class="fw-bold"><i class="bi bi-clock me-1 text-primary"></i><?= date('H:i', strtotime($rdv['heure_rdv'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Durée</label>
                        <p><?= $rdv['duree'] ?? 30 ?> minutes</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Type de consultation</label>
                        <p><?= esc($rdv['type_consultation'] ?? 'Consultation générale') ?></p>
                    </div>
                    <?php if (!empty($rdv['motif'])): ?>
                    <div class="col-12">
                        <label class="form-label text-muted small">Motif</label>
                        <p><?= esc($rdv['motif']) ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($rdv['notes'])): ?>
                    <div class="col-12">
                        <label class="form-label text-muted small">Notes</label>
                        <div class="alert alert-light"><?= nl2br(esc($rdv['notes'])) ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Patient -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Patient</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($rdv['patient_nom'])): ?>
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-circle me-3" style="width:45px;height:45px;font-size:1rem;">
                        <?= strtoupper(substr($rdv['patient_prenom'] ?? 'P', 0, 1) . substr($rdv['patient_nom'], 0, 1)) ?>
                    </div>
                    <div>
                        <div class="fw-bold"><?= esc($rdv['patient_prenom'] . ' ' . $rdv['patient_nom']) ?></div>
                        <?php if (!empty($rdv['patient_tel'])): ?>
                        <div class="text-muted small"><i class="bi bi-telephone me-1"></i><?= esc($rdv['patient_tel']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?= base_url('admin/patients/' . $rdv['patient_id']) ?>" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-folder2-open me-1"></i>Voir le dossier
                </a>
                <?php else: ?>
                <p class="text-muted">Patient non renseigné</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Médecin -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Médecin</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($rdv['medecin_nom'])): ?>
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                        <i class="bi bi-person-badge text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Dr. <?= esc($rdv['medecin_prenom'] . ' ' . $rdv['medecin_nom']) ?></div>
                        <?php if (!empty($rdv['specialite_nom'])): ?>
                        <div class="text-muted small"><?= esc($rdv['specialite_nom'] ?? '') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="<?= base_url('admin/doctors/' . $rdv['medecin_id']) ?>" class="btn btn-outline-info btn-sm w-100">
                    <i class="bi bi-person-lines-fill me-1"></i>Profil médecin
                </a>
                <?php else: ?>
                <p class="text-muted">Médecin non renseigné</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Actions rapides</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <?php if ($rdv['statut'] === 'planifie'): ?>
                <button class="btn btn-success btn-sm update-status" data-id="<?= $rdv['id'] ?>" data-status="confirme">
                    <i class="bi bi-check-circle me-1"></i>Confirmer
                </button>
                <?php endif; ?>
                <?php if (in_array($rdv['statut'], ['planifie', 'confirme'])): ?>
                <button class="btn btn-info btn-sm update-status" data-id="<?= $rdv['id'] ?>" data-status="en_cours">
                    <i class="bi bi-play-circle me-1"></i>Démarrer
                </button>
                <button class="btn btn-secondary btn-sm update-status" data-id="<?= $rdv['id'] ?>" data-status="annule">
                    <i class="bi bi-x-circle me-1"></i>Annuler
                </button>
                <?php endif; ?>
                <?php if ($rdv['statut'] === 'en_cours'): ?>
                <button class="btn btn-primary btn-sm update-status" data-id="<?= $rdv['id'] ?>" data-status="termine">
                    <i class="bi bi-check2-all me-1"></i>Terminer
                </button>
                <?php endif; ?>

                <?php if (!empty($rdv['patient_id'])): ?>
                <a href="<?= base_url('admin/prescriptions/create?patient_id=' . $rdv['patient_id'] . '&rdv_id=' . $rdv['id']) ?>" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-file-earmark-medical me-1"></i>Créer ordonnance
                </a>
                <a href="<?= base_url('admin/invoices/create?patient_id=' . $rdv['patient_id'] . '&rdv_id=' . $rdv['id']) ?>" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-receipt me-1"></i>Créer facture
                </a>
                <?php endif; ?>

                <a href="<?= base_url('admin/appointments/edit/' . $rdv['id']) ?>" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i>Modifier le RDV
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.update-status').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const status = this.dataset.status;
        fetch(`/admin/appointments/update-status/${id}`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            body: JSON.stringify({statut: status})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) window.location.reload();
        });
    });
});
</script>

<?= $this->endSection() ?>
