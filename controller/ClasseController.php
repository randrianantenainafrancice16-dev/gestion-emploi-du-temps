<?php
require_once __DIR__ . '/../Model/ClasseModel.php';

class ClasseController
{
    private ClasseModel $classeModel;

    public function __construct()
    {
        $this->classeModel = new ClasseModel();
    }

    public function list(): void {
        if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
            $classes = $this->classeModel->search(trim($_GET['keyword']));
        } else {
            $classes = $this->classeModel->readAll();
        }
        require_once __DIR__ . '/../views/classe/list.php';
    }

    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idclasse' => htmlspecialchars(trim($_POST['idclasse'] ?? '')),
                'niveau'   => htmlspecialchars(trim($_POST['niveau'] ?? ''))
            ];

            if (!empty($data['idclasse']) && !empty($data['niveau'])) {
                if ($this->classeModel->create($data)) {
                    header('Location: index.php?action=classes&success=1');
                    exit();
                } else {
                    $error = "Erreur lors de l'ajout de la classe.";
                }
            } else {
                $error = "L'ID et le niveau sont obligatoires.";
            }
        }
        require_once __DIR__ . '/../views/classe/add.php';
    }

    public function edit(string $idclasse): void {
        $classe = $this->classeModel->read($idclasse);
        if (!$classe) {
            header('Location: index.php?action=classes');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'niveau' => htmlspecialchars(trim($_POST['niveau'] ?? ''))
            ];

            if (!empty($data['niveau'])) {
                if ($this->classeModel->update($idclasse, $data)) {
                    header('Location: index.php?action=classes&success=1');
                    exit();
                } else {
                    $error = "Erreur lors de la modification.";
                }
            } else {
                $error = "Le niveau est obligatoire.";
            }
        }

        require_once __DIR__ . '/../views/classe/edit.php';
    }

    public function detail(string $idclasse): void {
        $classe = $this->classeModel->read($idclasse);
        if (!$classe) {
            header('Location: index.php?action=classes');
            exit();
        }
        require_once __DIR__ . '/../views/classe/detail.php';
    }

    public function delete(string $idclasse): void {
        try {
            if ($this->classeModel->delete($idclasse)) {
                header('Location: index.php?action=classes&success=1');
            } else {
                header('Location: index.php?action=classes&error=1');
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                header('Location: index.php?action=classes&error=foreign_key');
            } else {
                throw $e;
            }
        }
        exit();
    }
}
