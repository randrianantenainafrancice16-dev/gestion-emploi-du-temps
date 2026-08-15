<?php
$pageTitle = $prof ? "Modifier un professeur" : "Ajouter un professeur";
require __DIR__ . '/../layout_header.php';
?>

<div class="page-title"><i class="bi bi-person-plus-fill"></i><?= $pageTitle ?></div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:520px">
  <div class="card-header">Informations du professeur</div>
  <div class="card-body">
    <form method="post">
      <div class="mb-3">
        <label class="form-label fw-semibold">ID Professeur</label>
        <input name="idprof" value="<?= htmlspecialchars($prof['idprof'] ?? '') ?>"
               class="form-control" <?= $prof ? 'readonly' : 'required' ?> placeholder="ex: P001">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Nom</label>
        <input name="nom" value="<?= htmlspecialchars($prof['Nom'] ?? '') ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Prénoms</label>
        <input name="prenoms" value="<?= htmlspecialchars($prof['Prenoms'] ?? '') ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Grade</label>
        <select name="grade" class="form-select" required>
          <?php
          $grades = ['Assistant', 'Maître de Conférences', 'Professeur Titulaire', 'Doctorant en Informatique', 'Vacataire'];
          foreach ($grades as $g): ?>
            <option value="<?= htmlspecialchars($g) ?>"
              <?= (isset($prof['Grade']) && $prof['Grade'] === $g) ? 'selected' : '' ?>>
              <?= htmlspecialchars($g) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($prof['email'] ?? '') ?>"
               class="form-control" placeholder="ex: professeur@univ.mg">
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Enregistrer</button>
        <a href="index.php?action=professeurs" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>