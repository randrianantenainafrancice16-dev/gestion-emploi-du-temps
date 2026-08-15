<?php $pageTitle = "Tous les cours"; require __DIR__ . '/../layout_header.php'; ?>

<div class="page-title"><i class="bi bi-table"></i>Cours planifiés</div>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">Opération effectuée avec succès.</div>
<?php elseif (isset($_GET['error'])): ?>
  <div class="alert alert-danger">Une erreur est survenue.</div>
<?php endif; ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-table me-2"></i>Liste (<?= count($cours) ?>)</span>
    <a href="index.php?action=edt_new" class="btn btn-light btn-sm"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead>
        <tr><th>Date</th><th>Cours</th><th>Professeur</th><th>Classe</th><th>Salle</th><th>Actions</th></tr>
      </thead>
      <tbody>
      <?php if (empty($cours)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">Aucun cours planifié.</td></tr>
      <?php else: foreach ($cours as $c): ?>
        <tr>
          <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($c['Date']))) ?></td>
          <td><strong><?= htmlspecialchars($c['cours']) ?></strong></td>
          <td><?= htmlspecialchars($c['prof_nom']) ?></td>
          <td><?= htmlspecialchars($c['classe_nom']) ?></td>
          <td><?= htmlspecialchars($c['salle_nom']) ?></td>
          <td>
            <a href="index.php?action=edt_edit&id=<?= $c['idedt'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="index.php?action=edt_del&id=<?= $c['idedt'] ?>" class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Supprimer ce cours ?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
