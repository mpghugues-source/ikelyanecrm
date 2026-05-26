<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isAdmin = in_array(session()->get('role'), ['admin','super_admin','secretaire']);
$base    = $isAdmin ? '/admin' : '/medecin';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-clipboard2-pulse text-primary me-2"></i>Nouvelle consultation</h4>
        <small class="text-muted">
            Patient : <strong><?= esc($patient['prenom'] . ' ' . $patient['nom']) ?></strong>
            — Dossier <code><?= esc($patient['numero_dossier']) ?></code>
        </small>
    </div>
    <a href="<?= $base ?>/patients/view/<?= $patient['id'] ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour dossier
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
        <li><?= esc($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= base_url($base === '/admin' ? 'admin/medical-records/store' : 'medecin/medical-records/store') ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
    <input type="hidden" name="redirect_base" value="<?= $base ?>">
    <!-- medecin_id filled server-side for médecin role, selected below for admin -->

    <div class="row g-4">
        <!-- Colonne principale -->
        <div class="col-lg-8">

            <!-- Informations de la consultation -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Informations générales</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de consultation <span class="text-danger">*</span></label>
                            <input type="date" name="date_consultation" class="form-control"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motif de consultation</label>
                            <input type="text" name="motif" class="form-control"
                                   placeholder="Ex: Douleurs abdominales, contrôle TA..."
                                   value="<?= old('motif') ?>">
                        </div>
                        <?php if ($isAdmin && !empty($medecins)): ?>
                        <div class="col-md-6">
                            <label class="form-label">Médecin traitant</label>
                            <select name="medecin_id" class="form-select">
                                <option value="">— Sélectionner —</option>
                                <?php foreach ($medecins as $m): ?>
                                <option value="<?= $m['id'] ?>">
                                    Dr. <?= esc(($m['user_prenom'] ?? '') . ' ' . ($m['user_nom'] ?? '')) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-12">
                            <label class="form-label">Anamnèse / Histoire de la maladie</label>
                            <textarea name="anamnese" class="form-control" rows="3"
                                      placeholder="Décrivez l'historique des symptômes..."><?= old('anamnese') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Examen clinique -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-stethoscope me-2"></i>Examen clinique</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Examen clinique</label>
                        <textarea name="examen_clinique" class="form-control" rows="4"
                                  placeholder="Résultats de l'examen physique..."><?= old('examen_clinique') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diagnostic</label>
                        <textarea name="diagnostic" class="form-control" rows="2"
                                  placeholder="Diagnostic retenu..."><?= old('diagnostic') ?></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Traitement prescrit / Conduite à tenir</label>
                        <textarea name="traitement" class="form-control" rows="3"
                                  placeholder="Traitement médicamenteux, examens complémentaires..."><?= old('traitement') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>Notes complémentaires</h6>
                </div>
                <div class="card-body">
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Notes additionnelles, observations..."><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Colonne constantes vitales -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-activity me-2"></i>Constantes vitales</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Poids (kg)</label>
                        <div class="input-group">
                            <input type="number" name="poids" class="form-control" step="0.1" min="1" max="300"
                                   placeholder="70.5" value="<?= old('poids') ?>">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Taille (cm)</label>
                        <div class="input-group">
                            <input type="number" name="taille" class="form-control" min="30" max="250"
                                   placeholder="175" value="<?= old('taille') ?>">
                            <span class="input-group-text">cm</span>
                        </div>
                    </div>
                    <?php if (!empty($patient['poids']) && !empty($patient['taille']) || old('poids') && old('taille')): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted small">IMC calculé</label>
                        <div class="fw-bold" id="imc-display">—</div>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Tension artérielle</label>
                        <input type="text" name="tension_arterielle" class="form-control"
                               placeholder="120/80" value="<?= old('tension_arterielle') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Température (°C)</label>
                        <div class="input-group">
                            <input type="number" name="temperature" class="form-control" step="0.1" min="34" max="43"
                                   placeholder="37.0" value="<?= old('temperature') ?>">
                            <span class="input-group-text">°C</span>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Pouls (bpm)</label>
                        <div class="input-group">
                            <input type="number" name="pouls" class="form-control" min="20" max="250"
                                   placeholder="72" value="<?= old('pouls') ?>">
                            <span class="input-group-text">bpm</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient info card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-person me-2"></i>Patient</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="patient-avatar"
                             style="background:<?= $patient['sexe']==='F'?'linear-gradient(135deg,#e91e63,#f06292)':'linear-gradient(135deg,#1565c0,#42a5f5)' ?>">
                            <?= strtoupper(substr($patient['prenom'],0,1).substr($patient['nom'],0,1)) ?>
                        </div>
                        <div>
                            <div class="fw-bold"><?= esc($patient['prenom'] . ' ' . $patient['nom']) ?></div>
                            <div class="text-muted small"><?= esc($patient['numero_dossier']) ?></div>
                        </div>
                    </div>
                    <?php if ($patient['date_naissance']): ?>
                    <div class="text-muted small mb-1">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= date('d/m/Y', strtotime($patient['date_naissance'])) ?>
                        (<?= (int)((time() - strtotime($patient['date_naissance'])) / 31536000) ?> ans)
                    </div>
                    <?php endif; ?>
                    <?php if ($patient['groupe_sanguin']): ?>
                    <div class="text-muted small mb-1">
                        <i class="bi bi-droplet me-1 text-danger"></i>
                        Groupe sanguin : <strong class="text-danger"><?= esc($patient['groupe_sanguin']) ?></strong>
                    </div>
                    <?php endif; ?>
                    <?php if ($patient['allergies']): ?>
                    <div class="alert alert-warning py-1 px-2 mb-0 mt-2 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Allergies :</strong> <?= esc($patient['allergies']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Submit -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Enregistrer la consultation
                </button>
                <a href="<?= $base ?>/patients/view/<?= $patient['id'] ?>" class="btn btn-outline-secondary">
                    Annuler
                </a>
            </div>
        </div>
    </div>
</form>

<script>
// Calcul IMC en temps réel
const poidsInput  = document.querySelector('[name="poids"]');
const tailleInput = document.querySelector('[name="taille"]');
const imcDisplay  = document.getElementById('imc-display');

function updateIMC() {
    if (!imcDisplay) return;
    const p = parseFloat(poidsInput?.value);
    const t = parseFloat(tailleInput?.value) / 100;
    if (p > 0 && t > 0) {
        const imc = (p / (t * t)).toFixed(1);
        let cat = '';
        if (imc < 18.5)      cat = '<span class="text-info">Insuffisance pondérale</span>';
        else if (imc < 25)   cat = '<span class="text-success">Normal</span>';
        else if (imc < 30)   cat = '<span class="text-warning">Surpoids</span>';
        else                 cat = '<span class="text-danger">Obésité</span>';
        imcDisplay.innerHTML = `${imc} kg/m² — ${cat}`;
    } else {
        imcDisplay.textContent = '—';
    }
}
poidsInput?.addEventListener('input', updateIMC);
tailleInput?.addEventListener('input', updateIMC);
</script>

<?= $this->endSection() ?>
