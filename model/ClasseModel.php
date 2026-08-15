<?php
require_once 'ConnexionBd.php';

class ClasseModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO classe (idclasse, niveau) VALUES (:idclasse, :niveau)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idclasse' => $data['idclasse'],
            ':niveau'   => $data['niveau']
        ]);
    }

    public function read(string $idclasse): ?array {
        $sql = "SELECT * FROM classe WHERE idclasse = :idclasse";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idclasse' => $idclasse]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function readAll(): array {
        $sql = "SELECT * FROM classe";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(string $idclasse, array $data): bool {
        $sql = "UPDATE classe SET niveau = :niveau WHERE idclasse = :idclasse";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':niveau'   => $data['niveau'],
            ':idclasse' => $idclasse
        ]);
    }

    public function delete(string $idclasse): bool {
        $sql = "DELETE FROM classe WHERE idclasse = :idclasse";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':idclasse' => $idclasse]);
    }

    public function search(string $keyword): array {
        $sql = "SELECT * FROM classe
                WHERE idclasse LIKE :keyword
                OR niveau LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nombre de classes (idclasse) regroupées par niveau, pour l'histogramme du tableau de bord
    public function countByNiveau(): array {
        $sql = "SELECT niveau, COUNT(idclasse) AS nb
                FROM classe
                GROUP BY niveau
                ORDER BY niveau";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
