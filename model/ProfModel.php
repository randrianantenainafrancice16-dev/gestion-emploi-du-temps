<?php
require_once "ConnexionBd.php";

class ProfModel {
    private ?PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): bool {
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM Professeur WHERE idprof = :id");
        $check->execute([':id' => $data['idProfesseur']]);
        if ($check->fetchColumn() > 0) return false;

        $sql = "INSERT INTO Professeur (idprof, Nom, Prenoms, Grade, email)
                VALUES (:id, :nom, :prenom, :grade, :email)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'     => $data['idProfesseur'],
            ':nom'    => $data['nomProfesseur'],
            ':prenom' => $data['prenomProfesseur'],
            ':grade'  => $data['gradeProfesseur'],
            ':email'  => $data['email'] ?? null
        ]);
    }

    public function read(string $id): ?array {
        $sql = "SELECT idprof, Nom, Prenoms, Grade, email FROM Professeur WHERE idprof = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function readAll(): array {
        $sql = "SELECT idprof, Nom, Prenoms, Grade, email FROM Professeur";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(string $id, array $data): bool {
        $sql = "UPDATE Professeur
                SET Nom = :nom, Prenoms = :prenom, Grade = :grade, email = :email
                WHERE idprof = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'    => $data['nomProfesseur'],
            ':prenom' => $data['prenomProfesseur'],
            ':grade'  => $data['gradeProfesseur'],
            ':email'  => $data['email'] ?? null,
            ':id'     => $id
        ]);
    }

    public function delete(string $id): bool {
        $sql = "DELETE FROM Professeur WHERE idprof = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function search(string $keyword): array {
        $sql = "SELECT idprof, Nom, Prenoms, Grade, email
                FROM Professeur
                WHERE Nom LIKE :keyword OR Prenoms LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}