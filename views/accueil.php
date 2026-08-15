<?php $pageTitle = "Tableau de bord"; require __DIR__ . '/layout_header.php'; ?>

<div class="page-title"><i class="bi bi-speedometer2"></i>Tableau de bord</div>

<!-- Cartes statistiques -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#1a237e"><i class="bi bi-person-badge"></i></div>
      <div>
        <div class="stat-value"><?= $stats['profs'] ?></div>
        <div class="stat-label">Professeurs</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#1565c0"><i class="bi bi-grid-3x3-gap"></i></div>
      <div>
        <div class="stat-value"><?= $stats['salles'] ?></div>
        <div class="stat-label">Salles</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#283593"><i class="bi bi-mortarboard"></i></div>
      <div>
        <div class="stat-value"><?= $stats['classes'] ?></div>
        <div class="stat-label">Classes</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#0d47a1"><i class="bi bi-calendar-check"></i></div>
      <div>
        <div class="stat-value"><?= $stats['cours'] ?></div>
        <div class="stat-label">Cours planifiés</div>
      </div>
    </div>
  </div>
</div>

<!-- Graphiques -->
<div class="row g-3 mb-4">
  <!-- Diagramme circulaire : cours par classe -->
  <div class="col-md-5">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-pie-chart-fill me-2"></i>Cours par classe</div>
      <div class="card-body d-flex justify-content-center align-items-center">
        <canvas id="chartParClasse" style="max-height:260px"></canvas>
      </div>
    </div>
  </div>

  <!-- Histogramme : cours par jour -->
  <div class="col-md-7">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-calendar-week-fill me-2"></i>Nombre de cours par jour de la semaine</div>
      <div class="card-body">
        <canvas id="chartParJour" style="max-height:260px"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Prochains cours -->
<div class="card">
  <div class="card-header"><i class="bi bi-clock me-2"></i>Prochains cours</div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead><tr><th>Date</th><th>Cours</th><th>Professeur</th><th>Classe</th><th>Salle</th></tr></thead>
      <tbody>
      <?php if (empty($prochains)): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">Aucun cours planifié.</td></tr>
      <?php else: foreach ($prochains as $c): ?>
        <tr>
          <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($c['Date']))) ?></td>
          <td><strong><?= htmlspecialchars($c['cours']) ?></strong></td>
          <td><?= htmlspecialchars($c['prof_nom']) ?></td>
          <td><?= htmlspecialchars($c['classe_nom']) ?></td>
          <td><?= htmlspecialchars($c['salle_nom']) ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
// Données PHP → JavaScript
const dataClasse = <?= json_encode($coursParClasse) ?>;
const dataJour   = <?= json_encode($coursParJour) ?>;

// Jours complets de la semaine (même si certains ont 0 cours)
const tousLesJours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
const nbParJour = tousLesJours.map(j => {
    const found = dataJour.find(d => d.label === j);
    return found ? parseInt(found.nb) : 0;
});

const couleurs = [
  '#1a237e','#1565c0','#0277bd','#00838f',
  '#2e7d32','#f57f17','#e65100','#ad1457',
  '#6a1b9a','#4527a0'
];

// Diagramme circulaire
new Chart(document.getElementById('chartParClasse'), {
  type: 'pie',
  data: {
    labels: dataClasse.map(d => d.label),
    datasets: [{
      data: dataClasse.map(d => d.nb),
      backgroundColor: couleurs.slice(0, dataClasse.length),
      borderWidth: 2,
      borderColor: '#fff'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom', labels: { font: { size: 11 } } }
    }
  }
});

// Histogramme cours par jour
new Chart(document.getElementById('chartParJour'), {
  type: 'bar',
  data: {
    labels: tousLesJours,
    datasets: [{
      label: 'Nombre de cours',
      data: nbParJour,
      backgroundColor: [
        '#1a237e','#1565c0','#0277bd','#00838f','#2e7d32','#f57f17'
      ],
      borderRadius: 6,
      borderSkipped: false
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
  }
});
</script>

<?php require __DIR__ . '/layout_footer.php'; ?>