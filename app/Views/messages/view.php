<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-envelope-open me-2 text-primary"></i><?= esc($msg['sujet'] ?? 'Message') ?></h1>
    <a href="/messages" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i><?= lang('Common.back') ?>
    </a>
</div>

<div class="card mb-4">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong><?= lang('Common.from') ?> :</strong>
                <?= esc($msg['from_nom'] ?? '—') ?>
                <span class="badge bg-secondary ms-1" style="font-size:.65rem"><?= ucfirst($msg['from_role'] ?? '') ?></span>
            </div>
            <div>
                <strong><?= lang('Common.to') ?> :</strong>
                <?= esc($msg['to_nom'] ?? '—') ?>
                <span class="badge bg-secondary ms-1" style="font-size:.65rem"><?= ucfirst($msg['to_role'] ?? '') ?></span>
            </div>
            <div class="text-muted" style="font-size:.85rem">
                <i class="bi bi-clock me-1"></i><?= date('d/m/Y', strtotime($msg['created_at'])) ?> <?= lang('Common.at') ?> <?= date('H:i', strtotime($msg['created_at'])) ?>
            </div>
        </div>
    </div>
    <div class="card-body" style="min-height:120px;font-size:.95rem;line-height:1.7">
        <?= nl2br(esc($msg['message'])) ?>
    </div>
</div>

<!-- Reply -->
<div class="card">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-reply me-2 text-primary"></i><?= lang('Common.reply') ?>
    </div>
    <div class="card-body">
        <form method="POST" action="/messages/store">
            <?= csrf_field() ?>
            <input type="hidden" name="to_user_id"
                value="<?= session('user_id') == $msg['from_user_id'] ? $msg['to_user_id'] : $msg['from_user_id'] ?>">
            <input type="hidden" name="rdv_id" value="<?= $msg['rdv_id'] ?? '' ?>">
            <div class="mb-3">
                <label class="form-label fw-semibold"><?= lang('Common.subject') ?></label>
                <input type="text" name="sujet" class="form-control"
                    value="<?= esc(str_starts_with($msg['sujet'] ?? '', 'Re:') ? $msg['sujet'] : 'Re: ' . $msg['sujet']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold"><?= lang('Common.message') ?></label>
                <textarea name="message" class="form-control" rows="5" required
                    placeholder="<?= lang('Common.your_reply') ?>"></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i><?= lang('Common.send_reply') ?>
                </button>
                <a href="/messages" class="btn btn-outline-secondary"><?= lang('Common.cancel') ?></a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
