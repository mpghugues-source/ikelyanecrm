<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-calendar3 text-primary me-2"></i>Mes rendez-vous</h4>
    <div class="d-flex gap-2">
        <a href="<?= base_url('medecin/appointments/calendar') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-calendar-week me-1"></i>Vue calendrier
        </a>
    </div>
</div>

<!-- Filtres rapides -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Date</label>
                <input type="date" name="date" class="form-control form-control-sm"
                       value="<?= esc($filters['date'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="statut" class="form-select form-select-sm">
                    <option value="">Tous les statuts</option>
                    <option value="planifie"  <?= ($filters['statut'] ?? '') === 'planifie'  ? 'selected' : '' ?>>Planifié</option>
                    <option value="confirme"  <?= ($filters['statut'] ?? '') === 'confirme'  ? 'selected' : '' ?>>Confirmé</option>
                    <option value="en_cours"  <?= ($filters['statut'] ?? '') === 'en_cours'  ? 'selected' : '' ?>>En cours</option>
                    <option value="termine"   <?= ($filters['statut'] ?? '') === 'termine'   ? 'selected' : '' ?>>Terminé</option>
                    <option value="annule"    <?= ($filters['statut'] ?? '') === 'annule'    ? 'selected' : '' ?>>Annulé</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
            </div>
            <?php if (!empty($filters)): ?>
            <div class="col-md-2">
                <a href="<?= base_url('medecin/appointments') ?>" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-x-circle me-1"></i>Effacer
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Liste des rendez-vous -->
<div class="card">
    <div class="card-body p-0">
        <?php if (empty($rdvs)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
            <p class="mb-0">Aucun rendez-vous trouvé</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date & Heure</th>
                        <th>Patient</th>
                        <th>Motif</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rdvs as $rdv): ?>
                    <tr>
                        <td>
                            <div class="fw-bold"><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></div>
                            <div class="text-muted small"><i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($rdv['heure_rdv'])) ?></div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle" style="width:34px;height:34px;font-size:.75rem;flex-shrink:0;">
                                    <?= strtoupper(substr($rdv['patient_prenom'] ?? 'P', 0, 1) . substr($rdv['patient_nom'] ?? '?', 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= esc($rdv['patient_prenom'] . ' ' . $rdv['patient_nom']) ?></div>
                                    <?php if (!empty($rdv['patient_tel'])): ?>
                                    <div class="text-muted small"><?= esc($rdv['patient_tel']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted"><?= esc($rdv['motif'] ?? $rdv['type_consultation'] ?? '—') ?></span>
                        </td>
                        <td><?= $rdv['duree'] ?? 30 ?> min</td>
                        <td>
                            <select class="form-select form-select-sm statut-select"
                                    data-id="<?= $rdv['id'] ?>"
                                    style="width:auto;min-width:130px;">
                                <?php
                                $statuts = [
                                    'planifie' => 'Planifié',
                                    'confirme' => 'Confirmé',
                                    'en_cours' => 'En cours',
                                    'termine'  => 'Terminé',
                                    'annule'   => 'Annulé',
                                    'absent'   => 'Absent',
                                ];
                                foreach ($statuts as $val => $lbl): ?>
                                <option value="<?= $val ?>" <?= $rdv['statut'] === $val ? 'selected' : '' ?>>
                                    <?= $lbl ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="text-end">
                            <?php if (!empty($rdv['patient_id'])): ?>
                            <a href="<?= base_url('medecin/patients/view/' . $rdv['patient_id']) ?>"
                               class="btn btn-sm btn-outline-info" title="Dossier patient">
                                <i class="bi bi-folder2-open"></i>
                            </a>
                            <a href="<?= base_url('medecin/prescriptions/create/' . $rdv['patient_id']) ?>"
                               class="btn btn-sm btn-outline-success" title="Créer ordonnance">
                                <i class="bi bi-file-earmark-medical"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.statut-select').forEach(sel => {
    sel.addEventListener('change', function () {
        const id     = this.dataset.id;
        const statut = this.value;
        fetch(`/medecin/appointments/update-status/${id}`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            body: JSON.stringify({statut})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = this.closest('tr');
                row.style.transition = 'background .3s';
                row.style.background = '#d1e7dd';
                setTimeout(() => row.style.background = '', 1000);
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
