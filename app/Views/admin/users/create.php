<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-person-plus text-primary me-2"></i><?= lang('Admin.new_user') ?></h4>
    <a href="/admin/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?></a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="/admin/users/store" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.name') ?> *</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.firstname') ?> *</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?= lang('Common.email') ?> *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Admin.role_label') ?> *</label>
                            <select name="role" class="form-select" required>
                                <option value="admin"><?= lang('Admin.role_admin') ?></option>
                                <option value="manager"><?= lang('Admin.role_manager') ?></option>
                                <option value="commercial"><?= lang('Admin.role_commercial') ?></option>
                                <option value="support"><?= lang('Admin.role_support') ?></option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?= lang('Auth.password') ?> *</label>
                            <input type="password" name="password" class="form-control" required minlength="8" placeholder="<?= lang('Auth.pwd_min8') ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-person-check me-1"></i><?= lang('Admin.create_user') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
