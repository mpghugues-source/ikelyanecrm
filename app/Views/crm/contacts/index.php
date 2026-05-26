<?php $this->extend('layouts/main'); ?>
<?php $this->section('title'); ?><?= lang('Crm.contacts') ?><?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold"><i class="bi bi-people-fill text-primary me-2"></i><?= lang('Crm.contacts') ?></h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="/crm">CRM</a></li><li class="breadcrumb-item active"><?= lang('Crm.contacts') ?></li></ol></nav>
    </div>
    <a href="/crm/contacts/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i><?= lang('Crm.new_contact') ?></a>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-center">
            <div class="col-md-5"><input type="text" id="searchInput" class="form-control form-control-sm" placeholder="<?= lang('Crm.search') ?>..."></div>
            <div class="col-md-3">
                <select id="typeFilter" class="form-select form-select-sm">
                    <option value=""><?= lang('Crm.all') ?></option>
                    <option value="lead"><?= lang('Crm.type_lead') ?></option>
                    <option value="contact"><?= lang('Crm.type_contact') ?></option>
                    <option value="patient"><?= lang('Crm.type_patient') ?></option>
                    <option value="supplier"><?= lang('Crm.type_supplier') ?></option>
                    <option value="partner"><?= lang('Crm.type_partner') ?></option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="contactsTable">
                <thead class="table-light">
                    <tr>
                        <th><?= lang('Crm.last_name') ?> / <?= lang('Crm.first_name') ?></th>
                        <th><?= lang('Crm.contact_type') ?></th>
                        <th><?= lang('Crm.company') ?></th>
                        <th>Email</th>
                        <th><?= lang('Crm.notes') ?></th>
                        <th><?= lang('Crm.status') ?></th>
                        <th width="100"><?= lang('Crm.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contacts)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted"><?= lang('Crm.no_records') ?></td></tr>
                    <?php else: foreach ($contacts as $c):
                        $typeColors = ['lead'=>'warning','contact'=>'primary','patient'=>'info','supplier'=>'secondary','partner'=>'success'];
                        $tc = $typeColors[$c['type']] ?? 'secondary';
                    ?>
                    <tr class="contact-row" data-type="<?= $c['type'] ?>">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-<?= $tc ?>-subtle text-<?= $tc ?> rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;min-width:36px;font-size:.85rem">
                                    <?= strtoupper(substr($c['prenom']??'',0,1).substr($c['nom'],0,1)) ?>
                                </div>
                                <div>
                                    <a href="/crm/contacts/<?= $c['id'] ?>/view" class="fw-semibold text-decoration-none"><?= esc($c['prenom'].' '.$c['nom']) ?></a>
                                    <?php if ($c['poste']): ?><br><small class="text-muted"><?= esc($c['poste']) ?></small><?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-<?= $tc ?>-subtle text-<?= $tc ?>"><?= lang('Crm.type_'.$c['type']) ?></span></td>
                        <td><?= esc($c['entreprise'] ?? '—') ?></td>
                        <td><?= $c['email'] ? '<a href="mailto:'.esc($c['email']).'">'.esc($c['email']).'</a>' : '—' ?></td>
                        <td><span class="badge bg-light text-dark border me-1"><i class="bi bi-star-fill text-warning"></i> <?= $c['nb_leads'] ?? 0 ?></span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-ticket text-danger"></i> <?= $c['nb_cases'] ?? 0 ?></span></td>
                        <td><span class="badge bg-<?= $c['statut'] === 'active' ? 'success' : 'secondary' ?>"><?= lang('Crm.status_'.$c['statut']) ?></span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/crm/contacts/<?= $c['id'] ?>/edit" class="btn btn-outline-primary" title="<?= lang('Crm.edit') ?>"><i class="bi bi-pencil"></i></a>
                                <a href="/crm/contacts/<?= $c['id'] ?>/delete" class="btn btn-outline-danger" onclick="return confirm('<?= lang('Crm.confirm_delete') ?>')" title="<?= lang('Crm.delete') ?>"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('typeFilter').addEventListener('change', filterTable);
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const t = document.getElementById('typeFilter').value;
    document.querySelectorAll('.contact-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        const type = row.dataset.type;
        row.style.display = (text.includes(q) && (!t || type === t)) ? '' : 'none';
    });
}
</script>
<?php $this->endSection(); ?>
