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
        .failed-card { background: #fff; border-radius: 24px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); padding: 60px 50px; text-align: center; max-width: 520px; width: 100%; }
        .failed-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 28px; }
        .btn-retry { background: linear-gradient(135deg, #1a56db, #7c3aed); border: none; border-radius: 12px; padding: 14px 32px; font-size: 1rem; font-weight: 700; color: #fff; text-decoration: none; display: inline-block; transition: all .3s; }
        .btn-retry:hover { opacity: .9; transform: translateY(-2px); color: #fff; }
    </style>
</head>
<body>
<div class="container px-3">
    <div class="failed-card mx-auto">
        <div class="failed-icon">
            <i class="bi bi-x-lg" style="font-size:2.2rem;color:#fff"></i>
        </div>

        <h1 style="font-size:1.8rem;font-weight:800;color:#0f172a;margin-bottom:12px">Paiement non abouti</h1>

        <?php
        $messages = [
            'cancelled'           => 'Vous avez annulé le paiement. Votre compte est en attente — vous pouvez réessayer.',
            'verification_failed' => 'Nous n\'avons pas pu vérifier votre paiement. Si vous avez été débité, contactez le support.',
            'amount_mismatch'     => 'Le montant reçu ne correspond pas. Veuillez réessayer ou contacter le support.',
            'missing_params'      => 'Paramètres de paiement manquants. Veuillez réessayer.',
            'invalid_ref'         => 'Référence de paiement invalide ou expirée.',
        ];
        $msg = $messages[$reason ?? 'unknown'] ?? 'Une erreur inattendue s\'est produite.';
        ?>
        <p class="text-muted mb-4"><?= esc($msg) ?></p>

        <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;padding:16px;margin-bottom:28px;font-size:.85rem;color:#9a3412;">
            <i class="bi bi-info-circle me-2"></i>
            Votre compte a bien été créé. Une fois le paiement effectué, il sera activé automatiquement.
        </div>

        <a href="/payment/checkout" class="btn-retry">
            <i class="bi bi-arrow-clockwise me-2"></i>Réessayer le paiement
        </a>

        <div class="mt-3">
            <a href="/login" style="font-size:.85rem;color:#64748b;text-decoration:none">
                Se connecter (compte inactif)
            </a>
        </div>

        <div class="mt-4" style="font-size:.8rem;color:#94a3b8">
            Problème persistant ? <a href="mailto:support@ikelyanemed.com" class="text-primary">support@ikelyanemed.com</a>
        </div>
    </div>
</div>
</body>
</html>
