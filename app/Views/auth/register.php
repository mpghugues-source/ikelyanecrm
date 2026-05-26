<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? lang('Auth.register_title') . ' — IkelyaneMed') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); min-height: 100vh; display: flex; align-items: center; }
        .register-card { background: #fff; border-radius: 24px; box-shadow: 0 30px 80px rgba(0,0,0,0.3); border: none; overflow: hidden; }
        .register-left { background: linear-gradient(135deg, #1a56db, #7c3aed); padding: 48px 40px; display: flex; flex-direction: column; justify-content: center; }
        .register-right { padding: 48px 40px; }
        .brand { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .feature-item { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.85); margin-bottom: 16px; font-size: .95rem; }
        .feature-item i { font-size: 1.1rem; color: #93c5fd; flex-shrink: 0; }
        .form-label { font-weight: 600; font-size: .9rem; color: #374151; }
        .form-control, .form-select { border-radius: 10px; border: 2px solid #e2e8f0; padding: 10px 14px; font-size: .95rem; transition: border-color .2s; }
        .form-control:focus, .form-select:focus { border-color: #1a56db; box-shadow: 0 0 0 3px rgba(26,86,219,0.1); }
        .btn-register { background: linear-gradient(135deg, #1a56db, #7c3aed); border: none; border-radius: 12px; padding: 13px; font-size: 1rem; font-weight: 700; color: #fff; width: 100%; transition: all .3s; }
        .btn-register:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 10px 30px rgba(26,86,219,.4); color: #fff; }
        .plan-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff; padding: 6px 14px; border-radius: 50px; font-size: .8rem; font-weight: 600; margin-bottom: 24px; }
        .step-indicator { display: flex; gap: 8px; margin-bottom: 28px; }
        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: #e2e8f0; }
        .step-dot.active { background: #1a56db; width: 24px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="register-card">
                <div class="row g-0">
                    <!-- LEFT -->
                    <div class="col-lg-5 register-left">
                        <a href="/" class="text-white text-decoration-none">
                            <div class="brand">IkelyaneMed</div>
                        </a>
                        <p style="color:rgba(255,255,255,0.7);margin-bottom:36px"><?= lang('Auth.clinic_subtitle') ?></p>

                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><?= lang('Auth.trial_free') ?></div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><?= lang('Auth.setup_5min') ?></div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><?= lang('Auth.no_card') ?></div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><?= lang('Auth.support_247') ?></div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><?= lang('Auth.data_hosted') ?></div>
                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><?= lang('Auth.cancel_anytime') ?></div>

                        <div class="mt-auto pt-4" style="border-top:1px solid rgba(255,255,255,0.2);margin-top:40px">
                            <div style="color:rgba(255,255,255,0.6);font-size:.85rem"><?= lang('Auth.already_account') ?></div>
                            <a href="/login" style="color:#93c5fd;font-weight:600;text-decoration:none"><?= lang('Auth.login_btn') ?> →</a>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-7 register-right">
                        <div class="step-indicator">
                            <div class="step-dot active"></div>
                            <div class="step-dot"></div>
                            <div class="step-dot"></div>
                        </div>

                        <h2 style="font-size:1.7rem;font-weight:800;color:#0f172a;margin-bottom:6px"><?= lang('Auth.create_clinic') ?></h2>
                        <p class="text-muted mb-4" style="font-size:.95rem"><?= lang('Auth.register_subtitle') ?></p>

                        <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger border-0 rounded-3"><i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger border-0 rounded-3">
                            <?php foreach(session()->getFlashdata('errors') as $err): ?>
                            <div><i class="bi bi-x-circle me-1"></i><?= esc($err) ?></div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <form action="/register" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="plan" value="<?= esc($plan ?? 'gratuit') ?>">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label"><?= lang('Auth.clinic_name') ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-building text-muted"></i></span>
                                        <input type="text" name="clinic_name" class="form-control border-start-0 ps-0"
                                               placeholder="Ex: Clinique Médicale El Amine"
                                               value="<?= old('clinic_name') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><?= lang('Auth.professional_email') ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-0"
                                               placeholder="<?= lang('Auth.email_placeholder') ?>"
                                               value="<?= old('email') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><?= lang('Common.phone') ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-phone text-muted"></i></span>
                                        <input type="tel" name="phone" class="form-control border-start-0 ps-0"
                                               placeholder="05 00 00 00 00"
                                               value="<?= old('phone') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pays <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-globe text-muted"></i></span>
                                        <input type="text" name="pays" class="form-control border-start-0 ps-0"
                                               placeholder="Ex: RD Congo, France, Maroc..."
                                               value="<?= old('pays') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ville <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                        <input type="text" name="ville" class="form-control border-start-0 ps-0"
                                               placeholder="Ex: Kinshasa, Paris, Casablanca..."
                                               value="<?= old('ville') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><?= lang('Auth.password') ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                        <input type="password" name="password" id="password" class="form-control border-start-0 ps-0 border-end-0"
                                               placeholder="<?= lang('Auth.pwd_min8') ?>" required minlength="8">
                                        <span class="input-group-text bg-white border-start-0" style="cursor:pointer" onclick="togglePwd()">
                                            <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                                        </span>
                                    </div>
                                    <div class="mt-1" id="pwdStrength"></div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="bi bi-box-seam text-primary"></i>
                                            <span style="font-weight:600;font-size:.9rem"><?= lang('Auth.plan_selected') ?>
                                                <span class="text-primary"><?= ucfirst(esc($plan ?? 'gratuit')) ?></span>
                                            </span>
                                        </div>
                                        <div class="text-muted" style="font-size:.8rem"><?= lang('Auth.trial_included') ?></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                        <label class="form-check-label text-muted" for="terms" style="font-size:.9rem">
                                            <?= lang('Common.i_accept') ?> <a href="#" class="text-primary"><?= lang('Auth.terms_link') ?></a> <?= lang('Common.and') ?> <a href="#" class="text-primary"><?= lang('Auth.privacy_link') ?></a>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-register">
                                        <i class="bi bi-rocket-takeoff me-2"></i><?= lang('Auth.create_free') ?>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <div class="d-flex align-items-center gap-3 justify-content-center text-muted" style="font-size:.8rem">
                                <span><i class="bi bi-shield-check text-success me-1"></i><?= lang('Auth.data_secure') ?></span>
                                <span><i class="bi bi-lock text-success me-1"></i><?= lang('Auth.ssl_encrypt') ?></span>
                                <span><i class="bi bi-award text-success me-1"></i><?= lang('Auth.gdpr') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const pwdLabels = ['','<?= lang('Auth.pwd_weak') ?>','<?= lang('Auth.pwd_medium') ?>','<?= lang('Auth.pwd_strong') ?>','<?= lang('Auth.pwd_very_strong') ?>'];
const pwdColors = ['','#ef4444','#f59e0b','#10b981','#059669'];
function togglePwd() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'bi bi-eye-slash text-muted'; }
    else { pwd.type = 'password'; icon.className = 'bi bi-eye text-muted'; }
}
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const el = document.getElementById('pwdStrength');
    let strength = 0;
    if (val.length >= 8) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;
    el.innerHTML = val.length > 0 ? `<small style="color:${pwdColors[strength]}">${pwdLabels[strength]}</small>` : '';
});
</script>
</body>
</html>
