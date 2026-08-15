<?php $pageTitle = "Professeurs"; require __DIR__ . '/../layout_header.php'; ?>

<div class="page-title"><i class="bi bi-person-badge-fill"></i>Professeurs</div>

<?php if (isset($_GET['success']) && $_GET['success'] === 'email'): ?>
  <div class="alert alert-success"><i class="bi bi-envelope-check me-2"></i>Email envoyé avec succès !</div>
<?php elseif (isset($_GET['success'])): ?>
  <div class="alert alert-success">Opération effectuée avec succès.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'foreign_key'): ?>
  <div class="alert alert-danger">Impossible de supprimer : des cours sont encore associés à ce professeur.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'no_email'): ?>
  <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Ce professeur n'a pas d'email enregistré.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'email'): ?>
  <div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Échec de l'envoi de l'email.</div>
<?php elseif (isset($_GET['error'])): ?>
  <div class="alert alert-danger">Une erreur est survenue.</div>
<?php endif; ?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-table me-2"></i>Liste (<?= count($profs) ?>)</span>
    <a href="index.php?action=professeur_new" class="btn btn-light btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Ajouter
    </a>
  </div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead>
        <tr><th>ID</th><th>Nom</th><th>Prénoms</th><th>Grade</th><th>Email</th><th>Actions</th></tr>
      </thead>
      <tbody>
      <?php if (empty($profs)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">Aucun professeur enregistré.</td></tr>
      <?php else: foreach ($profs as $p): ?>
        <tr>
          <td><code style="color:#1a237e"><?= htmlspecialchars($p['idprof']) ?></code></td>
          <td><strong><?= htmlspecialchars($p['Nom']) ?></strong></td>
          <td><?= htmlspecialchars($p['Prenoms']) ?></td>
          <td><span class="badge" style="background:#e8eaf6;color:#1a237e;font-weight:500">
            <?= htmlspecialchars($p['Grade']) ?>
          </span></td>
          <td><?= htmlspecialchars($p['email'] ?? '—') ?></td>
          <td>
            <a href="index.php?action=professeur_edit&id=<?= urlencode($p['idprof']) ?>"
               class="btn btn-sm btn-outline-primary" title="Modifier"><i class="bi bi-pencil"></i></a>
            <a href="index.php?action=professeur_del&id=<?= urlencode($p['idprof']) ?>"
               class="btn btn-sm btn-outline-danger" title="Supprimer"
               onclick="return confirm('Supprimer <?= htmlspecialchars($p['Nom']) ?> ?')">
              <i class="bi bi-trash"></i></a>
            <?php if (!empty($p['email'])): ?>
            <a href="index.php?action=professeur_email&id=<?= urlencode($p['idprof']) ?>"
               class="btn btn-sm btn-outline-success" title="Envoyer EDT par email"
               onclick="return confirm('Envoyer l\'EDT à <?= htmlspecialchars($p['email']) ?> ?')">
              <i class="bi bi-envelope"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>