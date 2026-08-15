<?php $pageTitle = "Modifier une classe"; require __DIR__ . '/../layout_header.php'; ?>

<div class="page-title"><i class="bi bi-mortarboard-fill"></i>Modifier une classe</div>

<?php if (isset($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:500px">
  <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Détails de la classe</div>
  <div class="card-body">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">ID Classe</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($classe['idclasse']) ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Niveau</label>
        <input type="text" name="niveau" class="form-control"
               value="<?= htmlspecialchars($classe['niveau']) ?>" required>
      </div>

      <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Modifier</button>
      <a href="index.php?action=classes" class="btn btn-outline-secondary">Annuler</a>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>