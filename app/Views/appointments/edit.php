<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-pencil-square text-primary me-2"></i>Modifier le rendez-vous</h4>
    <a href="<?= base_url('admin/appointments/view/' . $rdv['id']) ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Retour
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

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/appointments/update/' . $rdv['id']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Patient -->
                <div class="col-md-6">
                    <label class="form-label">Patient <span class="text-danger">*</span></label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">— Sélectionner —</option>
                        <?php foreach ($patients as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $rdv['patient_id'] == $p['id'] ? 'selected' : '' ?>>
                            <?= esc($p['prenom'] . ' ' . $p['nom']) ?>
                            <?php if (!empty($p['numero_dossier'])): ?>(<?= esc($p['numero_dossier']) ?>)<?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Médecin -->
                <div class="col-md-6">
                    <label class="form-label">Médecin <span class="text-danger">*</span></label>
                    <select name="medecin_id" class="form-select" required>
                        <option value="">— Sélectionner —</option>
                        <?php foreach ($medecins as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $rdv['medecin_id'] == $m['id'] ? 'selected' : '' ?>>
                            Dr. <?= esc(($m['user_prenom'] ?? $m['prenom'] ?? '') . ' ' . ($m['user_nom'] ?? $m['nom'] ?? '')) ?>
                            <?php if (!empty($m['specialite_nom'])): ?> — <?= esc($m['specialite_nom']) ?><?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date -->
                <div class="col-md-4">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date_rdv" class="form-control"
                           value="<?= esc($rdv['date_rdv']) ?>" required>
                </div>

                <!-- Heure -->
                <div class="col-md-4">
                    <label class="form-label">Heure <span class="text-danger">*</span></label>
                    <input type="time" name="heure_rdv" class="form-control"
                           value="<?= esc(substr($rdv['heure_rdv'], 0, 5)) ?>" required>
                </div>

                <!-- Durée -->
                <div class="col-md-4">
                    <label class="form-label">Durée (minutes)</label>
                    <select name="duree" class="form-select">
                        <?php foreach ([15,20,30,45,60,90,120] as $d): ?>
                        <option value="<?= $d ?>" <?= ($rdv['duree'] ?? 30) == $d ? 'selected' : '' ?>><?= $d ?> min</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Type de consultation -->
                <div class="col-md-6">
                    <label class="form-label">Type de consultation</label>
                    <select name="type_consultation" class="form-select">
                        <?php
                        $types = ['Consultation générale','Consultation de suivi','Urgence','Téléconsultation','Bilan','Vaccination','Autre'];
                        $current = $rdv['type_consultation'] ?? 'Consultation générale';
                        ?>
                        <?php foreach ($types as $t): ?>
                        <option value="<?= $t ?>" <?= $current === $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Statut -->
                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <?php
                        $statuts = [
                            'planifie'  => 'Planifié',
                            'confirme'  => 'Confirmé',
                            'en_cours'  => 'En cours',
                            'termine'   => 'Terminé',
                            'annule'    => 'Annulé',
                            'absent'    => 'Patient absent',
                        ];
                        ?>
                        <?php foreach ($statuts as $val => $label): ?>
                        <option value="<?= $val ?>" <?= $rdv['statut'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Motif -->
                <div class="col-12">
                    <label class="form-label">Motif de consultation</label>
                    <input type="text" name="motif" class="form-control"
                           placeholder="Ex: Douleurs abdominales, contrôle tension..."
                           value="<?= esc($rdv['motif'] ?? '') ?>">
                </div>

                <!-- Notes -->
                <div class="col-12">
                    <label class="form-label">Notes internes</label>
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Notes visibles uniquement par le personnel médical..."><?= esc($rdv['notes'] ?? '') ?></textarea>
                </div>

                <!-- Actions -->
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="<?= base_url('admin/appointments/view/' . $rdv['id']) ?>" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
