<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-person-circle text-primary me-2"></i>Mon profil</h4>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- Informations personnelles -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Informations personnelles</h6>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?= base_url('profile/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control"
                                   value="<?= esc(old('nom', $user['nom'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control"
                                   value="<?= esc(old('prenom', $user['prenom'])) ?>" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= esc($user['email']) ?>" disabled>
                            <div class="form-text">L'email ne peut pas être modifié.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rôle</label>
                            <input type="text" class="form-control"
                                   value="<?= ucfirst(str_replace('_', ' ', $user['role'])) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control"
                                   value="<?= esc(old('telephone', $user['telephone'] ?? '')) ?>"
                                   placeholder="+213 5XX XXX XXX">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Carte profil + changement MDP -->
    <div class="col-lg-5">
        <!-- Avatar -->
        <div class="card mb-4 text-center">
            <div class="card-body py-4">
                <div class="avatar-circle mx-auto mb-3" style="width:80px;height:80px;font-size:2rem;">
                    <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
                </div>
                <h5 class="mb-1"><?= esc($user['prenom'] . ' ' . $user['nom']) ?></h5>
                <span class="badge bg-primary"><?= ucfirst(str_replace('_', ' ', $user['role'])) ?></span>
                <div class="text-muted small mt-2"><?= esc($user['email']) ?></div>
            </div>
        </div>

        <!-- Changement de mot de passe -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Changer le mot de passe</h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('profile/password') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe actuel</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="cur_pwd" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="cur_pwd">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="new_pwd" class="form-control"
                                   minlength="8" required>
                            <button type="button" class="btn btn-outline-secondary toggle-pwd" data-target="new_pwd">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Minimum 8 caractères.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-shield-check me-1"></i>Modifier le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-pwd').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = document.getElementById(this.dataset.target);
        const icon  = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
});
</script>

<?= $this->endSection() ?>
