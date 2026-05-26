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
        .card-expired { background: #fff; border-radius: 24px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); padding: 0; overflow: hidden; max-width: 680px; width: 100%; }
        .card-header-expired { background: linear-gradient(135deg, #ef4444, #dc2626); padding: 40px; text-align: center; }
        .card-body-expired { padding: 40px; }
        .plan-card { border: 2px solid #e2e8f0; border-radius: 16px; padding: 24px; cursor: pointer; transition: all .2s; }
        .plan-card:hover { border-color: #1a56db; background: #eff6ff; }
        .plan-card.selected { border-color: #1a56db; background: #eff6ff; }
        .plan-card.popular { border-color: #1a56db; position: relative; }
        .plan-card.popular::before { content: 'Recommandé'; position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #1a56db, #7c3aed); color: #fff; padding: 3px 16px; border-radius: 50px; font-size: .75rem; font-weight: 700; white-space: nowrap; }
        .btn-renew { background: linear-gradient(135deg, #1a56db, #7c3aed); border: none; border-radius: 12px; padding: 14px 32px; font-weight: 700; color: #fff; width: 100%; font-size: 1rem; transition: all .3s; }
        .btn-renew:hover { opacity: .9; transform: translateY(-2px); color: #fff; }
        .cycle-toggle { background: #f1f5f9; border-radius: 10px; padding: 4px; display: inline-flex; gap: 4px; margin-bottom: 24px; }
        .cycle-btn { padding: 8px 20px; border-radius: 8px; border: none; background: transparent; font-weight: 600; font-size: .9rem; color: #64748b; cursor: pointer; transition: all .2s; }
        .cycle-btn.active { background: #fff; color: #1a56db; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    </style>
</head>
<body>
<div class="container px-3">
    <div class="card-expired mx-auto">
        <div class="card-header-expired">
            <div style="width:64px;height:64px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <i class="bi bi-calendar-x" style="font-size:1.8rem;color:#fff"></i>
            </div>
            <h2 style="color:#fff;font-weight:800;font-size:1.6rem;margin-bottom:8px">Abonnement expiré</h2>
            <p style="color:rgba(255,255,255,0.8);font-size:.95rem;margin:0">
                L'abonnement de <strong><?= esc($tenant['nom'] ?? 'votre clinique') ?></strong> a expiré.<br>
                Renouvelez pour continuer à utiliser IkelyaneMed.
            </p>
        </div>

        <div class="card-body-expired">
            <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger rounded-3 mb-4"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <!-- Cycle -->
            <div class="text-center">
                <div class="cycle-toggle" id="cycleToggle">
                    <button class="cycle-btn active" onclick="setCycle('monthly', this)">Mensuel</button>
                    <button class="cycle-btn" onclick="setCycle('annual', this)">
                        Annuel <span style="background:#dcfce7;color:#15803d;font-size:.7rem;font-weight:700;padding:2px 6px;border-radius:50px;margin-left:4px">-17%</span>
                    </button>
                </div>
            </div>

            <form action="/abonnement/renouveler" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="cycle" id="cycleInput" value="monthly">

                <!-- Plans -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="plan-card" onclick="selectPlan('basic', this)">
                            <div style="font-size:.8rem;font-weight:700;color:#1a56db;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Basic</div>
                            <div style="font-size:1.8rem;font-weight:800;color:#0f172a">
                                <span class="price-display" data-monthly="€49" data-annual="€490">€49</span>
                                <span style="font-size:.9rem;font-weight:400;color:#94a3b8" id="periodBasic">/mois</span>
                            </div>
                            <div style="font-size:.8rem;color:#64748b;margin-top:8px">10 médecins · 1 000 patients</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="plan-card popular selected" onclick="selectPlan('premium', this)">
                            <div style="font-size:.8rem;font-weight:700;color:#1a56db;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Premium</div>
                            <div style="font-size:1.8rem;font-weight:800;color:#0f172a">
                                <span class="price-display" data-monthly="€99" data-annual="€990">€99</span>
                                <span style="font-size:.9rem;font-weight:400;color:#94a3b8" id="periodPremium">/mois</span>
                            </div>
                            <div style="font-size:.8rem;color:#64748b;margin-top:8px">Illimité · Tous les modules</div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="plan" id="planInput" value="premium">

                <button type="submit" class="btn-renew">
                    <i class="bi bi-arrow-clockwise me-2"></i>Renouveler mon abonnement
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="/logout" class="text-muted" style="font-size:.85rem;text-decoration:none">
                    <i class="bi bi-box-arrow-right me-1"></i>Se déconnecter
                </a>
                <span class="text-muted mx-2">·</span>
                <a href="mailto:support@ikelyanemed.com" class="text-muted" style="font-size:.85rem;text-decoration:none">
                    Contacter le support
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let currentCycle = 'monthly';
let currentPlan  = 'premium';

function setCycle(cycle, btn) {
    currentCycle = cycle;
    document.getElementById('cycleInput').value = cycle;
    document.querySelectorAll('.cycle-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const period = cycle === 'annual' ? '/an' : '/mois';
    document.getElementById('periodBasic').textContent   = period;
    document.getElementById('periodPremium').textContent = period;

    document.querySelectorAll('.price-display').forEach(el => {
        el.textContent = cycle === 'annual' ? el.dataset.annual : el.dataset.monthly;
    });
}

function selectPlan(plan, card) {
    currentPlan = plan;
    document.getElementById('planInput').value = plan;
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
}
</script>
</body>
</html>
