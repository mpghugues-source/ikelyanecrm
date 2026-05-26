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
        .card-inactive { background: #fff; border-radius: 24px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); padding: 60px 50px; text-align: center; max-width: 500px; width: 100%; }
    </style>
</head>
<body>
<div class="container px-3">
    <div class="card-inactive mx-auto">
        <div style="width:72px;height:72px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
            <i class="bi bi-slash-circle" style="font-size:2rem;color:#ef4444"></i>
        </div>
        <h2 style="font-weight:800;font-size:1.6rem;color:#0f172a;margin-bottom:12px">Compte désactivé</h2>
        <p class="text-muted mb-4">
            Votre compte a été désactivé par l'administrateur de la plateforme.<br>
            Veuillez contacter le support pour plus d'informations.
        </p>
        <a href="mailto:support@ikelyanemed.com" class="btn w-100 py-3 rounded-3 fw-700 mb-3"
           style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;border:none;font-weight:700">
            <i class="bi bi-envelope me-2"></i>Contacter le support
        </a>
        <a href="/logout" class="text-muted" style="font-size:.85rem;text-decoration:none">
            <i class="bi bi-box-arrow-right me-1"></i>Se déconnecter
        </a>
    </div>
</div>
</body>
</html>
