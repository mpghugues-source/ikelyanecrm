<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .success-card { background: #fff; border-radius: 24px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); padding: 60px 50px; text-align: center; max-width: 520px; width: 100%; }
        .success-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #22c55e, #16a34a); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 28px; }
        .btn-dashboard { background: linear-gradient(135deg, #1a56db, #7c3aed); border: none; border-radius: 12px; padding: 14px 32px; font-size: 1rem; font-weight: 700; color: #fff; text-decoration: none; display: inline-block; transition: all .3s; }
        .btn-dashboard:hover { opacity: .9; transform: translateY(-2px); color: #fff; }
    </style>
</head>
<body>
<div class="container px-3">
    <div class="success-card mx-auto">
        <div class="success-icon">
            <i class="bi bi-check-lg" style="font-size:2.5rem;color:#fff"></i>
        </div>

        <h1 style="font-size:1.8rem;font-weight:800;color:#0f172a;margin-bottom:12px">Paiement confirmé !</h1>
        <p class="text-muted mb-4">
            Votre abonnement est actif. Bienvenue sur IkelyaneMed,
            <strong><?= esc(session()->get('tenant_nom') ?? 'votre clinique') ?></strong> !
        </p>

        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:20px;margin-bottom:32px">
            <div style="font-size:.9rem;color:#15803d;font-weight:600;margin-bottom:8px">
                <i class="bi bi-envelope-check me-2"></i>Confirmation envoyée par email
            </div>
            <div style="font-size:.85rem;color:#166534">
                Un reçu de paiement a été envoyé à <strong><?= esc(session()->get('email') ?? '') ?></strong>
            </div>
        </div>

        <a href="/admin/dashboard" class="btn-dashboard">
            <i class="bi bi-speedometer2 me-2"></i>Accéder à mon espace
        </a>

        <div class="mt-4" style="font-size:.8rem;color:#94a3b8">
            Besoin d'aide ? <a href="mailto:support@ikelyanemed.com" class="text-primary">support@ikelyanemed.com</a>
        </div>
    </div>
</div>
</body>
</html>
