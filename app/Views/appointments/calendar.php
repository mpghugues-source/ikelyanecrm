<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = $base_url ?? '/admin'; ?>

<div class="page-header">
    <h4><i class="bi bi-calendar3 text-primary me-2"></i>Calendrier des rendez-vous</h4>
    <div class="d-flex gap-2 align-items-center">
        <select id="filterMedecin" class="form-select form-select-sm" style="width:auto">
            <option value="">Tous les médecins</option>
            <?php foreach ($medecins as $m): ?>
            <option value="<?= $m['id'] ?>" <?= ($medecin_id ?? 0) == $m['id'] ? 'selected' : '' ?>>
                Dr. <?= esc($m['user_prenom'] . ' ' . $m['user_nom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <a href="<?= $base ?>/appointments/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nouveau RDV
        </a>
    </div>
</div>

<!-- Légende -->
<div class="d-flex gap-3 mb-3 small flex-wrap">
    <span><span class="badge" style="background:#0d6efd">&nbsp;</span> Planifié</span>
    <span><span class="badge" style="background:#198754">&nbsp;</span> Confirmé</span>
    <span><span class="badge" style="background:#fd7e14">&nbsp;</span> En cours</span>
    <span><span class="badge" style="background:#6c757d">&nbsp;</span> Terminé</span>
    <span><span class="badge" style="background:#dc3545">&nbsp;</span> Absent</span>
</div>

<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today:    "Aujourd'hui",
            month:    'Mois',
            week:     'Semaine',
            day:      'Jour',
            list:     'Liste'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        height: 'auto',
        nowIndicator: true,
        eventClick: function(info) {
            const props = info.event.extendedProps;
            alert('Patient: ' + info.event.title + '\nMotif: ' + (props.motif || '—') + '\nStatut: ' + props.statut);
        },
        events: function(info, successCallback, failureCallback) {
            const medecinId = document.getElementById('filterMedecin').value;
            fetch('<?= $base ?>/appointments/json?medecin_id=' + medecinId)
                .then(r => r.json())
                .then(data => successCallback(data))
                .catch(e => failureCallback(e));
        }
    });
    calendar.render();

    document.getElementById('filterMedecin').addEventListener('change', function() {
        calendar.refetchEvents();
    });
});
</script>
<?= $this->endSection() ?>
