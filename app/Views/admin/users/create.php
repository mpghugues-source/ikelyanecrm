<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-person-plus text-primary me-2"></i>Nouvel utilisateur</h4>
    <a href="/admin/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="/admin/users/store" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rôle *</label>
                            <select name="role" class="form-select" required>
                                <option value="admin">Administrateur</option>
                                <option value="medecin">Médecin</option>
                                <option value="secretaire">Secrétaire</option>
                                <option value="patient">Patient</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mot de passe *</label>
                            <input type="password" name="password" class="form-control" required minlength="8" placeholder="Minimum 8 caractères">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-person-check me-1"></i>Créer l'utilisateur
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
