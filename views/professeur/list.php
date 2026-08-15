<?php $pageTitle = "Classes"; require __DIR__ . '/../layout_header.php'; ?>

<div class="page-title"><i class="bi bi-mortarboard-fill"></i>Classes</div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-table me-2"></i>Liste (<?= count($classes) ?>)</span>
    <a href="index.php?action=classe_new" class="btn btn-light btn-sm"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead><tr><th>ID</th><th>Niveau</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($classes)): ?>
        <tr><td colspan="3" class="text-center text-muted py-4">Aucune classe enregistrée.</td></tr>
      <?php else: foreach ($classes as $c): ?>
        <tr>
          <td><code><?= htmlspecialchars($c['idclasse']) ?></code></td>
          <td><?= htmlspecialchars($c['niveau']) ?></td>
          <td>
            <a href="index.php?action=classe_edit&id=<?= urlencode($c['idclasse']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?action=classe_del&id=<?= urlencode($c['idclasse']) ?>" class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Supprimer <?= htmlspecialchars($c['niveau']) ?> ?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
