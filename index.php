<?php
/**
 * index.php — Front Controller
 */
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/Controller/ProfesseurController.php';
require_once __DIR__ . '/Controller/SalleController.php';
require_once __DIR__ . '/Controller/ClasseController.php';
require_once __DIR__ . '/Controller/EdtController.php';

$action = $_GET['action'] ?? 'accueil';

$routes = [
    'accueil'          => null,

    'professeurs'      => [ProfesseurController::class, 'index'],
    'professeur_new'   => [ProfesseurController::class, 'create'],
    'professeur_edit'  => [ProfesseurController::class, 'edit'],
    'professeur_del'   => [ProfesseurController::class, 'delete'],
    'professeur_email' => [ProfesseurController::class, 'envoyerEmail'],

    'salles'           => [SalleController::class, 'list'],
    'salle_new'        => [SalleController::class, 'add'],
    'salle_edit'       => [SalleController::class, 'edit'],
    'salle_del'        => [SalleController::class, 'delete'],
    'salles_libres'    => [SalleController::class, 'libres'],
    'salles_occupees'  => [SalleController::class, 'occupees'],

    'classes'          => [ClasseController::class, 'list'],
    'classe_new'       => [ClasseController::class, 'add'],
    'classe_edit'      => [ClasseController::class, 'edit'],
    'classe_del'       => [ClasseController::class, 'delete'],

    'edt'              => [EdtController::class, 'list'],
    'edt_new'          => [EdtController::class, 'add'],
    'edt_edit'         => [EdtController::class, 'edit'],
    'edt_del'          => [EdtController::class, 'delete'],
    'edt_classe'       => [EdtController::class, 'parClasse'],
    'edt_semaine'      => [EdtController::class, 'semaine'],
    'edt_semaine_pdf'  => [EdtController::class, 'genererPDF'],
];

if ($action === 'accueil') {
    require_once __DIR__ . '/Model/ProfModel.php';
    require_once __DIR__ . '/Model/SalleModel.php';
    require_once __DIR__ . '/Model/ClasseModel.php';
    require_once __DIR__ . '/Model/EdtModel.php';

    $stats = [
        'profs'   => count((new ProfModel())->readAll()),
        'salles'  => count((new SalleModel())->readAll()),
        'classes' => count((new ClasseModel())->readAll()),
        'cours'   => count((new EdtModel())->readAll()),
    ];
    $prochains        = array_slice((new EdtModel())->readAll(), 0, 5);
    $coursParClasse   = (new EdtModel())->countByClasse();
    $coursParProf     = (new EdtModel())->countByProf();
    $coursParJour     = (new EdtModel())->countByJour();
    require __DIR__ . '/views/accueil.php';
    exit;
}

if (isset($routes[$action])) {
    [$controller, $method] = $routes[$action];
    $controller = new $controller();

    if (isset($_GET['id'])) {
        $controller->$method($_GET['id']);
    } else {
        $controller->$method();
    }
} else {
    http_response_code(404);
    echo "Page non trouvée.";
}