<?php
$pageTitle = "Vue Semaine";
require __DIR__ . '/../layout_header.php';

$jours    = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
$creneaux = ['08:00','10:00','12:00','14:00','16:00','18:00'];
$couleurs = ['#E3F2FD','#F3E5F5','#E8F5E9','#FFF3E0','#FCE4EC','#E0F7FA'];

$coursMap = [];
$dateDebutObj = new DateTime($dateDebut);
foreach ($cours as $c) {
    $dtCours = new DateTime($c['Date']);
    $diff = (int)$dateDebutObj->diff($dtCours)->format('%r%a');
    if ($diff >= 0 && $diff <= 5) {
        $heure = $dtCours->format('H:i');
        $coursMap[$diff][$heure] = $c;
    }
}
?>

<div class="page-title"><i class="bi bi-calendar-week-fill"></i>Emploi du temps — Vue semaine</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="get" action="index.php">
      <input type="hidden" name="action" value="edt_semaine">
      <div class="d-flex gap-2 align-items-center flex-wrap">
        <label class="fw-semibold mb-0">Semaine du :</label>
        <input type="date" name="date_debut"
               value="<?= htmlspecialchars($dateDebut) ?>"
               class="form-control" style="max-width:200px">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-search me-1"></i>Afficher
        </button>
        <a href="index.php?action=edt_semaine_pdf&date_debut=<?= htmlspecialchars($dateDebut) ?>"
           class="btn btn-danger ms-auto">
          <i class="bi bi-file-earmark-pdf me-1"></i>Exporter PDF
        </a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-2" style="overflow-x:auto">
    <table class="table table-bordered mb-0"
           style="min-width:900px;table-layout:fixed;width:100%">
      <thead>
        <tr>
          <th style="width:80px;background:#1a237e;color:#fff;text-align:center;vertical-align:middle">Horaire</th>
          <?php foreach ($jours as $j): ?>
            <th style="width:calc((100% - 80px)/6);background:#1a237e;color:#fff;text-align:center;padding:10px">
              <?= $j ?>
            </th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($creneaux as $idx => $cr): ?>
        <tr style="height:90px">
          <td style="background:#ECEFF1;color:#1a237e;font-weight:700;text-align:center;vertical-align:middle">
            <?= $cr ?>
          </td>
          <?php for ($j = 0; $j < 6; $j++): ?>
            <td style="vertical-align:top;padding:6px;background:#fafafa">
              <?php $c = $coursMap[$j][$cr] ?? null; if ($c): ?>
                <div style="background:<?= $couleurs[$j % count($couleurs)] ?>;border-left:3px solid #1a237e;border-radius:6px;padding:6px 8px;font-size:.8rem">
                  <div style="font-weight:700;color:#1a237e"><?= htmlspecialchars($c['cours']) ?></div>
                  <div class="text-muted" style="font-size:.75rem">
                    <i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($c['prof_nom']) ?>
                  </div>
                  <div class="text-muted" style="font-size:.75rem">
                    <i class="bi bi-building me-1"></i><?= htmlspecialchars($c['salle_nom']) ?>
                  </div>
                  <div class="text-muted" style="font-size:.75rem">
                    <i class="bi bi-mortarboard me-1"></i><?= htmlspecialchars($c['classe_nom']) ?>
                  </div>
                </div>
              <?php endif; ?>
            </td>
          <?php endfor; ?>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>