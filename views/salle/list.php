<?php $pageTitle = "Salles libres"; require __DIR__ . '/../layout_header.php'; ?>

<div class="page-title"><i class="bi bi-door-open-fill"></i>Salles libres à une heure donnée</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form class="d-flex gap-2 align-items-center" method="get">
      <input type="hidden" name="action" value="salles_libres">
      <label class="fw-semibold mb-0">Date &amp; Heure :</label>
      <input type="datetime-local" name="datetime" value="<?= htmlspecialchars($dtStr) ?>"
             class="form-control" style="max-width:240px">
      <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Rechercher</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    Salles disponibles à <?= htmlspecialchars(str_replace('T',' ',$dtStr)) ?>
    — <?= count($salles) ?> salle(s) libre(s)
  </div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead><tr><th>#</th><th>Salle</th><th>Statut</th></tr></thead>
      <tbody>
      <?php if (empty($salles)): ?>
        <tr><td colspan="3" class="text-center text-muted py-4">
          <i class="bi bi-exclamation-circle me-2"></i>Toutes les salles sont occupées à cet horaire.
        </td></tr>
      <?php else: foreach ($salles as $s): ?>
        <tr>
          <td><?= $s['idsalle'] ?></td>
          <td><strong><?= htmlspecialchars($s['design']) ?></strong></td>
          <td><span class="badge-libre"><i class="bi bi-check-circle me-1"></i>Disponible</span></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
