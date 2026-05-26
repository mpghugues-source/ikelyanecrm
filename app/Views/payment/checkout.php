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
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); min-height: 100vh; display: flex; align-items: center; }
        .checkout-card { background: #fff; border-radius: 24px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); overflow: hidden; }
        .checkout-left { background: linear-gradient(135deg, #1a56db, #7c3aed); padding: 48px 40px; }
        .checkout-right { padding: 48px 40px; }
        .plan-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff; padding: 8px 18px; border-radius: 50px; font-size: .85rem; font-weight: 700; margin-bottom: 24px; }
        .cycle-btn { border: 2px solid #e2e8f0; border-radius: 12px; padding: 14px 20px; cursor: pointer; transition: all .2s; text-align: center; }
        .cycle-btn:hover { border-color: #1a56db; }
        .cycle-btn.active { border-color: #1a56db; background: #eff6ff; }
        .cycle-btn .price { font-size: 1.6rem; font-weight: 800; color: #0f172a; }
        .cycle-btn .period { font-size: .8rem; color: #64748b; }
        .cycle-btn .badge-economy { background: #dcfce7; color: #15803d; font-size: .7rem; font-weight: 700; padding: 2px 8px; border-radius: 50px; display: inline-block; margin-top: 4px; }
        .feature-item { display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.85); margin-bottom: 12px; font-size: .9rem; }
        .feature-item i { color: #93c5fd; }
        .btn-pay { background: linear-gradient(135deg, #1a56db, #7c3aed); border: none; border-radius: 14px; padding: 16px; font-size: 1.05rem; font-weight: 700; color: #fff; width: 100%; transition: all .3s; }
        .btn-pay:hover { opacity: .9; transform: translateY(-2px); box-shadow: 0 15px 40px rgba(26,86,219,.4); color: #fff; }
        .btn-pay:disabled { opacity: .6; transform: none; cursor: not-allowed; }
        .security-badge { display: flex; align-items: center; gap: 8px; color: #64748b; font-size: .8rem; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 24px; }
        .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: .9rem; }
        .summary-row.total { font-weight: 800; font-size: 1.05rem; border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 6px; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="checkout-card">
                <div class="row g-0">

                    <!-- LEFT — Récapitulatif -->
                    <div class="col-lg-5 checkout-left">
                        <a href="/" class="text-white text-decoration-none">
                            <div style="font-size:1.6rem;font-weight:800;margin-bottom:6px">IkelyaneMed</div>
                        </a>
                        <p style="color:rgba(255,255,255,0.6);font-size:.9rem;margin-bottom:28px">Plateforme de gestion médicale SaaS</p>

                        <div class="plan-badge">
                            <i class="bi bi-box-seam-fill"></i>
                            Plan <?= esc($plan['nom']) ?>
                        </div>

                        <div style="color:rgba(255,255,255,0.9);font-size:.95rem;margin-bottom:28px">
                            Ce que vous obtenez avec le plan <strong><?= esc($plan['nom']) ?></strong> :
                        </div>

                        <?php if ($plan_slug === 'basic'): ?>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Jusqu'à 10 médecins</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Jusqu'à 1 000 patients</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Gestion rendez-vous & ordonnances</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Pharmacie & Laboratoire</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Export PDF & CSV</div>
                        <?php else: ?>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Médecins & patients illimités</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Tous les modules (Radiologie, Ambulances, RH)</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Rapports avancés & statistiques</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Téléconsultation intégrée</div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i>Support prioritaire 24/7</div>
                        <?php endif; ?>

                        <div class="mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.2)">
                            <div class="security-badge" style="color:rgba(255,255,255,0.6)">
                                <i class="bi bi-shield-lock-fill" style="color:#93c5fd"></i>
                                Paiement sécurisé — Annulation possible à tout moment
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT — Paiement -->
                    <div class="col-lg-7 checkout-right">
                        <div style="margin-bottom:8px;color:#64748b;font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Étape 2 sur 2</div>
                        <h2 style="font-size:1.7rem;font-weight:800;color:#0f172a;margin-bottom:6px">Finaliser l'abonnement</h2>
                        <p class="text-muted mb-4" style="font-size:.9rem">Clinique : <strong><?= esc($tenant_nom) ?></strong></p>

                        <!-- Choix du cycle -->
                        <div style="font-weight:700;font-size:.95rem;color:#374151;margin-bottom:12px">Choisissez votre cycle de facturation</div>
                        <div class="row g-3 mb-4" id="cycleSelector">
                            <div class="col-6">
                                <div class="cycle-btn <?= $cycle === 'monthly' ? 'active' : '' ?>" onclick="selectCycle('monthly')">
                                    <div class="period">Mensuel</div>
                                    <div class="price">€<?= number_format($plan['mensuel'], 0) ?></div>
                                    <div class="period">/ mois</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="cycle-btn <?= $cycle === 'annual' ? 'active' : '' ?>" onclick="selectCycle('annual')">
                                    <div class="period">Annuel</div>
                                    <div class="price">€<?= number_format($plan['annuel'], 0) ?></div>
                                    <div class="period">/ an</div>
                                    <?php $saving = round((1 - $plan['annuel'] / ($plan['mensuel'] * 12)) * 100); ?>
                                    <div class="badge-economy">Économie <?= $saving ?>%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Récapitulatif -->
                        <div class="summary-box">
                            <div style="font-weight:700;font-size:.9rem;margin-bottom:12px;color:#374151">Récapitulatif</div>
                            <div class="summary-row">
                                <span class="text-muted">Plan <?= esc($plan['nom']) ?></span>
                                <span id="summaryAmount">€<?= number_format($amount, 2) ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="text-muted">Cycle</span>
                                <span id="summaryCycle"><?= $cycle === 'annual' ? 'Annuel' : 'Mensuel' ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="text-muted">Renouvellement</span>
                                <span id="summaryRenew"><?= $cycle === 'annual' ? 'Dans 1 an' : 'Dans 1 mois' ?></span>
                            </div>
                            <div class="summary-row total">
                                <span>Total dû maintenant</span>
                                <span id="summaryTotal" style="color:#1a56db">€<?= number_format($amount, 2) ?></span>
                            </div>
                        </div>

                        <!-- Méthodes de paiement -->
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:20px">
                            <img src="https://img.icons8.com/color/32/visa.png" alt="Visa" title="Visa" style="height:24px">
                            <img src="https://img.icons8.com/color/32/mastercard.png" alt="Mastercard" title="Mastercard" style="height:24px">
                            <span style="font-size:.8rem;color:#94a3b8;margin-left:4px">+ Mobile Money (M-Pesa, Airtel, Orange)</span>
                        </div>

                        <!-- Bouton paiement -->
                        <button id="btnPay" class="btn-pay" onclick="startPayment()">
                            <i class="bi bi-lock-fill me-2"></i>
                            Payer <span id="btnAmount">€<?= number_format($amount, 2) ?></span> en toute sécurité
                        </button>

                        <div class="text-center mt-3">
                            <div class="security-badge justify-content-center">
                                <i class="bi bi-shield-check-fill text-success"></i>
                                Sécurisé par Flutterwave &nbsp;·&nbsp; SSL 256-bit
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="/register" class="text-muted" style="font-size:.85rem;text-decoration:none">
                                <i class="bi bi-arrow-left me-1"></i>Retour à l'inscription
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
const PLANS = {
    monthly: { amount: <?= $plan['mensuel'] ?>, label: 'Mensuel', renew: 'Dans 1 mois' },
    annual:  { amount: <?= $plan['annuel'] ?>,  label: 'Annuel',  renew: 'Dans 1 an'  },
};

let currentCycle = '<?= $cycle ?>';

function selectCycle(cycle) {
    currentCycle = cycle;

    document.querySelectorAll('.cycle-btn').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');

    const p = PLANS[cycle];
    const fmt = n => '€' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    document.getElementById('summaryAmount').textContent = fmt(p.amount);
    document.getElementById('summaryCycle').textContent  = p.label;
    document.getElementById('summaryRenew').textContent  = p.renew;
    document.getElementById('summaryTotal').textContent  = fmt(p.amount);
    document.getElementById('btnAmount').textContent     = fmt(p.amount);

    // Mettre à jour côté serveur
    fetch('/payment/set-cycle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'cycle=' + cycle + '&<?= csrf_token() ?>=' + document.querySelector('meta[name="csrf-token"]')?.content,
    });
}

function startPayment() {
    const btn = document.getElementById('btnPay');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Chargement...';

    const p = PLANS[currentCycle];

    FlutterwaveCheckout({
        public_key: '<?= esc($public_key) ?>',
        tx_ref:     '<?= esc($payment['tx_ref']) ?>',
        amount:     p.amount,
        currency:   'EUR',
        payment_options: 'card,mobilemoney',
        customer: {
            email: '<?= esc($email) ?>',
            name:  '<?= esc($tenant_nom) ?>',
        },
        customizations: {
            title:       'IkelyaneMed',
            description: 'Abonnement <?= esc($plan['nom']) ?> — ' + (currentCycle === 'annual' ? 'Annuel' : 'Mensuel'),
            logo:        'https://ikelyanemed.com/logo.png',
        },
        callback: function(data) {
            if (data.status === 'successful' || data.status === 'completed') {
                window.location.href = '/payment/verify?transaction_id=' + data.transaction_id + '&tx_ref=' + data.tx_ref;
            } else {
                window.location.href = '/payment/failed?reason=cancelled';
            }
        },
        onclose: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-lock-fill me-2"></i>Payer <span id="btnAmount">€' + p.amount.toFixed(2) + '</span> en toute sécurité';
        },
    });
}
</script>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
</body>
</html>
