<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-calendar-plus text-primary me-2"></i>Prendre un rendez-vous</h4>
    <a href="/patient/appointments" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="/patient/appointments/store" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Spécialité</label>
                        <select id="specialiteSelect" class="form-select" onchange="filterDoctors()">
                            <option value="">Toutes les spécialités</option>
                            <?php foreach ($specialites as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= esc($s['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Médecin *</label>
                        <select name="medecin_id" id="medecinSelect" class="form-select" required>
                            <option value="">Sélectionner un médecin...</option>
                            <?php foreach ($medecins as $m): ?>
                            <option value="<?= $m['id'] ?>" data-spec="<?= $m['specialite_id'] ?? 0 ?>">
                                Dr. <?= esc($m['user_prenom'] . ' ' . $m['user_nom']) ?>
                                <?= $m['specialite_nom'] ? ' — ' . $m['specialite_nom'] : '' ?>
                                (<?= number_format($m['tarif_consultation']) ?> DA)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date *</label>
                            <input type="date" name="date_rdv" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Heure *</label>
                            <input type="time" name="heure_rdv" class="form-control" step="1800" min="08:00" max="17:00" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type_rdv" class="form-select">
                            <option value="consultation">Consultation</option>
                            <option value="suivi">Suivi</option>
                            <option value="teleconsultation">Téléconsultation</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Motif de consultation</label>
                        <textarea name="motif" class="form-control" rows="3" placeholder="Décrivez brièvement votre motif..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-calendar-check me-1"></i>Confirmer la demande
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function filterDoctors() {
    const specId = document.getElementById('specialiteSelect').value;
    const options = document.querySelectorAll('#medecinSelect option[data-spec]');
    options.forEach(opt => {
        if (!specId || opt.getAttribute('data-spec') === specId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });
    document.getElementById('medecinSelect').value = '';
}
</script>
<?= $this->endSection() ?>
