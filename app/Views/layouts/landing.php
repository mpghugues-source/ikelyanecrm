<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'IkelyaneCRM — CRM & ERP SaaS') ?></title>
    <meta name="description" content="IkelyaneCRM - La solution SaaS CRM & ERP complète. Contacts, leads, inventaire, projets, fournisseurs. Gérez votre entreprise depuis une seule plateforme.">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
    <meta name="theme-color" content="#059669">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="IkelyaneCRM">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --secondary: #f97316;
            --accent: #0891b2;
            --success: #10b981;
            --orange: #f97316;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
        }
        * { font-family: 'Inter', sans-serif; }
        body { overflow-x: hidden; }

        /* NAVBAR */
        .navbar-brand { font-size: 1.6rem; font-weight: 800; }
        .navbar-brand .crm { color: var(--primary); }
        .navbar-brand .ikel { color: var(--secondary); }
        .navbar { backdrop-filter: blur(10px); background: rgba(255,255,255,0.95) !important; border-bottom: 1px solid var(--gray-200); }
        .nav-link { font-weight: 500; color: #374151 !important; transition: color .2s; }
        .nav-link:hover { color: var(--primary) !important; }
        .btn-nav-login { border: 2px solid var(--primary); color: var(--primary) !important; border-radius: 8px; padding: 6px 20px; font-weight: 600; }
        .btn-nav-login:hover { background: var(--primary); color: #fff !important; }
        .btn-nav-signup { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff !important; border-radius: 8px; padding: 6px 20px; font-weight: 600; }
        .btn-nav-signup:hover { opacity: .9; transform: translateY(-1px); }

        /* HERO */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #052e16 0%, #134e37 50%, #064e3b 100%);
            display: flex; align-items: center;
            position: relative; overflow: hidden; padding-top: 80px;
        }
        .hero-section::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23059669' fill-opacity='0.07'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-badge { background: rgba(5,150,105,0.25); border: 1px solid rgba(5,150,105,0.5); color: #6ee7b7; padding: 6px 16px; border-radius: 50px; font-size: .85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; }
        .hero-title { font-size: 3.5rem; font-weight: 900; line-height: 1.1; color: #fff; }
        .hero-title .highlight { background: linear-gradient(135deg, #6ee7b7, #fb923c); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-subtitle { font-size: 1.2rem; color: #94a3b8; line-height: 1.7; }
        .btn-hero-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; border-radius: 12px; padding: 14px 32px; font-size: 1.05rem; font-weight: 700; color: #fff; transition: all .3s; }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 20px 40px rgba(5,150,105,0.4); color: #fff; }
        .btn-hero-secondary { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 14px 32px; font-size: 1.05rem; font-weight: 600; color: #fff; backdrop-filter: blur(10px); }
        .btn-hero-secondary:hover { background: rgba(255,255,255,0.2); color: #fff; }
        .hero-stats { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 32px; }
        .hero-stat-value { font-size: 2rem; font-weight: 800; color: #fff; }
        .hero-stat-label { font-size: .85rem; color: #94a3b8; }
        .hero-mockup { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 20px; backdrop-filter: blur(20px); }
        .mockup-header { background: rgba(255,255,255,0.08); border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .mockup-dot { width: 10px; height: 10px; border-radius: 50%; }
        .mockup-card { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 16px; margin-bottom: 12px; }
        .mockup-stat { font-size: 1.6rem; font-weight: 800; color: #6ee7b7; }
        .mockup-label { font-size: .75rem; color: #94a3b8; }

        /* MODULE TABS */
        .modules-section { padding: 100px 0; background: #fff; }
        .module-tab { cursor: pointer; border-radius: 16px; padding: 20px 24px; border: 2px solid var(--gray-200); transition: all .3s; background: #fff; }
        .module-tab:hover, .module-tab.active { border-color: var(--primary); background: #ecfdf5; }
        .module-tab .tab-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .module-tab .tab-title { font-weight: 700; color: var(--dark); margin-bottom: 4px; }
        .module-tab .tab-desc { font-size: .85rem; color: #64748b; }

        /* FEATURES */
        .features-section { padding: 100px 0; background: var(--gray-100); }
        .section-badge { background: rgba(5,150,105,0.1); color: var(--primary); padding: 6px 16px; border-radius: 50px; font-size: .85rem; font-weight: 600; display: inline-block; margin-bottom: 16px; }
        .section-title { font-size: 2.5rem; font-weight: 800; color: var(--dark); line-height: 1.2; }
        .feature-card { background: #fff; border-radius: 20px; padding: 32px; border: 1px solid var(--gray-200); transition: all .3s; height: 100%; }
        .feature-card:hover { transform: translateY(-8px); box-shadow: 0 30px 60px rgba(0,0,0,0.1); border-color: var(--primary); }
        .feature-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; }
        .feature-title { font-size: 1.1rem; font-weight: 700; color: var(--dark); margin-bottom: 12px; }
        .feature-desc { color: #64748b; line-height: 1.7; font-size: .95rem; }

        /* GEAR ANIMATION */
        .gear-container { position: relative; display: flex; align-items: center; justify-content: center; gap: 0; }
        .gear { display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; }
        .gear-big { width: 90px; height: 90px; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; font-size: 2.2rem; animation: spin 8s linear infinite; }
        .gear-small { width: 60px; height: 60px; background: linear-gradient(135deg, var(--secondary), var(--accent)); color: #fff; font-size: 1.5rem; animation: spin-reverse 6s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes spin-reverse { from { transform: rotate(0deg); } to { transform: rotate(-360deg); } }

        /* HOW IT WORKS */
        .how-section { padding: 100px 0; background: #fff; }
        .step-number { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; font-weight: 800; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .step-line { width: 2px; height: 60px; background: linear-gradient(180deg, var(--primary), transparent); margin: 8px 0 8px 23px; }

        /* PRICING */
        .pricing-section { padding: 100px 0; background: var(--gray-100); }
        .pricing-card { background: #fff; border-radius: 24px; padding: 40px; border: 2px solid var(--gray-200); transition: all .3s; height: 100%; }
        .pricing-card.popular { border-color: var(--primary); transform: scale(1.05); position: relative; }
        .pricing-card.popular::before { content: 'Plus populaire'; position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; padding: 4px 20px; border-radius: 50px; font-size: .8rem; font-weight: 700; white-space: nowrap; }
        .pricing-card:hover { box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
        .pricing-plan { font-size: .9rem; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; }
        .pricing-price { font-size: 3rem; font-weight: 900; color: var(--dark); line-height: 1; }
        .pricing-period { font-size: 1rem; color: #94a3b8; font-weight: 400; }
        .pricing-feature { display: flex; align-items: center; gap: 10px; padding: 8px 0; color: #374151; font-size: .95rem; }
        .pricing-feature i { color: var(--success); flex-shrink: 0; }
        .pricing-feature.disabled { color: #94a3b8; }
        .pricing-feature.disabled i { color: #cbd5e1; }
        .btn-pricing { border-radius: 12px; padding: 12px 24px; font-weight: 700; font-size: 1rem; width: 100%; transition: all .3s; }
        .btn-pricing-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none; color: #fff; }
        .btn-pricing-primary:hover { opacity: .9; transform: translateY(-2px); color: #fff; }
        .btn-pricing-outline { border: 2px solid var(--primary); color: var(--primary); background: transparent; }
        .btn-pricing-outline:hover { background: var(--primary); color: #fff; }

        /* TESTIMONIALS */
        .testimonials-section { padding: 100px 0; background: #fff; }
        .testimonial-card { background: var(--gray-100); border-radius: 20px; padding: 32px; border: 1px solid var(--gray-200); height: 100%; }
        .testimonial-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1.1rem; }
        .testimonial-stars { color: #f59e0b; }

        /* CTA */
        .cta-section { padding: 100px 0; background: linear-gradient(135deg, #052e16, #134e37); position: relative; overflow: hidden; }
        .cta-section::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(5,150,105,0.2) 0%, transparent 60%); }

        /* FOOTER */
        .footer { background: #0f172a; padding: 60px 0 30px; }
        .footer-brand { font-size: 1.4rem; font-weight: 800; }
        .footer-brand .crm { color: var(--primary); }
        .footer-brand .ikel { color: var(--secondary); }
        .footer-link { color: #94a3b8; text-decoration: none; display: block; margin-bottom: 8px; font-size: .9rem; transition: color .2s; }
        .footer-link:hover { color: #fff; }
        .footer-heading { color: #fff; font-weight: 700; margin-bottom: 16px; }
        .footer-divider { border-color: rgba(255,255,255,0.1); margin: 40px 0 20px; }
        .footer-copy { color: #64748b; font-size: .85rem; }

        /* ANIMATIONS */
        .fade-up { opacity: 0; transform: translateY(30px); transition: all .6s ease; }
        .fade-up.visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .pricing-card.popular { transform: scale(1); }
            .section-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <span class="ikel">Ikelya</span><span class="crm">neCRM</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="#modules">Modules</a></li>
                <li class="nav-item"><a class="nav-link" href="#fonctionnalites">Fonctionnalités</a></li>
                <li class="nav-item"><a class="nav-link" href="#comment">Comment ça marche</a></li>
                <li class="nav-item"><a class="nav-link" href="#tarifs">Tarifs</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            <div class="d-flex gap-2 mt-2 mt-lg-0">
                <a href="/login" class="nav-link btn-nav-login">Connexion</a>
                <a href="/register" class="nav-link btn-nav-signup">Essai gratuit</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero-section" id="hero">
    <div class="container position-relative z-1">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge mb-4">
                    <i class="bi bi-stars"></i>
                    Nouveau — Module ERP & Gestion de projets disponible
                </div>
                <h1 class="hero-title mb-4">
                    CRM &amp; ERP tout-en-un<br>
                    <span class="highlight">simple &amp; puissant</span>
                </h1>
                <p class="hero-subtitle mb-5">
                    IkelyaneCRM centralise vos contacts, leads, campagnes, stocks et projets dans une seule plateforme SaaS. Conçu pour les équipes commerciales et opérationnelles du monde entier.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="/register" class="btn btn-hero-primary">
                        <i class="bi bi-rocket-takeoff me-2"></i>Démarrer gratuitement
                    </a>
                    <a href="#modules" class="btn btn-hero-secondary">
                        <i class="bi bi-play-circle me-2"></i>Découvrir les modules
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="row g-4">
                        <div class="col-4">
                            <div class="hero-stat-value">200+</div>
                            <div class="hero-stat-label">Entreprises actives</div>
                        </div>
                        <div class="col-4">
                            <div class="hero-stat-value">15k+</div>
                            <div class="hero-stat-label">Leads traités</div>
                        </div>
                        <div class="col-4">
                            <div class="hero-stat-value">99.9%</div>
                            <div class="hero-stat-label">Disponibilité</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-mockup">
                    <div class="mockup-header">
                        <div class="mockup-dot" style="background:#ef4444"></div>
                        <div class="mockup-dot" style="background:#f59e0b"></div>
                        <div class="mockup-dot" style="background:#10b981"></div>
                        <span style="color:#94a3b8;font-size:.8rem;margin-left:8px">IkelyaneCRM Dashboard</span>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="mockup-card">
                                <div style="color:#94a3b8;font-size:.75rem;margin-bottom:4px"><i class="bi bi-people me-1"></i>Contacts</div>
                                <div class="mockup-stat">3,847</div>
                                <div class="mockup-label" style="color:#10b981">+18% ce mois</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mockup-card">
                                <div style="color:#94a3b8;font-size:.75rem;margin-bottom:4px"><i class="bi bi-funnel me-1"></i>Leads actifs</div>
                                <div class="mockup-stat" style="color:#fb923c">142</div>
                                <div class="mockup-label">32 en négociation</div>
                            </div>
                        </div>
                    </div>
                    <div class="mockup-card mb-3">
                        <div style="color:#94a3b8;font-size:.75rem;margin-bottom:12px"><i class="bi bi-graph-up me-1"></i>Pipeline commercial</div>
                        <div style="display:flex;gap:4px;align-items:flex-end;height:60px">
                            <?php $bars = [35,55,40,75,50,90,65,100,60,80,45,85]; foreach($bars as $h): ?>
                            <div style="flex:1;background:linear-gradient(180deg,#059669,#f97316);border-radius:4px 4px 0 0;height:<?= $h ?>%;opacity:.8"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="mockup-card text-center">
                                <div style="color:#10b981;font-size:1.2rem;font-weight:800">24</div>
                                <div class="mockup-label">Gagnés</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mockup-card text-center">
                                <div style="color:#f59e0b;font-size:1.2rem;font-weight:800">18</div>
                                <div class="mockup-label">En cours</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mockup-card text-center">
                                <div style="color:#ef4444;font-size:1.2rem;font-weight:800">5</div>
                                <div class="mockup-label">Perdus</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODULES CRM + ERP -->
<section class="modules-section" id="modules">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <span class="section-badge"><i class="bi bi-gear-fill me-1"></i>Modules</span>
            <h2 class="section-title">CRM + ERP intégrés dans une seule plateforme</h2>
            <p class="text-muted mt-3" style="max-width:600px;margin:0 auto">Deux moteurs puissants, un seul outil. Gérez votre cycle de vente et vos opérations sans changer d'application.</p>
        </div>
        <div class="row g-4 mb-5">
            <!-- CRM -->
            <div class="col-lg-6 fade-up">
                <div class="rounded-4 p-4 h-100" style="background:linear-gradient(135deg,#eff6ff,#f5f3ff);border:2px solid #dbeafe">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#059669,#f97316);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <div>
                            <h3 style="font-weight:800;color:var(--dark);margin:0">Module CRM</h3>
                            <div style="color:#64748b;font-size:.9rem">Gestion de la relation client</div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <?php foreach([
                            ['bi-people-fill','#d1fae5','#059669','Contacts','Base contacts complète avec segmentation et historique'],
                            ['bi-funnel-fill','#ffedd5','#f97316','Leads & Pipeline','Suivi des opportunités avec vue Kanban et scoring'],
                            ['bi-megaphone-fill','#d1fae5','#059669','Campagnes','Campagnes marketing multicanal avec suivi des résultats'],
                            ['bi-chat-dots-fill','#fef3c7','#d97706','Support & Cases','Gestion des tickets et demandes clients en temps réel'],
                        ] as $f): ?>
                        <div class="col-6">
                            <div class="bg-white rounded-3 p-3 d-flex align-items-start gap-2 h-100">
                                <div style="width:36px;height:36px;background:<?= $f[1] ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;color:<?= $f[2] ?>;flex-shrink:0">
                                    <i class="bi <?= $f[0] ?>"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.85rem;color:var(--dark)"><?= $f[3] ?></div>
                                    <div style="font-size:.78rem;color:#64748b;line-height:1.4"><?= $f[4] ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- ERP -->
            <div class="col-lg-6 fade-up">
                <div class="rounded-4 p-4 h-100" style="background:linear-gradient(135deg,#fff7ed,#fdf4ff);border:2px solid #fed7aa">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#f97316,#a855f7);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <div>
                            <h3 style="font-weight:800;color:var(--dark);margin:0">Module ERP</h3>
                            <div style="color:#64748b;font-size:.9rem">Planification des ressources</div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <?php foreach([
                            ['bi-box-seam-fill','#ffedd5','#f97316','Inventaire','Gestion des stocks avec mouvements et alertes de réapprovisionnement'],
                            ['bi-truck','#fce7f3','#db2777','Fournisseurs','Annuaire fournisseurs avec conditions commerciales et évaluations'],
                            ['bi-clipboard2-check-fill','#e0f2fe','#0284c7','Bons de commande','Création et suivi des commandes fournisseurs de A à Z'],
                            ['bi-kanban-fill','#f0fdf4','#16a34a','Projets','Gestion de projets avec tâches, jalons et suivi des équipes'],
                        ] as $f): ?>
                        <div class="col-6">
                            <div class="bg-white rounded-3 p-3 d-flex align-items-start gap-2 h-100">
                                <div style="width:36px;height:36px;background:<?= $f[1] ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;color:<?= $f[2] ?>;flex-shrink:0">
                                    <i class="bi <?= $f[0] ?>"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.85rem;color:var(--dark)"><?= $f[3] ?></div>
                                    <div style="font-size:.78rem;color:#64748b;line-height:1.4"><?= $f[4] ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Engrenages animés -->
        <div class="text-center py-4 fade-up">
            <div class="gear-container d-inline-flex gap-2 align-items-center">
                <div class="gear gear-big"><i class="bi bi-gear-fill"></i></div>
                <div class="gear gear-small"><i class="bi bi-gear-fill"></i></div>
                <div class="gear gear-big" style="animation-delay:-2s"><i class="bi bi-gear-fill"></i></div>
            </div>
            <p class="text-muted mt-3">CRM &amp; ERP synchronisés en temps réel</p>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features-section" id="fonctionnalites">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <span class="section-badge"><i class="bi bi-lightning-charge me-1"></i>Fonctionnalités</span>
            <h2 class="section-title">Tout ce dont votre équipe a besoin</h2>
            <p class="text-muted mt-3" style="max-width:600px;margin:0 auto">Une solution complète pensée pour les équipes commerciales et opérationnelles. Simple à prendre en main, puissante à exploiter.</p>
        </div>
        <div class="row g-4">
            <?php
            $features = [
                ['icon'=>'bi-people-fill','color'=>'#d1fae5','icon_color'=>'#059669','title'=>'Gestion des Contacts','desc'=>'Base de contacts enrichie avec historique complet, segmentation, tags et notes d\'activité.'],
                ['icon'=>'bi-funnel-fill','color'=>'#ffedd5','icon_color'=>'#f97316','title'=>'Pipeline de ventes','desc'=>'Vue Kanban des opportunités, scoring automatique des leads et prévisions de revenus.'],
                ['icon'=>'bi-megaphone','color'=>'#d1fae5','icon_color'=>'#059669','title'=>'Campagnes marketing','desc'=>'Créez et suivez des campagnes email, mesurez les conversions et optimisez vos actions.'],
                ['icon'=>'bi-box-seam','color'=>'#fef3c7','icon_color'=>'#d97706','title'=>'Gestion des stocks','desc'=>'Suivi des niveaux de stock, mouvements d\'entrée/sortie, alertes et valorisation.'],
                ['icon'=>'bi-graph-up-arrow','color'=>'#fce7f3','icon_color'=>'#db2777','title'=>'Tableaux de bord','desc'=>'Indicateurs temps réel : chiffre d\'affaires, pipeline, taux de conversion, performance équipe.'],
                ['icon'=>'bi-shield-lock','color'=>'#e0f2fe','icon_color'=>'#0284c7','title'=>'Sécurité Multi-tenant','desc'=>'Données isolées par entreprise, chiffrement fort, accès par rôles, conformité RGPD.'],
                ['icon'=>'bi-chat-dots','color'=>'#f0fdf4','icon_color'=>'#16a34a','title'=>'Messagerie interne','desc'=>'Communicez en équipe, partagez des fichiers et restez synchronisé sur les dossiers clients.'],
                ['icon'=>'bi-kanban','color'=>'#fef9c3','icon_color'=>'#ca8a04','title'=>'Gestion de projets','desc'=>'Planifiez les projets, assignez les tâches, suivez l\'avancement avec jalons et délais.'],
            ];
            foreach($features as $i => $f): ?>
            <div class="col-md-6 col-lg-3 fade-up" style="transition-delay: <?= $i * 0.1 ?>s">
                <div class="feature-card">
                    <div class="feature-icon" style="background:<?= $f['color'] ?>; color:<?= $f['icon_color'] ?>">
                        <i class="bi <?= $f['icon'] ?>"></i>
                    </div>
                    <div class="feature-title"><?= $f['title'] ?></div>
                    <div class="feature-desc"><?= $f['desc'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="comment">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 fade-up">
                <span class="section-badge"><i class="bi bi-map me-1"></i>Comment ça marche</span>
                <h2 class="section-title mb-4">Opérationnel en moins de 5 minutes</h2>
                <?php
                $steps = [
                    ['num'=>'1','title'=>'Créez votre compte','desc'=>'Inscrivez votre entreprise en 2 minutes. Aucune carte de crédit requise pour l\'essai gratuit.'],
                    ['num'=>'2','title'=>'Configurez vos équipes','desc'=>'Ajoutez vos utilisateurs, définissez les rôles (admin, commercial, support, manager) et configurez les accès.'],
                    ['num'=>'3','title'=>'Importez vos données','desc'=>'Importez vos contacts, prospects et produits depuis votre ancien outil ou commencez directement.'],
                    ['num'=>'4','title'=>'Gérez au quotidien','desc'=>'Suivez vos leads, gérez vos stocks, pilotez vos projets — tout depuis un seul tableau de bord.'],
                ];
                foreach($steps as $i => $step): ?>
                <div class="d-flex gap-3 mb-2">
                    <div class="d-flex flex-column align-items-center">
                        <div class="step-number"><?= $step['num'] ?></div>
                        <?php if($i < count($steps)-1): ?><div class="step-line"></div><?php endif; ?>
                    </div>
                    <div class="pb-3">
                        <div class="fw-700" style="font-weight:700;margin-bottom:4px"><?= $step['title'] ?></div>
                        <div class="text-muted" style="font-size:.9rem"><?= $step['desc'] ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="col-lg-7 fade-up">
                <div class="bg-light rounded-4 p-4" style="border:2px solid var(--gray-200)">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="bg-white rounded-3 p-3 d-flex align-items-center gap-3 shadow-sm">
                                <div style="width:40px;height:40px;background:linear-gradient(135deg,#059669,#f97316);border-radius:10px;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-person-plus text-white"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.9rem">Nouveau contact ajouté</div>
                                    <div class="text-muted" style="font-size:.8rem">Karim Mansouri · SARL TechDZ · Alger</div>
                                </div>
                                <span class="ms-auto badge" style="background:#d1fae5;color:#059669;font-size:.75rem">Qualifié</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-white rounded-3 p-3 d-flex align-items-center gap-3 shadow-sm">
                                <div style="width:40px;height:40px;background:linear-gradient(135deg,#f97316,#fbbf24);border-radius:10px;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-funnel text-white"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.9rem">Lead passé en négociation</div>
                                    <div class="text-muted" style="font-size:.8rem">Contrat ERP — 12 500 € · Probabilité 75%</div>
                                </div>
                                <span class="ms-auto badge" style="background:#ffedd5;color:#f97316;font-size:.75rem">Chaud</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-white rounded-3 p-3 d-flex align-items-center gap-3 shadow-sm">
                                <div style="width:40px;height:40px;background:linear-gradient(135deg,#f97316,#ef4444);border-radius:10px;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-box-seam text-white"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.9rem">Alerte stock faible</div>
                                    <div class="text-muted" style="font-size:.8rem">Produit REF-0042 · 3 unités restantes</div>
                                </div>
                                <span class="ms-auto badge" style="background:#fef3c7;color:#d97706;font-size:.75rem">À réapprovisionner</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-white rounded-3 p-3 d-flex align-items-center gap-3 shadow-sm">
                                <div style="width:40px;height:40px;background:linear-gradient(135deg,#059669,#10b981);border-radius:10px;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.9rem">Projet clôturé avec succès</div>
                                    <div class="text-muted" style="font-size:.8rem">Déploiement CRM — 14 tâches complétées</div>
                                </div>
                                <span class="ms-auto badge" style="background:#d1fae5;color:#059669;font-size:.75rem">Terminé</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="pricing-section" id="tarifs">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <span class="section-badge"><i class="bi bi-tag me-1"></i>Tarifs</span>
            <h2 class="section-title">Des plans adaptés à chaque équipe</h2>
            <p class="text-muted mt-3">Commencez gratuitement. Évoluez selon vos besoins. Sans engagement.</p>
        </div>
        <div class="row g-4 align-items-center justify-content-center">
            <!-- Starter -->
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="pricing-card">
                    <div class="pricing-plan">Starter</div>
                    <div class="pricing-price mt-2">€0<span class="pricing-period">/mois</span></div>
                    <p class="text-muted mt-2 mb-4">Parfait pour démarrer et tester la plateforme.</p>
                    <a href="/register" class="btn btn-pricing btn-pricing-outline mb-4">Commencer gratuitement</a>
                    <hr class="my-4">
                    <?php foreach(['3 utilisateurs','Jusqu\'à 500 contacts','Module CRM de base','Leads & Pipeline','Messagerie interne','Support par email'] as $f): ?>
                    <div class="pricing-feature"><i class="bi bi-check-circle-fill"></i><?= $f ?></div>
                    <?php endforeach; ?>
                    <?php foreach(['Module ERP','Campagnes marketing','Rapports avancés','Support prioritaire'] as $f): ?>
                    <div class="pricing-feature disabled"><i class="bi bi-x-circle-fill"></i><?= $f ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Business -->
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="pricing-card popular">
                    <div class="pricing-plan">Business</div>
                    <div class="pricing-price mt-2">€49<span class="pricing-period">/mois</span></div>
                    <p class="text-muted mt-2 mb-4">La solution complète CRM + ERP pour les équipes en croissance.</p>
                    <a href="/register?plan=business" class="btn btn-pricing btn-pricing-primary mb-4">Démarrer maintenant</a>
                    <hr class="my-4">
                    <?php foreach(['Jusqu\'à 15 utilisateurs','Contacts illimités','CRM complet (leads, campagnes, support)','Module ERP (stocks, fournisseurs, projets)','Bons de commande','Tableaux de bord avancés','Export PDF & CSV','Support prioritaire'] as $f): ?>
                    <div class="pricing-feature"><i class="bi bi-check-circle-fill"></i><?= $f ?></div>
                    <?php endforeach; ?>
                    <?php foreach(['API personnalisée','Intégrations sur mesure'] as $f): ?>
                    <div class="pricing-feature disabled"><i class="bi bi-x-circle-fill"></i><?= $f ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Enterprise -->
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="pricing-card">
                    <div class="pricing-plan">Enterprise</div>
                    <div class="pricing-price mt-2">€99<span class="pricing-period">/mois</span></div>
                    <p class="text-muted mt-2 mb-4">Pour les grandes équipes et groupes multi-sites.</p>
                    <a href="/register?plan=enterprise" class="btn btn-pricing btn-pricing-outline mb-4">Démarrer maintenant</a>
                    <hr class="my-4">
                    <?php foreach(['Utilisateurs illimités','Tout le plan Business','API REST complète','Intégrations personnalisées','Multi-entités & multi-sites','Rapports personnalisés','Support dédié 24/7','Formation incluse'] as $f): ?>
                    <div class="pricing-feature"><i class="bi bi-check-circle-fill"></i><?= $f ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials-section" id="temoignages">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <span class="section-badge"><i class="bi bi-chat-quote me-1"></i>Témoignages</span>
            <h2 class="section-title">Ce que disent nos utilisateurs</h2>
        </div>
        <div class="row g-4">
            <?php
            $testimonials = [
                ['name'=>'Youssef Tlemçani','role'=>'Directeur Commercial, Alger','text'=>'IkelyaneCRM a transformé notre équipe commerciale. Le suivi des leads en Kanban est intuitif et mes commerciaux l\'ont adopté en une journée. Notre taux de conversion a augmenté de 30%.','initial'=>'Y'],
                ['name'=>'Fatima Ouali','role'=>'Responsable Opérations, Tunis','text'=>'La combinaison CRM + ERP dans un seul outil est ce que nous cherchions depuis des années. Gestion des stocks et suivi clients enfin synchronisés. Un gain de temps énorme.','initial'=>'F'],
                ['name'=>'Marc Leclerc','role'=>'CEO, PME, Bruxelles','text'=>'Un outil complet à un prix raisonnable. La gestion des bons de commande et le suivi de projet sont excellents. Le support est réactif et les mises à jour régulières.','initial'=>'M'],
            ];
            foreach($testimonials as $t): ?>
            <div class="col-md-4 fade-up">
                <div class="testimonial-card">
                    <div class="testimonial-stars mb-3">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-muted mb-4" style="font-style:italic;line-height:1.7">"<?= $t['text'] ?>"</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="testimonial-avatar"><?= $t['initial'] ?></div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem"><?= $t['name'] ?></div>
                            <div class="text-muted" style="font-size:.8rem"><?= $t['role'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container text-center position-relative z-1">
        <div class="fade-up">
            <h2 style="font-size:2.8rem;font-weight:900;color:#fff;margin-bottom:20px">
                Prêt à booster votre équipe ?
            </h2>
            <p style="color:#94a3b8;font-size:1.1rem;max-width:500px;margin:0 auto 40px">
                Rejoignez plus de 200 entreprises qui font confiance à IkelyaneCRM. Essai gratuit 30 jours, sans engagement.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="/register" class="btn btn-hero-primary btn-lg">
                    <i class="bi bi-rocket-takeoff me-2"></i>Commencer maintenant
                </a>
                <a href="#contact" class="btn btn-hero-secondary btn-lg">
                    <i class="bi bi-telephone me-2"></i>Nous contacter
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section class="py-5 bg-white" id="contact" style="padding:80px 0">
    <div class="container">
        <div class="text-center mb-5 fade-up">
            <span class="section-badge"><i class="bi bi-envelope me-1"></i>Contact</span>
            <h2 class="section-title">Une question ? Contactez-nous</h2>
            <p class="text-muted mt-2">Notre équipe vous répond sous 24h, partout dans le monde.</p>
        </div>
        <div class="row g-5 align-items-start justify-content-center">
            <!-- Infos -->
            <div class="col-lg-4 fade-up">
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="feature-icon flex-shrink-0" style="background:#d1fae5;color:#059669;width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.2rem">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;margin-bottom:2px">Email</div>
                        <div class="text-muted small">contact@ikelyanecrm.com</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="feature-icon flex-shrink-0" style="background:#d1fae5;color:#059669;width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.2rem">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;margin-bottom:2px">WhatsApp</div>
                        <div class="text-muted small">Disponible via le formulaire</div>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <div class="feature-icon flex-shrink-0" style="background:#ffedd5;color:#f97316;width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.2rem">
                        <i class="bi bi-globe2"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;margin-bottom:2px">Présence mondiale</div>
                        <div class="text-muted small">Disponible partout dans le monde</div>
                    </div>
                </div>
            </div>
            <!-- Formulaire -->
            <div class="col-lg-6 fade-up">
                <?php if(session()->getFlashdata('contact_success')): ?>
                <div class="alert alert-success border-0 rounded-3 mb-4">
                    <i class="bi bi-check-circle me-2"></i>Message envoyé ! Nous vous répondons sous 24h.
                </div>
                <?php endif; ?>
                <form action="/contact" method="POST" class="p-4 rounded-4" style="background:#f8fafc;border:1px solid #e2e8f0">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-weight:600;font-size:.9rem">Nom complet</label>
                            <input type="text" name="nom" class="form-control rounded-3" placeholder="Jean Dupont" required style="border:2px solid #e2e8f0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-weight:600;font-size:.9rem">Email professionnel</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="contact@entreprise.com" required style="border:2px solid #e2e8f0">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-weight:600;font-size:.9rem">Sujet</label>
                            <select name="sujet" class="form-select rounded-3" style="border:2px solid #e2e8f0">
                                <option value="demo">Demander une démo</option>
                                <option value="tarifs">Renseignement sur les tarifs</option>
                                <option value="support">Support technique</option>
                                <option value="partenariat">Partenariat</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-weight:600;font-size:.9rem">Message</label>
                            <textarea name="message" class="form-control rounded-3" rows="4" placeholder="Décrivez votre besoin..." required style="border:2px solid #e2e8f0;resize:none"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn w-100 fw-700 py-3 rounded-3" style="background:linear-gradient(135deg,#059669,#f97316);color:#fff;font-weight:700;border:none">
                                <i class="bi bi-send me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand mb-3">
                    <span class="ikel">Ikelya</span><span class="crm">neCRM</span>
                </div>
                <p style="color:#94a3b8;font-size:.9rem;line-height:1.7">La solution SaaS CRM & ERP pensée pour les équipes commerciales et opérationnelles du monde entier.</p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" style="color:#475569;font-size:1.3rem"><i class="bi bi-facebook"></i></a>
                    <a href="#" style="color:#475569;font-size:1.3rem"><i class="bi bi-instagram"></i></a>
                    <a href="#" style="color:#475569;font-size:1.3rem"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <div class="footer-heading">Produit</div>
                <a href="#modules" class="footer-link">Modules CRM & ERP</a>
                <a href="#fonctionnalites" class="footer-link">Fonctionnalités</a>
                <a href="#tarifs" class="footer-link">Tarifs</a>
                <a href="/register" class="footer-link">Essai gratuit</a>
            </div>
            <div class="col-lg-2 col-6">
                <div class="footer-heading">Accès</div>
                <a href="/login" class="footer-link">Espace Admin</a>
                <a href="/login" class="footer-link">Espace Commercial</a>
                <a href="/login" class="footer-link">Espace Manager</a>
            </div>
            <div class="col-lg-2 col-6">
                <div class="footer-heading">Support</div>
                <a href="#contact" class="footer-link">Contact</a>
                <a href="#" class="footer-link">Documentation</a>
                <a href="#" class="footer-link">FAQ</a>
            </div>
            <div class="col-lg-2 col-6">
                <div class="footer-heading">Légal</div>
                <a href="#" class="footer-link">Conditions d'utilisation</a>
                <a href="#" class="footer-link">Politique de confidentialité</a>
                <a href="#" class="footer-link">RGPD</a>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="footer-copy">© <?= date('Y') ?> IkelyaneCRM. Tous droits réservés.</div>
            <div class="footer-copy">Fait avec <i class="bi bi-heart-fill text-danger"></i> pour le monde entier</div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

window.addEventListener('scroll', () => {
    const nav = document.querySelector('.navbar');
    nav.style.boxShadow = window.scrollY > 20 ? '0 4px 20px rgba(0,0,0,0.1)' : 'none';
});

document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if(target) { e.preventDefault(); target.scrollIntoView({behavior:'smooth', block:'start'}); }
    });
});
</script>
<!-- PWA Service Worker -->
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
}
</script>
</body>
</html>
