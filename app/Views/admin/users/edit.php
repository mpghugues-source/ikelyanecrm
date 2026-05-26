<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-pencil text-primary me-2"></i><?= lang('Admin.edit_user') ?></h4>
    <a href="/admin/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?></a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="/admin/users/update/<?= $user['id'] ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.name') ?> *</label>
                            <input type="text" name="nom" class="form-control" value="<?= esc($user['nom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.firstname') ?> *</label>
                            <input type="text" name="prenom" class="form-control" value="<?= esc($user['prenom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.phone') ?></label>
                            <input type="tel" name="telephone" class="form-control" value="<?= esc($user['telephone']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Admin.role_label') ?></label>
                            <select name="role" class="form-select">
                                <?php
                                $roles = [
                                    'admin'      => lang('Admin.role_admin'),
                                    'manager'    => lang('Admin.role_manager'),
                                    'commercial' => lang('Admin.role_commercial'),
                                    'support'    => lang('Admin.role_support'),
                                ];
                                foreach ($roles as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $user['role']===$val?'selected':'' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.status') ?></label>
                            <select name="actif" class="form-select">
                                <option value="1" <?= $user['actif']?'selected':'' ?>><?= lang('Common.active') ?></option>
                                <option value="0" <?= !$user['actif']?'selected':'' ?>><?= lang('Common.inactive') ?></option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?= lang('Common.new_password') ?> <small class="text-muted">(<?= lang('Common.leave_empty') ?>)</small></label>
                            <input type="password" name="password" class="form-control" minlength="8">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save me-1"></i><?= lang('Common.save') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
