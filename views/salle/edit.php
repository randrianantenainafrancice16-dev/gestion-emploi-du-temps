<?php
$pageTitle = "Modifier une salle";
require __DIR__ . '/../layout_header.php';
?>

<div class="page-title"><i class="bi bi-door-open-fill"></i><?= $pageTitle ?></div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:480px">
  <div class="card-header">Informations de la salle</div>
  <div class="card-body">
    <form method="post">
      <div class="mb-3">
        <label class="form-label fw-semibold">ID Salle</label>
        <input value="<?= htmlspecialchars($salle['idsalle']) ?>" class="form-control" readonly>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Désignation</label>
        <input name="design" value="<?= htmlspecialchars($salle['design']) ?>"
               class="form-control" required>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Statut</label>
        <select name="occupation" class="form-select">
          <option value="Libre" <?= ($salle['occupation'] === 'Libre') ? 'selected' : '' ?>>Libre</option>
          <option value="Occupée" <?= ($salle['occupation'] === 'Occupée') ? 'selected' : '' ?>>Occupée</option>
        </select>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Enregistrer</button>
        <a href="index.php?action=salles" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
