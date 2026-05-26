<!DOCTYPE html>
<html lang="<?= session()->get('locale') ?? 'fr' ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family:'Inter',sans-serif; } body { background:#f1f5f9; }
        .sa-sidebar { width:260px;background:#0f172a;min-height:100vh;position:fixed;left:0;top:0;z-index:100;padding:24px 0; }
        .sa-brand { padding:0 24px 24px;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px; }
        .sa-brand-name { font-size:1.3rem;font-weight:800;color:#fff; }
        .sa-brand-badge { font-size:.7rem;background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;padding:2px 10px;border-radius:50px;font-weight:700; }
        .sa-nav-link { display:flex;align-items:center;gap:12px;padding:12px 24px;color:#94a3b8;text-decoration:none;font-size:.9rem;font-weight:500;transition:all .2s;border-left:3px solid transparent; }
        .sa-nav-link:hover,.sa-nav-link.active { color:#fff;background:rgba(255,255,255,.05);border-left-color:#1a56db; }
        .sa-nav-link i { font-size:1.1rem;width:20px;text-align:center; }
        .sa-main { margin-left:260px;padding:32px; }
        .form-card { background:#fff;border-radius:16px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #e2e8f0; }
    </style>
</head>
<body>
<?= view('superadmin/partials/sidebar', ['activeNav' => 'subscriptions']) ?>
<div class="sa-main">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/superadmin/subscriptions" class="btn btn-sm btn-light border"><i class="bi bi-arrow-left"></i></a>
        <h4 class="mb-0 fw-bold" style="font-weight:800"><?= esc($title) ?></h4>
    </div>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger border-0 rounded-3 mb-3">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="/superadmin/subscriptions/plans/update/<?= $plan['id'] ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nom du plan <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" value="<?= esc(old('nom', $plan['nom'])) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Identifiant (slug) <span class="text-danger">*</span></label>
                    <input type="text" name="slug" class="form-control" value="<?= esc(old('slug', $plan['slug'])) ?>" required
                           pattern="[a-z0-9_-]+" title="Minuscules, chiffres, tirets uniquement">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Prix mensuel (DA) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="prix_mensuel" class="form-control" value="<?= esc(old('prix_mensuel', $plan['prix_mensuel'])) ?>" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Prix annuel (DA) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="prix_annuel" class="form-control" value="<?= esc(old('prix_annuel', $plan['prix_annuel'])) ?>" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Max médecins</label>
                    <input type="number" name="max_medecins" class="form-control" value="<?= esc(old('max_medecins', $plan['max_medecins'])) ?>" min="0">
                    <div class="form-text">0 = illimité</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Max patients</label>
                    <input type="number" name="max_patients" class="form-control" value="<?= esc(old('max_patients', $plan['max_patients'])) ?>" min="0">
                    <div class="form-text">0 = illimité</div>
                </div>
                <div class="col-md-9">
                    <label class="form-label fw-semibold">Fonctionnalités incluses</label>
                    <textarea name="features" class="form-control" rows="5"><?= esc(old('features', $features)) ?></textarea>
                    <div class="form-text">Une fonctionnalité par ligne.</div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Ordre d'affichage</label>
                    <input type="number" name="ordre" class="form-control" value="<?= esc(old('ordre', $plan['ordre'])) ?>" min="0">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               <?= old('is_active', $plan['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="isActive">Actif</label>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                    </button>
                    <a href="/superadmin/subscriptions" class="btn btn-outline-secondary">Annuler</a>
                    <a href="/superadmin/subscriptions/plans/delete/<?= $plan['id'] ?>" class="btn btn-outline-danger ms-auto"
                       onclick="return confirm('Supprimer ce plan ? Cette action est irréversible.')">
                        <i class="bi bi-trash me-1"></i>Supprimer
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
