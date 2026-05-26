<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<div class="card auth-card p-4 p-md-5">
    <div class="text-center mb-4">
        <div class="auth-brand">Ikelya<span>neMed</span></div>
        <p class="text-muted mt-2"><?= lang('Auth.reset_desc') ?></p>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 rounded-3"><i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/reset-password" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= esc($token) ?>">

        <div class="mb-3">
            <label class="form-label fw-600"><?= lang('Auth.new_password') ?></label>
            <input type="password" name="password" class="form-control" placeholder="<?= lang('Auth.pwd_min8') ?>" required minlength="8">
        </div>
        <div class="mb-4">
            <label class="form-label fw-600"><?= lang('Auth.confirm_password') ?></label>
            <input type="password" name="password_confirm" class="form-control" placeholder="<?= lang('Auth.pwd_repeat') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary btn-login w-100 text-white">
            <i class="bi bi-check-circle me-2"></i><?= lang('Auth.reset_confirm_btn') ?>
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="/login" class="text-muted" style="font-size:.9rem"><i class="bi bi-arrow-left me-1"></i><?= lang('Auth.back_login') ?></a>
    </div>
</div>
<?= $this->endSection() ?>
