<!DOCTYPE html>
<html lang="<?= session()->get('locale') ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'IkelyaneCRM') ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#059669">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        body { background: linear-gradient(135deg, #052e16 0%, #0a1628 50%, #064e3b 100%); min-height: 100vh; }
        body::before {
            content:'';position:fixed;top:-50%;left:-50%;width:200%;height:200%;
            background:radial-gradient(ellipse at 30% 20%, rgba(5,150,105,.2) 0%, transparent 60%),
                       radial-gradient(ellipse at 70% 80%, rgba(249,115,22,.12) 0%, transparent 60%);
            pointer-events:none;z-index:0;
        }
        .auth-card { border-radius:20px;box-shadow:0 25px 60px rgba(0,0,0,.4);border:1px solid rgba(5,150,105,.2);background:rgba(5,46,22,.75);backdrop-filter:blur(20px);position:relative;z-index:1; }
        .auth-brand { font-size:1.6rem;font-weight:800;color:#fff;letter-spacing:-1px; }
        .auth-brand span { background:linear-gradient(135deg,#059669,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent; }
        .form-control, .form-select { background:rgba(255,255,255,.06);border:1px solid rgba(5,150,105,.25);color:#e2e8f0;border-radius:10px;padding:11px 14px; }
        .form-control:focus, .form-select:focus { background:rgba(255,255,255,.08);border-color:#059669;box-shadow:0 0 0 3px rgba(5,150,105,.25);color:#fff; }
        .form-control::placeholder { color:rgba(148,163,184,.5); }
        .form-label { color:#94a3b8;font-size:.825rem;font-weight:600;margin-bottom:6px; }
        .input-group-text { background:rgba(255,255,255,.06);border:1px solid rgba(5,150,105,.25);color:#94a3b8;border-radius:10px 0 0 10px;border-right:none; }
        .input-group .form-control { border-left:none;border-radius:0 10px 10px 0; }
        .btn-login { background:linear-gradient(135deg,#059669,#f97316);border:none;padding:12px;font-size:1rem;font-weight:600;border-radius:10px;width:100%; }
        .btn-login:hover { opacity:.9;transform:translateY(-1px);transition:all .2s;color:#fff; }
        a { color:#6ee7b7; }
        a:hover { color:#a7f3d0; }
        .alert-danger { background:rgba(220,38,38,.15);border:1px solid rgba(220,38,38,.3);color:#fca5a5; }
        .alert-success { background:rgba(52,211,153,.15);border:1px solid rgba(52,211,153,.3);color:#6ee7b7; }
        .alert-warning { background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.25);color:#fcd34d; }
        /* Override Bootstrap primary to match CRM green */
        :root { --bs-primary: #059669; --bs-primary-rgb: 5,150,105; }
        .btn-primary { background:linear-gradient(135deg,#059669,#f97316);border:none; }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active { background:linear-gradient(135deg,#047857,#ea580c);border:none;box-shadow:0 0 0 3px rgba(5,150,105,.3); }
        .bg-primary { background:linear-gradient(135deg,#059669,#f97316) !important; }
        .btn-outline-secondary { border-color:rgba(5,150,105,.4);color:#6ee7b7; }
        .btn-outline-secondary:hover { background:rgba(5,150,105,.15);border-color:#059669;color:#6ee7b7; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3" style="position:relative;z-index:1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(() => {});</script>
</body>
</html>
