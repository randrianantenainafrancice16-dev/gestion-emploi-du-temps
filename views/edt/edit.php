<?php
$pageTitle = "Modifier une classe";
require __DIR__ . '/../layout_header.php';
?>

<div class="page-title"><i class="bi bi-mortarboard-fill"></i><?= $pageTitle ?></div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:480px">
  <div class="card-header">Informations de la classe</div>
  <div class="card-body">
    <form method="post">
      <div class="mb-3">
        <label class="form-label fw-semibold">ID Classe</label>
        <input value="<?= htmlspecialchars($classe['idclasse']) ?>" class="form-control" readonly>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Niveau</label>
        <input name="niveau" value="<?= htmlspecialchars($classe['niveau']) ?>"
               class="form-control" required>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Enregistrer</button>
        <a href="index.php?action=classes" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
