<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-credit-card text-primary me-2"></i>Mon abonnement</h4>
</div>

<?php
$plan     = $tenant['plan'] ?? 'starter';
$isPaid   = in_array($plan, ['pro', 'enterprise']);
$expireTs = $tenant['expire_le'] ? strtotime($tenant['expire_le']) : null;
$expired  = $expireTs && $expireTs < time();
?>

<div class="row g-4">

    <!-- ── Statut actuel ─────────────────────────────────────── -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden">
            <div class="card-header border-0 py-3 px-4" style="background:linear-gradient(135deg,<?= esc($currentPlan['color']) ?>18,<?= esc($currentPlan['color']) ?>08)">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:<?= esc($currentPlan['color']) ?>">Plan actuel</span>
                        <h5 class="mb-0 mt-1 fw-800"><?= esc($currentPlan['nom']) ?></h5>
                    </div>
                    <?php if ($expired): ?>
                    <span class="badge bg-danger">Expiré</span>
                    <?php elseif ($daysLeft !== null && $daysLeft <= 7): ?>
                    <span class="badge bg-warning text-dark">Expire dans <?= $daysLeft ?> jour<?= $daysLeft > 1 ? 's' : '' ?></span>
                    <?php elseif ($isPaid): ?>
                    <span class="badge bg-success">Actif</span>
                    <?php else: ?>
                    <span class="badge bg-secondary">Gratuit</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body px-4 py-3">
                <div class="row g-3">
                    <!-- Infos expiration -->
                    <div class="col-md-4">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">
                            <?= $isPaid ? 'Expire le' : 'Validité' ?>
                        </div>
                        <div class="fw-700 mt-1">
                            <?php if ($tenant['expire_le']): ?>
                            <?= date('d/m/Y', strtotime($tenant['expire_le'])) ?>
                            <?php else: ?>
                            Sans limite
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Médecins -->
                    <div class="col-md-4">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Médecins</div>
                        <div class="fw-700 mt-1">
                            <?= $doctorUsage['current'] ?> / <?= $doctorUsage['max'] === 0 ? '∞' : $doctorUsage['max'] ?>
                        </div>
                        <?php if ($doctorUsage['max'] > 0): $pct = min(100, round($doctorUsage['current'] / $doctorUsage['max'] * 100)); ?>
                        <div class="mt-1" style="height:5px;background:#e2e8f0;border-radius:3px">
                            <div style="height:100%;width:<?= $pct ?>%;background:<?= $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : $currentPlan['color']) ?>;border-radius:3px"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!-- Patients -->
                    <div class="col-md-4">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Patients</div>
                        <div class="fw-700 mt-1">
                            <?= $patientUsage['current'] ?> / <?= $patientUsage['max'] === 0 ? '∞' : $patientUsage['max'] ?>
                        </div>
                        <?php if ($patientUsage['max'] > 0): $pct = min(100, round($patientUsage['current'] / $patientUsage['max'] * 100)); ?>
                        <div class="mt-1" style="height:5px;background:#e2e8f0;border-radius:3px">
                            <div style="height:100%;width:<?= $pct ?>%;background:<?= $pct >= 100 ? '#ef4444' : ($pct >= 80 ? '#f59e0b' : $currentPlan['color']) ?>;border-radius:3px"></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Fonctionnalités incluses -->
                <div class="mt-3 pt-3" style="border-top:1px solid #f1f5f9">
                    <div class="text-muted mb-2" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Inclus dans votre plan</div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($currentPlan['features'] as $f): ?>
                        <span class="badge" style="background:<?= esc($currentPlan['color']) ?>18;color:<?= esc($currentPlan['color']) ?>;font-size:.8rem;font-weight:600;padding:5px 10px;border-radius:6px">
                            <i class="bi bi-check me-1"></i><?= esc($f) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Historique des paiements ──────────────────────── -->
        <div class="card border-0 shadow-sm mt-4" style="border-radius:16px">
            <div class="card-header border-0 py-3 px-4 bg-white">
                <h6 class="mb-0 fw-700"><i class="bi bi-clock-history me-2 text-primary"></i>Historique des paiements</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($payments)): ?>
                <div class="text-center text-muted py-5" style="font-size:.9rem">
                    <i class="bi bi-receipt" style="font-size:2rem;display:block;margin-bottom:8px;opacity:.4"></i>
                    <?= lang('Admin.no_payments') ?>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.9rem">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th class="px-4 py-3 fw-600" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Date</th>
                                <th class="py-3 fw-600" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Plan</th>
                                <th class="py-3 fw-600" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Cycle</th>
                                <th class="py-3 fw-600" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Montant</th>
                                <th class="py-3 fw-600" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Statut</th>
                                <th class="py-3 fw-600" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px">Réf.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $p): ?>
                            <tr>
                                <td class="px-4 py-3 text-muted"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                                <td class="py-3 fw-600"><?= ucfirst(esc($p['plan_slug'])) ?></td>
                                <td class="py-3 text-muted"><?= $p['billing_cycle'] === 'annual' ? 'Annuel' : 'Mensuel' ?></td>
                                <td class="py-3 fw-700">€<?= number_format($p['amount'], 2) ?></td>
                                <td class="py-3">
                                    <?php
                                    $badges = [
                                        'paid'      => ['bg-success',  'Payé'],
                                        'pending'   => ['bg-warning text-dark', 'En attente'],
                                        'failed'    => ['bg-danger',   'Échoué'],
                                        'cancelled' => ['bg-secondary','Annulé'],
                                    ];
                                    [$cls, $lbl] = $badges[$p['status']] ?? ['bg-secondary', $p['status']];
                                    ?>
                                    <span class="badge <?= $cls ?>"><?= $lbl ?></span>
                                </td>
                                <td class="py-3 text-muted" style="font-size:.8rem;font-family:monospace">
                                    <?= $p['flw_transaction_id'] ? esc(substr($p['flw_transaction_id'], 0, 12)) . '…' : '—' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── Panneau renouvellement / upgrade ─────────────────── -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:16px">
            <div class="card-body p-4">
                <h6 class="fw-700 mb-4">
                    <?= $expired ? 'Renouveler votre abonnement' : ($plan === 'starter' ? 'Passer à un plan payant' : 'Renouveler / Changer de plan') ?>
                </h6>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger rounded-3 mb-3 py-2" style="font-size:.85rem"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <!-- Toggle cycle -->
                <div class="d-flex gap-2 mb-4 p-1 rounded-3" style="background:#f1f5f9">
                    <button class="cycle-btn active flex-fill py-2 rounded-2 fw-600" style="font-size:.85rem" onclick="setCycle('monthly', this)">Mensuel</button>
                    <button class="cycle-btn flex-fill py-2 rounded-2 fw-600" style="font-size:.85rem" onclick="setCycle('annual', this)">
                        Annuel <span style="font-size:.7rem;background:#dcfce7;color:#15803d;padding:1px 6px;border-radius:50px;margin-left:3px">-17%</span>
                    </button>
                </div>

                <form action="/abonnement/renouveler" method="POST" id="renewForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="cycle" id="cycleInput" value="monthly">
                    <input type="hidden" name="plan"  id="planInput"  value="<?= $plan === 'starter' ? 'pro' : esc($plan) ?>">

                    <!-- Plans -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <?php foreach (['pro', 'enterprise'] as $slug):
                            $p         = $plans[$slug] ?? ['nom' => ucfirst($slug), 'color' => '#1a56db', 'mensuel' => 0, 'annuel' => 0, 'features' => []];
                            $isCurrent = ($plan === $slug);
                        ?>
                        <div class="plan-option <?= $isCurrent ? 'selected' : '' ?>"
                             onclick="selectPlan('<?= $slug ?>', this)"
                             style="border:2px solid <?= $isCurrent ? $p['color'] : '#e2e8f0' ?>;border-radius:12px;padding:14px 16px;cursor:pointer;transition:all .2s;background:<?= $isCurrent ? $p['color'] . '08' : '#fff' ?>">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div style="font-weight:700;font-size:.95rem;color:<?= $p['color'] ?>"><?= $p['nom'] ?></div>
                                    <div style="font-size:.8rem;color:#64748b;margin-top:2px">
                                        <?= $slug === 'pro' ? lang('Admin.plan_users_contacts') : lang('Admin.plan_unlimited') ?>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-800" style="font-size:1.2rem;color:#0f172a">
                                        <span class="price-display" data-monthly="€<?= $p['mensuel'] ?>" data-annual="€<?= $p['annuel'] ?>">€<?= $p['mensuel'] ?></span>
                                    </div>
                                    <div style="font-size:.75rem;color:#94a3b8" class="period-label">/mois</div>
                                </div>
                            </div>
                            <?php if ($isCurrent && !$expired): ?>
                            <div style="font-size:.75rem;color:<?= $p['color'] ?>;margin-top:6px;font-weight:600">
                                <i class="bi bi-check-circle-fill me-1"></i>Plan actuel
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn w-100 py-3 fw-700 rounded-3"
                            style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;border:none;font-size:1rem">
                        <i class="bi bi-credit-card me-2"></i>
                        <span id="btnText">
                            <?= $expired ? 'Renouveler maintenant' : ($plan === 'starter' ? 'Passer au plan payant' : 'Renouveler maintenant') ?>
                        </span>
                    </button>

                    <div class="text-center mt-3" style="font-size:.8rem;color:#94a3b8">
                        <i class="bi bi-shield-lock me-1"></i>Paiement sécurisé · Annulable à tout moment
                    </div>
                </form>
            </div>
        </div>

        <!-- Aide -->
        <div class="card border-0 shadow-sm mt-3" style="border-radius:16px;background:linear-gradient(135deg,#eff6ff,#f5f3ff)">
            <div class="card-body p-4">
                <div style="font-weight:700;font-size:.95rem;margin-bottom:8px">
                    <i class="bi bi-headset me-2 text-primary"></i>Besoin d'aide ?
                </div>
                <p style="font-size:.85rem;color:#64748b;margin:0">
                    Notre équipe est disponible pour vous accompagner dans votre abonnement.
                </p>
                <a href="mailto:support@ikelyanemed.com" class="btn btn-sm mt-3 fw-600"
                   style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;font-size:.85rem">
                    <i class="bi bi-envelope me-1"></i>support@ikelyanemed.com
                </a>
            </div>
        </div>
    </div>

</div>

<?= $this->section('styles') ?>
<style>
.cycle-btn { border: none; background: transparent; cursor: pointer; }
.cycle-btn.active { background: #fff; color: #1a56db; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const planColors = { pro: '#1a56db', enterprise: '#7c3aed' };
let currentCycle = 'monthly';

function setCycle(cycle, btn) {
    currentCycle = cycle;
    document.getElementById('cycleInput').value = cycle;

    document.querySelectorAll('.cycle-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const period = cycle === 'annual' ? '/an' : '/mois';
    document.querySelectorAll('.period-label').forEach(el => el.textContent = period);
    document.querySelectorAll('.price-display').forEach(el => {
        el.textContent = cycle === 'annual' ? el.dataset.annual : el.dataset.monthly;
    });
}

function selectPlan(slug, card) {
    document.getElementById('planInput').value = slug;

    document.querySelectorAll('.plan-option').forEach(c => {
        c.style.borderColor = '#e2e8f0';
        c.style.background  = '#fff';
    });

    const color = planColors[slug] || '#1a56db';
    card.style.borderColor = color;
    card.style.background  = color + '08';
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
