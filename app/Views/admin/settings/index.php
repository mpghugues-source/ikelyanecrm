<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-gear text-primary me-2"></i><?= lang('Admin.settings_title') ?></h4>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-building me-2 text-primary"></i><?= lang('Admin.org_info') ?></h6></div>
            <div class="card-body">
                <form action="/admin/settings/save" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label"><?= lang('Admin.org_name') ?></label>
                            <input type="text" name="nom" class="form-control" value="<?= esc($tenant['nom'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?= lang('SuperAdmin.address') ?></label>
                            <textarea name="adresse" class="form-control" rows="2"><?= esc($tenant['adresse'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.phone') ?></label>
                            <input type="text" name="telephone" class="form-control" value="<?= esc($tenant['telephone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Common.email') ?></label>
                            <input type="email" name="email" class="form-control" value="<?= esc($tenant['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('SuperAdmin.city') ?></label>
                            <input type="text" name="ville" class="form-control" value="<?= esc($tenant['ville'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('Admin.primary_color') ?></label>
                            <div class="input-group">
                                <input type="color" name="couleur" id="colorPicker" class="form-control form-control-color" value="<?= esc($tenant['couleur'] ?? '#0d6efd') ?>">
                                <input type="text" id="colorText" class="form-control" value="<?= esc($tenant['couleur'] ?? '#0d6efd') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i><?= lang('Admin.save_settings') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0"><i class="bi bi-receipt me-2 text-primary"></i><?= lang('Admin.services_title') ?></h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#addServiceForm">
                    <i class="bi bi-plus-lg me-1"></i><?= lang('Common.add') ?>
                </button>
            </div>
            <div class="collapse" id="addServiceForm">
                <div class="card-body border-bottom bg-light">
                    <form action="/admin/settings/services/add" method="POST" class="row g-2">
                        <?= csrf_field() ?>
                        <div class="col-7">
                            <input type="text" name="nom" class="form-control form-control-sm" placeholder="<?= lang('Admin.service_name') ?>" required>
                        </div>
                        <div class="col-3">
                            <input type="number" name="prix" class="form-control form-control-sm" placeholder="<?= lang('Admin.service_price') ?> (DA)" min="0" step="50" required>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-success btn-sm w-100">OK</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th><?= lang('Admin.service_name') ?></th><th>Code</th><th><?= lang('Admin.service_price') ?></th><th></th></tr></thead>
                        <tbody>
                            <?php foreach ($services as $s): ?>
                            <tr>
                                <td><?= esc($s['nom']) ?></td>
                                <td><code><?= esc($s['code'] ?? '—') ?></code></td>
                                <td><?= number_format($s['prix']) ?> DA</td>
                                <td class="text-end">
                                    <a href="/admin/settings/services/delete/<?= $s['id'] ?>"
                                       class="btn btn-outline-danger btn-sm"
                                       data-confirm="<?= lang('Admin.delete_service') ?>">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="card-title"><i class="bi bi-award me-2 text-primary"></i><?= lang('Admin.specialties_title') ?></h6></div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($specialites as $s): ?>
                    <span class="badge bg-light text-dark border"><?= esc($s['nom']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const picker = document.getElementById('colorPicker');
const text   = document.getElementById('colorText');
picker.addEventListener('input', () => { text.value = picker.value; });
</script>
<?= $this->endSection() ?>
