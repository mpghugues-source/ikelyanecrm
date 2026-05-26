<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-envelope me-2 text-primary"></i><?= esc($title) ?>
        <?php if ($box !== 'sent' && $unread > 0): ?>
            <span class="badge bg-danger ms-2" style="font-size:.65rem"><?= $unread ?></span>
        <?php endif; ?>
    </h1>
    <a href="/messages/create" class="btn btn-primary">
        <i class="bi bi-pencil-square me-1"></i><?= lang('Nav.new_message') ?>
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-white d-flex gap-2">
        <a href="/messages" class="btn btn-sm <?= $box === 'inbox' ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <i class="bi bi-inbox me-1"></i>Reçus
            <?php if ($unread > 0 && $box !== 'sent'): ?><span class="badge bg-danger ms-1"><?= $unread ?></span><?php endif; ?>
        </a>
        <a href="/messages?box=sent" class="btn btn-sm <?= $box === 'sent' ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <i class="bi bi-send me-1"></i>Envoyés
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:30px"></th>
                        <th><?= $box === 'sent' ? 'À' : 'De' ?></th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($messages)): ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-envelope-open" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px"></i>
                        Aucun message
                    </td></tr>
                <?php else: foreach ($messages as $m): ?>
                    <?php $unreadRow = ($box === 'inbox' && !$m['is_read']); ?>
                    <tr class="<?= $unreadRow ? 'fw-bold' : '' ?>">
                        <td>
                            <?php if ($unreadRow): ?>
                                <span class="badge bg-primary" style="font-size:.55rem;padding:3px 5px"><?= lang('Nav.new_badge') ?></span>
                            <?php else: ?>
                                <i class="bi bi-envelope<?= $unreadRow ? '' : '-open' ?> text-muted"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $nom  = esc($box === 'sent' ? ($m['to_nom'] ?? '—') : ($m['from_nom'] ?? '—'));
                            $role = $box === 'sent' ? ($m['to_role'] ?? '') : ($m['from_role'] ?? '');
                            $roleColors = ['admin'=>'secondary','medecin'=>'info','patient'=>'success','secretaire'=>'warning'];
                            $roleColor = $roleColors[$role] ?? 'secondary';
                            echo $nom;
                            ?>
                            <span class="badge bg-<?= $roleColor ?> ms-1" style="font-size:.65rem"><?= ucfirst($role) ?></span>
                        </td>
                        <td><?= esc($m['sujet'] ?? '(sans sujet)') ?></td>
                        <td class="text-muted" style="font-size:.85rem"><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                        <td class="text-end">
                            <a href="/messages/<?= $m['id'] ?>/view" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if ($box === 'sent'): ?>
                            <form method="POST" action="/messages/<?= $m['id'] ?>/delete" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce message ?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
