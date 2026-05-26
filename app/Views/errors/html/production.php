<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur serveur | IkeylaneMed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f0f4ff; min-height: 100vh; display: flex; align-items: center; }
        .error-icon { font-size: 5rem; color: #dc3545; }
        .brand { color: #2563eb; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container text-center py-5">
        <i class="bi bi-exclamation-triangle-fill error-icon"></i>
        <h2 class="mt-3 mb-1">Une erreur est survenue</h2>
        <p class="text-muted mb-4">
            Le serveur a rencontré un problème. Veuillez réessayer ultérieurement.<br>
            Si le problème persiste, contactez l'administrateur.
        </p>
        <a href="javascript:history.back()" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
        <a href="/" class="btn btn-primary">
            <i class="bi bi-house me-1"></i>Accueil
        </a>
        <div class="mt-5 text-muted small">
            <span class="brand">IkeylaneMed</span> — Système de gestion médicale
        </div>
    </div>
</body>
</html>
