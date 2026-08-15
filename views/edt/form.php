<?php
$pageTitle = $cours ? "Modifier un cours" : "Ajouter un cours";
require __DIR__ . '/../layout_header.php';
$dateValue = $cours ? str_replace(' ', 'T', substr($cours['date'], 0, 16)) : '';
?>

<div class="page-title"><i class="bi bi-calendar-plus-fill"></i><?= $pageTitle ?></div>
<div class="card" style="max-width:1200px">
  <div class="card-header">Détails du cours</div>
  <div class="card-body">
    <form method="post">

      <div class="mb-3">
        <label class="form-label fw-semibold">Cours / Matière</label>
        <input name="cours" value="<?= htmlspecialchars($cours['cours'] ?? '') ?>"
               class="form-control" required placeholder="ex: Algorithmique, Bases de Données…">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Date &amp; Heure</label>
        <input type="datetime-local" name="date" id="dateInput"
               value="<?= htmlspecialchars($dateValue) ?>" class="form-control" required>
        <small class="text-muted">Format : AAAA-MM-JJ HH:MM</small>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Professeur</label>
        <select name="idprof" class="form-select" required>
          <option value="">-- Choisir un professeur --</option>
          <?php foreach ($profs as $p): ?>
            <option value="<?= htmlspecialchars($p['idprof']) ?>"
              <?= ($cours && $cours['idprof']===$p['idprof']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['idprof'].' – '.$p['Nom'].' '.$p['Prenoms'].' ('.$p['Grade'].')') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Classe</label>
        <select name="idclasse" class="form-select" required>
          <option value="">-- Choisir une classe --</option>
          <?php foreach ($classes as $cl): ?>
            <option value="<?= htmlspecialchars($cl['idclasse']) ?>"
              <?= ($cours && $cours['idclasse']===$cl['idclasse']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cl['idclasse'].' – '.$cl['niveau']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-4">
        <label class="form-label fw-semibold">
          Salle
          <span id="salleBadge" class="badge bg-secondary ms-2" style="display:none"></span>
        </label>
        <select name="idsalle" id="salleSelect" class="form-select" required>
          <option value="">-- Choisissez d'abord une date --</option>
          <?php foreach ($salles as $s): ?>
            <option value="<?= $s['idsalle'] ?>"
                    data-design="<?= htmlspecialchars($s['design']) ?>"
                    data-occupation="<?= htmlspecialchars($s['occupation']) ?>"
              <?= ($cours && $cours['idsalle']==$s['idsalle']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['design'].' ('.$s['occupation'].')') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="text-muted" id="salleInfo"></small>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Enregistrer</button>
        <a href="index.php?action=edt" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<script>
// Toutes les salles disponibles côté PHP (injection JSON)
const toutesLesSalles = <?= json_encode(array_values($salles)) ?>;
const salleActuelle   = <?= $cours ? (int)$cours['idsalle'] : 'null' ?>;

function filtrerSalles(dateStr) {
    const select = document.getElementById('salleSelect');
    const badge  = document.getElementById('salleBadge');
    const info   = document.getElementById('salleInfo');

    if (!dateStr) {
        select.innerHTML = '<option value="">-- Choisissez d\'abord une date --</option>';
        badge.style.display = 'none';
        return;
    }

    // Extraire uniquement la date (YYYY-MM-DD) pour comparer avec la BDD
    const dateOnly = dateStr.substring(0, 10);

    // Appel AJAX pour obtenir les idsalle occupées ce jour
    fetch('index.php?action=salles_occupees&date=' + encodeURIComponent(dateOnly))
        .then(r => r.json())
        .then(occupees => {
            select.innerHTML = '<option value="">-- Choisir une salle --</option>';
            let nbLibres = 0;

            toutesLesSalles.forEach(s => {
                const estOccupee = occupees.includes(parseInt(s.idsalle));
                // En modification, toujours afficher la salle actuelle même occupée
                if (!estOccupee || s.idsalle == salleActuelle) {
                    const opt = document.createElement('option');
                    opt.value = s.idsalle;
                    opt.textContent = s.design + (estOccupee ? ' (Occupée ce jour)' : ' (Libre)');
                    if (s.idsalle == salleActuelle) opt.selected = true;
                    select.appendChild(opt);
                    if (!estOccupee) nbLibres++;
                }
            });

            badge.textContent = nbLibres + ' salle(s) libre(s)';
            badge.style.display = 'inline';
            info.textContent = nbLibres === 0 ? 'Aucune salle disponible à cette date.' : '';
        })
        .catch(() => {
            // En cas d'erreur AJAX, afficher toutes les salles
            select.innerHTML = '<option value="">-- Choisir une salle --</option>';
            toutesLesSalles.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.idsalle;
                opt.textContent = s.design + ' (' + s.occupation + ')';
                if (s.idsalle == salleActuelle) opt.selected = true;
                select.appendChild(opt);
            });
        });
}

// Déclencher au changement de date
document.getElementById('dateInput').addEventListener('change', function() {
    filtrerSalles(this.value);
});

// Déclencher au chargement si date déjà remplie (modification)
window.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('dateInput');
    if (dateInput.value) filtrerSalles(dateInput.value);
});
</script>

<?php require __DIR__ . '/../layout_footer.php'; ?>
