<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-people text-primary me-2"></i>Utilisateurs</h4>
    <a href="/admin/users/create" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Nouvel utilisateur</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Utilisateur</th><th>Email</th><th>Rôle</th><th>Téléphone</th><th>Statut</th><th>Dernière connexion</th><th class="text-end">Actions</th></tr>
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
                            <span class="badge bg-<?= match($u['role']) { 'admin'=>'danger','medecin'=>'primary','secretaire'=>'warning','patient'=>'success',default=>'secondary' } ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td><?= esc($u['telephone'] ?? '—') ?></td>
                        <td>
                            <?php if ($u['actif']): ?>
                            <span class="badge bg-success-subtle text-success">Actif</span>
                            <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= $u['derniere_connexion'] ? date('d/m/Y H:i', strtotime($u['derniere_connexion'])) : 'Jamais' ?></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/users/edit/<?= $u['id'] ?>" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <?php if ($u['id'] !== session()->get('user_id')): ?>
                                <a href="/admin/users/delete/<?= $u['id'] ?>" class="btn btn-outline-danger" data-confirm="Désactiver cet utilisateur ?"><i class="bi bi-person-dash"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
