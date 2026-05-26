<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-people text-primary me-2"></i><?= lang('Admin.user_management') ?></h4>
    <a href="/admin/users/create" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i><?= lang('Admin.new_user') ?></a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th><?= lang('Admin.user_col') ?></th>
                        <th>Email</th>
                        <th><?= lang('Admin.role_label') ?></th>
                        <th><?= lang('Admin.phone') ?></th>
                        <th><?= lang('Common.status') ?></th>
                        <th><?= lang('Admin.last_login_col') ?></th>
                        <th class="text-end"><?= lang('Common.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm"><?= strtoupper(substr($u['prenom'],0,1).substr($u['nom'],0,1)) ?></div>
                                <span class="fw-semibold"><?= esc($u['prenom'] . ' ' . $u['nom']) ?></span>
                            </div>
                        </td>
                        <td><?= esc($u['email']) ?></td>
                        <td>
                            <?php
                            $roleKey = 'role_' . $u['role'];
                            $roleLabel = lang('Admin.' . $roleKey) ?: ucfirst($u['role']);
                            $roleColor = match($u['role']) {
                                'admin'      => 'danger',
                                'manager'    => 'primary',
                                'commercial' => 'warning',
                                'support'    => 'info',
                                default      => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?= $roleColor ?>"><?= esc($roleLabel) ?></span>
                        </td>
                        <td><?= esc($u['telephone'] ?? '—') ?></td>
                        <td>
                            <?php if ($u['actif']): ?>
                            <span class="badge bg-success-subtle text-success"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : lang('Admin.never_logged') ?>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/users/edit/<?= $u['id'] ?>" class="btn btn-outline-secondary" title="<?= lang('Common.edit') ?>"><i class="bi bi-pencil"></i></a>
                                <?php if ($u['id'] !== session()->get('user_id')): ?>
                                <a href="/admin/users/delete/<?= $u['id'] ?>" class="btn btn-outline-danger" title="<?= lang('Admin.deactivate_user') ?>" onclick="return confirm('<?= lang('Admin.deactivate_user') ?>')"><i class="bi bi-person-dash"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($users)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4"><?= lang('Common.no_results') ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
