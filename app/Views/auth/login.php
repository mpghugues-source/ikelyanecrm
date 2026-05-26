<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-card p-4 mt-4">
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-3 p-3 mb-3">
            <i class="bi bi-diagram-3-fill text-white fs-2"></i>
        </div>
        <div class="auth-brand"><span>Ikelyane</span>CRM</div>
        <p class="text-muted small mt-1"><?= lang('Auth.platform') ?></p>
    </div>

    <!-- Language switcher on login page -->
    <?php $locale = session()->get('locale') ?? 'fr'; ?>
    <div class="text-end mb-3">
        <a href="<?= base_url('lang/fr') ?>" class="btn btn-sm <?= $locale === 'fr' ? 'btn-primary' : 'btn-outline-secondary' ?> me-1">
            🇫🇷 FR
        </a>
        <a href="<?= base_url('lang/en') ?>" class="btn btn-sm <?= $locale === 'en' ? 'btn-primary' : 'btn-outline-secondary' ?>">
            🇬🇧 EN
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger py-2 small">
        <i class="bi bi-exclamation-triangle me-1"></i>
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
    <?php endif; ?>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
    <div class="alert alert-warning py-2 small">
        <?php foreach ((array)$errors as $e): ?>
        <div><i class="bi bi-dot"></i><?= esc($e) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success py-2 small">
        <i class="bi bi-check-circle me-1"></i>
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold small"><?= lang('Auth.email') ?></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control border-start-0 ps-0"
                       placeholder="<?= lang('Auth.email_placeholder') ?>"
                       value="<?= esc(old('email')) ?>" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold small"><?= lang('Auth.password') ?></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" name="password" id="passwordInput" class="form-control border-start-0 ps-0"
                       placeholder="••••••••" required>
                <button type="button" class="input-group-text bg-light border-start-0"
                        onclick="togglePassword()">
                    <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-login w-100 text-white mb-3">
            <i class="bi bi-box-arrow-in-right me-2"></i><?= lang('Auth.login_btn') ?>
        </button>
        <div class="text-center">
            <a href="/forgot-password" class="text-muted small text-decoration-none">
                <i class="bi bi-question-circle me-1"></i><?= lang('Auth.forgot_password') ?>
            </a>
        </div>
    </form>
</div>

<p class="text-center text-white small mt-3 opacity-75">
    &copy; <?= date('Y') ?> IkelyaneCRM — <?= lang('Auth.copyright') ?>
</p>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
<?= $this->endSection() ?>
