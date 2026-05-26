<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = $base_url ?? '/admin'; ?>

<div class="page-header">
    <h4><i class="bi bi-file-medical text-primary me-2"></i>Nouvelle ordonnance</h4>
    <a href="<?= $base ?>/prescriptions" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<form action="<?= $base ?>/prescriptions/store" method="POST">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-lg-8">
            <!-- En-tête ordonnance -->
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-person me-2 text-primary"></i>Informations</h6></div>
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
                            <label class="form-label">Médecin *</label>
                            <select name="medecin_id" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                <?php foreach ($medecins as $m): ?>
                                <option value="<?= $m['id'] ?>">Dr. <?= esc($m['user_prenom'] . ' ' . $m['user_nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date_ordonnance" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Validité (jours)</label>
                            <input type="number" name="validite_jours" class="form-control" value="30" min="1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Médicaments -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title"><i class="bi bi-capsule me-2 text-danger"></i>Médicaments prescrits</h6>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addMedication">
                        <i class="bi bi-plus-lg me-1"></i>Ajouter
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Médicament</th>
                                    <th>Posologie</th>
                                    <th>Fréquence</th>
                                    <th>Durée</th>
                                    <th>Qté</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="medicationsTable">
                                <tr class="med-row">
                                    <td><input type="text" name="medicaments[0][medicament_libre]" class="form-control form-control-sm" placeholder="Médicament"></td>
                                    <td><input type="text" name="medicaments[0][posologie]" class="form-control form-control-sm" placeholder="ex: 1 cp matin et soir" required></td>
                                    <td><input type="text" name="medicaments[0][frequence]" class="form-control form-control-sm" placeholder="ex: 3x/jour"></td>
                                    <td><input type="text" name="medicaments[0][duree]" class="form-control form-control-sm" placeholder="ex: 7 jours"></td>
                                    <td><input type="number" name="medicaments[0][quantite]" class="form-control form-control-sm" value="1" min="1" style="width:65px"></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-med"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><h6 class="card-title"><i class="bi bi-sticky me-2 text-warning"></i>Instructions générales</h6></div>
                <div class="card-body">
                    <textarea name="instructions" class="form-control" rows="4" placeholder="Instructions supplémentaires pour le patient..."></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-save me-1"></i>Enregistrer l'ordonnance
            </button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
let medIdx = 1;

document.getElementById('addMedication').addEventListener('click', () => {
    const tbody = document.getElementById('medicationsTable');
    const firstRow = tbody.querySelector('tr.med-row');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, '[' + medIdx + ']');
        el.value = el.type === 'number' ? '1' : '';
    });
    tbody.appendChild(newRow);
    medIdx++;
});

document.getElementById('medicationsTable').addEventListener('click', e => {
    if (e.target.closest('.remove-med')) {
        const rows = document.querySelectorAll('#medicationsTable tr.med-row');
        if (rows.length > 1) e.target.closest('tr').remove();
    }
});
</script>
<?= $this->endSection() ?>
