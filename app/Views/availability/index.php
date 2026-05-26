<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Disponibilités des médecins</h4>
        <small class="text-muted">Gérez les indisponibilités et vérifiez les créneaux libres</small>
    </div>
    <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg me-1"></i>Ajouter une indisponibilité
    </button>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success border-0 rounded-3"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="row g-3">
    <!-- Vérificateur de créneaux -->
    <div class="col-lg-5">
        <div class="card rounded-4 border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4"><i class="bi bi-search me-2 text-primary"></i>Vérifier la disponibilité</h6>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Médecin</label>
                    <select id="checkDoctor" class="form-select rounded-3">
                        <option value="">Sélectionner...</option>
                        <?php foreach($doctors as $d): ?>
                        <option value="<?= $d['id'] ?>" data-duree="<?= $d['duree_consultation']??30 ?>">
                            Dr. <?= esc($d['prenom'].' '.$d['nom']) ?> — <?= esc($d['specialite_nom']??'') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Date</label>
                    <input type="date" id="checkDate" class="form-control rounded-3" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                </div>
                <button class="btn btn-primary w-100 rounded-3" onclick="loadSlots()">
                    <i class="bi bi-calendar2-check me-1"></i>Voir les créneaux
                </button>

                <div id="slotsContainer" class="mt-4" style="display:none">
                    <h6 class="fw-bold mb-3 text-muted small text-uppercase">Créneaux disponibles</h6>
                    <div id="slotsList" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indisponibilités planifiées -->
    <div class="col-lg-7">
        <div class="card rounded-4 border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4"><i class="bi bi-calendar-x me-2 text-danger"></i>Indisponibilités planifiées</h6>
                <?php if(empty($schedules)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-calendar-check" style="font-size:2rem;opacity:.3"></i>
                    <p class="mt-2">Aucune indisponibilité planifiée</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="font-size:.9rem">
                        <thead style="background:#f8fafc">
                            <tr>
                                <th class="border-0 py-3 ps-3" style="color:#64748b;font-size:.8rem;font-weight:700">MÉDECIN</th>
                                <th class="border-0 py-3" style="color:#64748b;font-size:.8rem;font-weight:700">PÉRIODE</th>
                                <th class="border-0 py-3" style="color:#64748b;font-size:.8rem;font-weight:700">TYPE</th>
                                <th class="border-0 py-3" style="color:#64748b;font-size:.8rem;font-weight:700">MOTIF</th>
                                <th class="border-0 py-3" style="color:#64748b;font-size:.8rem;font-weight:700"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($schedules as $s):
                            $doc = null;
                            foreach($doctors as $d) { if($d['id']==$s['medecin_id']) { $doc=$d; break; } }
                        ?>
                        <tr>
                            <td class="ps-3">Dr. <?= esc($doc ? $doc['prenom'].' '.$doc['nom'] : '—') ?></td>
                            <td>
                                <div style="font-size:.85rem"><?= date('d/m/Y H:i', strtotime($s['date_debut'])) ?></div>
                                <div class="text-muted" style="font-size:.78rem">→ <?= date('d/m/Y H:i', strtotime($s['date_fin'])) ?></div>
                            </td>
                            <td>
                                <?php $typeColors = ['indisponible'=>'#fee2e2,#dc2626','conge'=>'#d1fae5,#059669','formation'=>'#dbeafe,#1a56db','autre'=>'#f1f5f9,#64748b'];
                                $tc = explode(',', $typeColors[$s['type']]??'#f1f5f9,#64748b'); ?>
                                <span style="background:<?= $tc[0] ?>;color:<?= $tc[1] ?>;padding:3px 10px;border-radius:50px;font-size:.75rem;font-weight:700">
                                    <?= ucfirst($s['type']) ?>
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:.85rem"><?= esc($s['motif']??'—') ?></td>
                            <td>
                                <a href="/admin/availability/delete/<?= $s['id'] ?>" class="btn btn-sm"
                                   style="background:#fee2e2;color:#dc2626;border:none;border-radius:8px"
                                   onclick="return confirm('Supprimer cette indisponibilité ?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL Ajouter indisponibilité -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Ajouter une indisponibilité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="/admin/availability/store" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Médecin *</label>
                        <select name="medecin_id" class="form-select rounded-3" required>
                            <option value="">Sélectionner...</option>
                            <?php foreach($doctors as $d): ?>
                            <option value="<?= $d['id'] ?>">Dr. <?= esc($d['prenom'].' '.$d['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Début *</label>
                            <input type="datetime-local" name="date_debut" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Fin *</label>
                            <input type="datetime-local" name="date_fin" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label fw-semibold small">Type *</label>
                        <select name="type" class="form-select rounded-3" required>
                            <option value="indisponible">Indisponible</option>
                            <option value="conge">Congé</option>
                            <option value="formation">Formation</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Motif (optionnel)</label>
                        <input type="text" name="motif" class="form-control rounded-3" placeholder="Ex: Congé annuel...">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-600">
                        <i class="bi bi-save me-2"></i>Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function loadSlots() {
    const docId = document.getElementById('checkDoctor').value;
    const date  = document.getElementById('checkDate').value;
    if (!docId || !date) { alert('Sélectionnez un médecin et une date.'); return; }

    fetch(`/api/slots/${docId}?date=${date}`)
        .then(r => r.json())
        .then(slots => {
            const container = document.getElementById('slotsContainer');
            const list      = document.getElementById('slotsList');
            container.style.display = 'block';
            list.innerHTML = '';

            if (!slots.length) {
                list.innerHTML = '<span class="text-muted small">Aucun créneau ce jour</span>';
                return;
            }

            slots.forEach(s => {
                const btn = document.createElement('span');
                btn.textContent = s.label;
                btn.style.cssText = `
                    padding:6px 14px;border-radius:8px;font-size:.85rem;font-weight:600;cursor:default;
                    background:${s.disponible ? '#d1fae5' : '#fee2e2'};
                    color:${s.disponible ? '#059669' : '#dc2626'};
                    border:1px solid ${s.disponible ? '#a7f3d0' : '#fecaca'};
                `;
                list.appendChild(btn);
            });
        });
}
</script>
<?= $this->endSection() ?>
