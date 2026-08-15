<?php $pageTitle = "EDT par classe"; require __DIR__ . '/../layout_header.php'; ?>

<div class="page-title"><i class="bi bi-diagram-3-fill"></i>EDT par classe</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form class="d-flex gap-2 align-items-center flex-wrap" method="get">
      <input type="hidden" name="action" value="edt_classe">
      <label class="fw-semibold mb-0">Classe :</label>
      <select name="idclasse" class="form-select" style="max-width:280px">
        <option value="">-- Toutes les classes --</option>
        <?php foreach ($classes as $cl): ?>
          <option value="<?= htmlspecialchars($cl['idclasse']) ?>"
            <?= ($idclasseSel === $cl['idclasse']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cl['idclasse'].' – '.$cl['niveau']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Afficher</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    Cours <?= $idclasseSel ? 'pour la classe '.htmlspecialchars($idclasseSel) : '' ?>
    (<?= count($cours) ?>)
  </div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead><tr><th>Date</th><th>Cours</th><th>Professeur</th><th>Salle</th></tr></thead>
      <tbody>
      <?php if (empty($cours)): ?>
        <tr><td colspan="4" class="text-center text-muted py-4">Aucun cours trouvé.</td></tr>
      <?php else: foreach ($cours as $c): ?>
        <tr>
          <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($c['Date']))) ?></td>
          <td><strong><?= htmlspecialchars($c['cours']) ?></strong></td>
          <td><?= htmlspecialchars($c['prof_nom']) ?></td>
          <td><?= htmlspecialchars($c['salle_nom']) ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>