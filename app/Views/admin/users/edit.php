<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-pencil text-primary me-2"></i>Modifier l'utilisateur</h4>
    <a href="/admin/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="/admin/users/update/<?= $user['id'] ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="nom" class="form-control" value="<?= esc($user['nom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc($user['prenom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control" value="<?= esc($user['telephone']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rôle</label>
                            <select name="role" class="form-select">
                                <?php foreach (['admin'=>'Administrateur','medecin'=>'Médecin','secretaire'=>'Secrétaire','patient'=>'Patient'] as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $user['role']===$val?'selected':'' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Statut</label>
                            <select name="actif" class="form-select">
                                <option value="1" <?= $user['actif']?'selected':'' ?>>Actif</option>
                                <option value="0" <?= !$user['actif']?'selected':'' ?>>Inactif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                            <input type="password" name="password" class="form-control" minlength="8">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save me-1"></i>Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
