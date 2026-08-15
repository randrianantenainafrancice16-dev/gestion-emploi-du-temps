<?php $pageTitle = "Salles"; require __DIR__ . '/../layout_header.php'; ?>

<div class="page-title"><i class="bi bi-door-open-fill"></i>Salles</div>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">Opération effectuée avec succès.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'foreign_key'): ?>
  <div class="alert alert-danger">Impossible de supprimer : des cours sont encore associés à cette salle.</div>
<?php elseif (isset($_GET['error'])): ?>
  <div class="alert alert-danger">Une erreur est survenue.</div>
<?php endif; ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-table me-2"></i>Liste (<?= count($salles) ?>)</span>
    <a href="index.php?action=salle_new" class="btn btn-light btn-sm"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead>
        <tr><th>#</th><th>Désignation</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
      <?php if (empty($salles)): ?>
        <tr><td colspan="4" class="text-center text-muted py-4">Aucune salle enregistrée.</td></tr>
      <?php else: foreach ($salles as $s): ?>
        <tr>
          <td><code style="color:#1a237e"><?= htmlspecialchars($s['idsalle']) ?></code></td>
          <td><strong><?= htmlspecialchars($s['design']) ?></strong></td>
          <td>
            <?php if ($s['occupation'] === 'Libre'): ?>
              <span class="badge bg-success">Libre</span>
            <?php else: ?>
              <span class="badge bg-danger">Occupée</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="index.php?action=salle_edit&id=<?= urlencode($s['idsalle']) ?>"
               class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?action=salle_del&id=<?= urlencode($s['idsalle']) ?>"
               class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Supprimer <?= htmlspecialchars($s['design']) ?> ?')">
              <i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
