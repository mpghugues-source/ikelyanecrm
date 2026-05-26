<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i><?= esc($title) ?></h1>
    <a href="/messages" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?>
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $e): ?>
            <li><?= esc($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/messages/store">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold"><?= lang('Common.recipient') ?> <span class="text-danger">*</span></label>
                <select name="to_user_id" class="form-select" required>
                    <option value=""><?= lang('Common.choose_recipient') ?></option>
                    <?php
                    $roleLabels = [
                        'admin'      => lang('Admin.role_admin'),
                        'manager'    => lang('Admin.role_manager'),
                        'commercial' => lang('Admin.role_commercial'),
                        'support'    => lang('Admin.role_support'),
                    ];
                    $grouped = [];
                    foreach ($destinataires as $d) {
                        $grouped[$d['role']][] = $d;
                    }
                    foreach ($grouped as $role => $users):
                    ?>
                        <optgroup label="<?= $roleLabels[$role] ?? ucfirst($role) ?>">
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>"
                                    <?= (old('to_user_id', $to_user_id) == $u['id']) ? 'selected' : '' ?>>
                                    <?= esc($u['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold"><?= lang('Common.subject') ?> <span class="text-danger">*</span></label>
                <input type="text" name="sujet" class="form-control" required maxlength="255"
                    value="<?= esc(old('sujet', $sujet)) ?>">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold"><?= lang('Common.message') ?> <span class="text-danger">*</span></label>
                <textarea name="message" class="form-control" rows="8" required
                    placeholder="<?= lang('Common.write_message') ?>"><?= esc(old('message')) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i><?= lang('Common.send') ?>
                </button>
                <a href="/messages" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
