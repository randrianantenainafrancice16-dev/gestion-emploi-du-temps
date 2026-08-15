<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'EDT Universitaire' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { display: flex; min-height: 100vh; background: #f4f6fb; }

    /* Sidebar */
    .sidebar {
      width: 220px; min-height: 100vh; background: #1a237e;
      color: #fff; padding: 0; flex-shrink: 0;
    }
    .sidebar-brand {
      padding: 18px 20px 10px; border-bottom: 1px solid rgba(255,255,255,.15);
    }
    .sidebar-brand h6 { color: #fff; font-weight: 700; margin: 0; font-size: 1rem; }
    .sidebar-brand small { color: rgba(255,255,255,.6); font-size: .75rem; }
    .sidebar-section {
      padding: 14px 20px 4px; font-size: .7rem;
      text-transform: uppercase; color: rgba(255,255,255,.45); letter-spacing: .08em;
    }
    .sidebar a {
      display: flex; align-items: center; gap: 8px;
      padding: 8px 20px; color: rgba(255,255,255,.85);
      text-decoration: none; font-size: .875rem; transition: background .15s;
    }
    .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,.12); color: #fff; }

    /* Main */
    .main-content { flex: 1; padding: 28px 32px; max-width: calc(100vw - 220px); }
    .page-title {
      font-size: 1.4rem; font-weight: 700; color: #1a237e;
      margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
    }

    /* Cards */
    .card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .card-header {
      background: #1a237e; color: #fff; font-weight: 600;
      border-radius: 10px 10px 0 0 !important; padding: 12px 18px;
    }

    /* Stats */
    .stat-card {
      background: #fff; border-radius: 10px; padding: 20px 24px;
      box-shadow: 0 2px 8px rgba(0,0,0,.08); display: flex;
      align-items: center; gap: 16px;
    }
    .stat-icon {
      width: 50px; height: 50px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; color: #fff;
    }
    .stat-value { font-size: 1.8rem; font-weight: 700; color: #1a237e; line-height: 1; }
    .stat-label { font-size: .8rem; color: #666; margin-top: 2px; }

    /* Badge libre */
    .badge-libre { background: #e8f5e9; color: #2e7d32; padding: 3px 10px; border-radius: 20px; font-size: .8rem; }

    /* Alerts */
    .alert { border-radius: 8px; }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-brand">
    <h6><i class="bi bi-calendar3 me-2"></i>EDT Universitaire</h6>
    <small>Gestion emploi du temps</small>
  </div>

  <div class="sidebar-section">Général</div>
  <a href="index.php"><i class="bi bi-house-door"></i>Tableau de bord</a>

  <div class="sidebar-section">Référentiels</div>
  <a href="index.php?action=professeurs"><i class="bi bi-person-badge"></i>Professeurs</a>
  <a href="index.php?action=salles"><i class="bi bi-grid-3x3-gap"></i>Salles</a>
  <a href="index.php?action=classes"><i class="bi bi-mortarboard"></i>Classes</a>

  <div class="sidebar-section">Emploi du Temps</div>
  <a href="index.php?action=edt"><i class="bi bi-table"></i>Tous les cours</a>
  <a href="index.php?action=edt_new"><i class="bi bi-plus-circle"></i>Ajouter un cours</a>
  <a href="index.php?action=edt_classe"><i class="bi bi-diagram-3"></i>EDT par classe</a>
  <a href="index.php?action=edt_semaine"><i class="bi bi-calendar-week"></i>Vue semaine</a>

  <div class="sidebar-section">Recherche</div>
  <a href="index.php?action=salles_libres"><i class="bi bi-door-open"></i>Salles libres</a>
</nav>

<!-- Contenu principal -->
<div class="main-content">
