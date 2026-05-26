<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4><i class="bi bi-calendar-plus text-primary me-2"></i>Nouveau rendez-vous</h4>
    <a href="/admin/appointments" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i>Retour</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="/admin/appointments/store" method="POST" id="rdvForm">
                    <?= csrf_field() ?>

                    <!-- Étape 1 : Patient + Médecin -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patientSelect" class="form-select rounded-3" required>
                                <option value="">Sélectionner un patient...</option>
                                <?php foreach ($patients as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= esc($p['prenom'] . ' ' . $p['nom']) ?> — <?= esc($p['numero_dossier']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Médecin <span class="text-danger">*</span></label>
                            <select name="medecin_id" id="medecinSelect" class="form-select rounded-3" required onchange="onDoctorOrDateChange()">
                                <option value="">Sélectionner un médecin...</option>
                                <?php foreach ($medecins as $m): ?>
                                <option value="<?= $m['id'] ?>" data-duree="<?= $m['duree_consultation'] ?? 30 ?>">
                                    Dr. <?= esc($m['user_prenom'] . ' ' . $m['user_nom']) ?><?= $m['specialite_nom'] ? ' — ' . $m['specialite_nom'] : '' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Étape 2 : Date + Sélecteur de créneaux -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" id="dateRdv" name="date_rdv" class="form-control rounded-3"
                                   min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>"
                                   required onchange="onDoctorOrDateChange()">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Heure <span class="text-danger">*</span></label>
                            <input type="hidden" name="heure_rdv" id="heureInput" required>

                            <!-- Créneaux visuels -->
                            <div id="slotsWrapper">
                                <div id="slotsMsg" class="text-muted small mt-1" style="padding:8px 0">
                                    <i class="bi bi-info-circle me-1"></i>Sélectionnez un médecin et une date pour voir les créneaux
                                </div>
                                <div id="slotsPicker" class="d-flex flex-wrap gap-2 mt-1" style="display:none!important"></div>
                                <div id="slotsLoading" style="display:none" class="text-muted small py-2">
                                    <div class="spinner-border spinner-border-sm me-1"></div> Chargement...
                                </div>
                                <!-- Fallback input time -->
                                <input type="time" id="heureManual" class="form-control rounded-3 mt-2" step="900"
                                       style="display:none" placeholder="Heure manuelle"
                                       onchange="document.getElementById('heureInput').value=this.value">
                            </div>
                            <div id="conflictAlert" class="alert alert-danger border-0 rounded-3 mt-2 py-2 small" style="display:none"></div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type de rendez-vous</label>
                            <select name="type_rdv" id="typeRdv" class="form-select rounded-3" onchange="toggleTeleLink()">
                                <option value="consultation">Consultation</option>
                                <option value="suivi">Suivi</option>
                                <option value="urgence">Urgence</option>
                                <option value="teleconsultation">📹 Téléconsultation</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Durée (minutes)</label>
                            <select name="duree" id="dureeSelect" class="form-select rounded-3" onchange="onDoctorOrDateChange()">
                                <option value="15">15 min</option>
                                <option value="30" selected>30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">1 heure</option>
                            </select>
                        </div>
                    </div>

                    <!-- Lien téléconsultation (visible si type = teleconsultation) -->
                    <div id="teleLinkSection" class="mb-3" style="display:none">
                        <div class="p-3 rounded-3" style="background:#f0f7ff;border:1px solid #bfdbfe">
                            <label class="form-label fw-semibold small"><i class="bi bi-camera-video-fill me-1 text-primary"></i>Lien de téléconsultation</label>
                            <div class="input-group">
                                <input type="url" name="lien_tele" id="teleLinkInput" class="form-control rounded-3 border-0" placeholder="https://meet.google.com/xxx-xxxx-xxx" style="background:#fff">
                                <button type="button" class="btn btn-primary rounded-3 ms-2" onclick="generateMeetLink()" style="white-space:nowrap">
                                    <i class="bi bi-magic me-1"></i>Générer
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block">Ce lien sera envoyé au patient par email avec la confirmation.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Motif de la consultation</label>
                        <textarea name="motif" class="form-control rounded-3" rows="3" placeholder="Décrivez brièvement le motif de la consultation..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes internes</label>
                        <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="Notes visibles uniquement par le personnel..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill rounded-3 py-2 fw-600" id="submitBtn">
                            <i class="bi bi-calendar-check me-1"></i>Créer le rendez-vous
                        </button>
                        <a href="/admin/appointments" class="btn btn-outline-secondary rounded-3">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
let selectedHeure = '';
let conflictTimeout;

function onDoctorOrDateChange() {
    const medecinId = document.getElementById('medecinSelect').value;
    const date      = document.getElementById('dateRdv').value;
    const msg       = document.getElementById('slotsMsg');
    const picker    = document.getElementById('slotsPicker');
    const loading   = document.getElementById('slotsLoading');
    const manual    = document.getElementById('heureManual');

    if (!medecinId || !date) {
        msg.style.display = '';
        picker.style.display = 'none';
        manual.style.display = 'none';
        return;
    }

    // Mettre à jour la durée selon le médecin sélectionné
    const opt = document.querySelector(`#medecinSelect option[value="${medecinId}"]`);
    if (opt && opt.dataset.duree) {
        const dureeSelect = document.getElementById('dureeSelect');
        dureeSelect.value = opt.dataset.duree;
    }

    msg.style.display = 'none';
    loading.style.display = '';
    picker.style.display = 'none';
    picker.innerHTML = '';
    selectedHeure = '';
    document.getElementById('heureInput').value = '';

    fetch(`/api/slots/${medecinId}?date=${date}`)
        .then(r => r.json())
        .then(slots => {
            loading.style.display = 'none';

            if (!slots || slots.length === 0) {
                msg.innerHTML = '<i class="bi bi-calendar-x me-1 text-danger"></i>Aucun créneau disponible ce jour. <a href="#" onclick="showManual()">Saisir manuellement</a>';
                msg.style.display = '';
                manual.style.display = 'none';
                return;
            }

            picker.innerHTML = '';
            picker.style.removeProperty('display');

            slots.forEach(s => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = s.label;
                btn.dataset.heure = s.heure;
                btn.className = 'btn btn-sm rounded-3';
                btn.style.cssText = `
                    padding:6px 14px;font-weight:600;font-size:.85rem;transition:all .15s;
                    ${s.disponible
                        ? 'background:#f0f7ff;color:#1a56db;border:1px solid #bfdbfe'
                        : 'background:#f9fafb;color:#94a3b8;border:1px dashed #e2e8f0;cursor:not-allowed;text-decoration:line-through'}
                `;
                if (s.disponible) {
                    btn.onclick = () => selectSlot(s.heure, btn);
                } else {
                    btn.disabled = true;
                    btn.title = 'Créneau occupé';
                }
                picker.appendChild(btn);
            });

            // Lien fallback
            const fallback = document.createElement('div');
            fallback.innerHTML = '<small class="text-muted"><a href="#" onclick="showManual();return false">Heure personnalisée</a></small>';
            picker.after(fallback);
        })
        .catch(() => {
            loading.style.display = 'none';
            showManual();
        });
}

function selectSlot(heure, btn) {
    selectedHeure = heure;
    document.getElementById('heureInput').value = heure;
    document.getElementById('heureManual').value = heure;

    // Highlight
    document.querySelectorAll('#slotsPicker .btn').forEach(b => {
        b.style.background = b.dataset.heure === heure ? '#1a56db' : '#f0f7ff';
        b.style.color      = b.dataset.heure === heure ? '#fff' : '#1a56db';
        b.style.borderColor= b.dataset.heure === heure ? '#1a56db' : '#bfdbfe';
    });

    // Vérifier conflit en temps réel
    clearTimeout(conflictTimeout);
    conflictTimeout = setTimeout(() => checkConflict(heure), 300);
}

function checkConflict(heure) {
    const medecinId = document.getElementById('medecinSelect').value;
    const date      = document.getElementById('dateRdv').value;
    const duree     = document.getElementById('dureeSelect').value;
    const alertEl   = document.getElementById('conflictAlert');
    const submitBtn = document.getElementById('submitBtn');

    if (!medecinId || !date || !heure) return;

    const fd = new FormData();
    fd.append('medecin_id', medecinId);
    fd.append('date', date);
    fd.append('heure', heure);
    fd.append('duree', duree);

    fetch('/api/check-conflict', { method:'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (!data.available) {
                alertEl.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>' + data.message;
                alertEl.style.display = '';
                submitBtn.disabled = true;
            } else {
                alertEl.style.display = 'none';
                submitBtn.disabled = false;
            }
        });
}

function showManual() {
    document.getElementById('heureManual').style.display = '';
    document.getElementById('heureManual').focus();
}

function toggleTeleLink() {
    const type = document.getElementById('typeRdv').value;
    document.getElementById('teleLinkSection').style.display = type === 'teleconsultation' ? '' : 'none';
}

function generateMeetLink() {
    const chars = 'abcdefghijklmnopqrstuvwxyz';
    const rand  = n => Array.from({length:n}, () => chars[Math.floor(Math.random()*chars.length)]).join('');
    document.getElementById('teleLinkInput').value = `https://meet.google.com/${rand(3)}-${rand(4)}-${rand(3)}`;
}

// Valider heure avant submit
document.getElementById('rdvForm').addEventListener('submit', function(e) {
    const heure = document.getElementById('heureInput').value || document.getElementById('heureManual').value;
    if (!heure) {
        e.preventDefault();
        alert('Veuillez sélectionner un créneau horaire.');
        return;
    }
    document.getElementById('heureInput').value = heure;
});

// Charger créneaux au chargement si médecin et date déjà sélectionnés
document.addEventListener('DOMContentLoaded', onDoctorOrDateChange);
</script>
<?= $this->endSection() ?>
