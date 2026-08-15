<?php
require_once __DIR__ . '/../Model/SalleModel.php';

class SalleController {
    private SalleModel $salleModel;

    public function __construct() {
        $this->salleModel = new SalleModel();
    }

    public function list(): void {
        $salles = $this->salleModel->readAll();
        require_once __DIR__ . '/../views/salle/index.php';
    }

    // Retourne en JSON les idsalle occupées à une date donnée (pour le formulaire EDT)
    public function occupees(): void {
        $date = $_GET['date'] ?? date('Y-m-d');
        $sql  = "SELECT DISTINCT idsalle FROM edt WHERE DATE(Date) = :date";
        // On passe par le modèle directement n'est pas possible sans PDO exposé,
        // donc on utilise une requête via le modèle salle
        $occupees = $this->salleModel->getOccupeesByDate($date);
        header('Content-Type: application/json');
        echo json_encode($occupees);
        exit();
    }

    public function libres(): void {
        $dtStr  = $_GET['datetime'] ?? date('Y-m-d\TH:i');
        $salles = $this->salleModel->searchSallesLibres($dtStr);
        require_once __DIR__ . '/../views/salle/list.php';
    }

    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'design'     => htmlspecialchars(trim($_POST['design'] ?? '')),
                'occupation' => htmlspecialchars(trim($_POST['occupation'] ?? 'Libre'))
            ];

            if (!empty($data['design'])) {
                if ($this->salleModel->create($data)) {
                    header('Location: index.php?action=salles&success=add');
                    exit();
                } else {
                    $error = "Erreur lors de l'ajout.";
                }
            } else {
                $error = "La désignation est obligatoire.";
            }
        }
        require_once __DIR__ . '/../views/salle/add.php';
    }

    public function edit(int $id): void {
        $salle = $this->salleModel->read($id);
        if (!$salle) {
            header('Location: index.php?action=salles');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'design'     => htmlspecialchars(trim($_POST['design'] ?? '')),
                'occupation' => htmlspecialchars(trim($_POST['occupation'] ?? 'Libre'))
            ];

            if (!empty($data['design'])) {
                if ($this->salleModel->update($id, $data)) {
                    header('Location: index.php?action=salles&success=edit');
                    exit();
                } else {
                    $error = "Erreur lors de la modification.";
                }
            } else {
                $error = "La désignation est obligatoire.";
            }
        }

        require_once __DIR__ . '/../views/salle/edit.php';
    }

    public function detail(int $id): void {
        $salle = $this->salleModel->read($id);
        if (!$salle) {
            header('Location: index.php?action=salles');
            exit();
        }
        require_once __DIR__ . '/../views/salle/detail.php';
    }

    public function delete(int $id): void {
        try {
            if ($this->salleModel->delete($id)) {
                header('Location: index.php?action=salles&success=delete');
            } else {
                header('Location: index.php?action=salles&error=delete');
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                header('Location: index.php?action=salles&error=foreign_key');
            } else {
                throw $e;
            }
        }
        exit();
    }

}
