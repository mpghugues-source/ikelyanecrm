<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="card auth-card p-4 mt-4">
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-warning rounded-3 p-3 mb-3">
            <i class="bi bi-key-fill text-white fs-2"></i>
        </div>
        <h5 class="fw-bold"><?= lang('Auth.forgot_title') ?></h5>
        <p class="text-muted small"><?= lang('Auth.forgot_desc') ?></p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success py-2 small"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <form action="/forgot-password" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold small"><?= lang('Auth.email') ?></label>
            <input type="email" name="email" class="form-control" placeholder="<?= lang('Auth.email_placeholder') ?>" required autofocus>
        </div>
        <button type="submit" class="btn btn-warning w-100 text-white fw-semibold mb-3">
            <i class="bi bi-envelope me-2"></i><?= lang('Auth.send_link') ?>
        </button>
        <div class="text-center">
            <a href="/login" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i><?= lang('Auth.back_login') ?>
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
