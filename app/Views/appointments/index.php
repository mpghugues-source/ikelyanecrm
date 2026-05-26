<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = $base_url ?? '/admin'; ?>

<div class="page-header">
    <h4><i class="bi bi-clock text-primary me-2"></i>Rendez-vous</h4>
    <div class="d-flex gap-2">
        <a href="<?= $base ?>/appointments/calendar" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-calendar3 me-1"></i>Calendrier
        </a>
        <?php if ($base === '/admin'): ?>
        <a href="<?= $base ?>/appointments/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nouveau RDV
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Date</label>
                <input type="date" name="date" class="form-control form-control-sm" value="<?= esc($filters['date'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Médecin</label>
                <select name="medecin_id" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <?php foreach ($medecins as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= ($filters['medecin_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
                        Dr. <?= esc($m['user_prenom'] . ' ' . $m['user_nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Statut</label>
                <select name="statut" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <?php foreach (['planifie','confirme','en_cours','termine','annule','absent'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($filters['statut'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <?php if (empty($rdvs)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
            Aucun rendez-vous trouvé
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date & Heure</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Motif</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rdvs as $rdv): ?>
                    <tr>
                        <td>
                            <strong><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></strong><br>
                            <small class="text-primary"><?= substr($rdv['heure_rdv'],0,5) ?></small>
                        </td>
                        <td>
                            <a href="<?= $base ?>/patients/view/<?= $rdv['patient_id'] ?>" class="text-decoration-none fw-semibold">
                                <?= esc($rdv['patient_prenom'] . ' ' . $rdv['patient_nom']) ?>
                            </a>
                            <br><small class="text-muted"><?= esc($rdv['patient_tel'] ?? '') ?></small>
                        </td>
                        <td>Dr. <?= esc($rdv['medecin_prenom'] . ' ' . $rdv['medecin_nom']) ?><br><small class="text-muted"><?= esc($rdv['specialite_nom'] ?? '') ?></small></td>
                        <td><?= esc($rdv['motif'] ?? '—') ?></td>
                        <td><span class="badge bg-light text-dark"><?= esc($rdv['type_rdv']) ?></span></td>
                        <td>
                            <select class="form-select form-select-sm status-select" style="width:auto" data-id="<?= $rdv['id'] ?>">
                                <?php foreach (['planifie','confirme','en_cours','termine','annule','absent'] as $s): ?>
                                <option value="<?= $s ?>" <?= $rdv['statut'] === $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="<?= $base ?>/patients/view/<?= $rdv['patient_id'] ?>" class="btn btn-outline-primary" title="Dossier patient">
                                    <i class="bi bi-folder2-open"></i>
                                </a>
                                <?php if ($base === '/admin'): ?>
                                <a href="/admin/prescriptions/create/<?= $rdv['patient_id'] ?>" class="btn btn-outline-success" title="Ordonnance">
                                    <i class="bi bi-file-medical"></i>
                                </a>
                                <a href="/admin/appointments/delete/<?= $rdv['id'] ?>" class="btn btn-outline-danger"
                                   data-confirm="Annuler ce rendez-vous ?">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.status-select').forEach(function(sel) {
    sel.addEventListener('change', function() {
        const id = sel.getAttribute('data-id');
        fetch('<?= $base ?>/appointments/update-status/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ statut: sel.value })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                sel.closest('td').querySelector('.badge-statut') && (sel.closest('td').querySelector('.badge-statut').className = 'badge-statut statut-' + sel.value);
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
