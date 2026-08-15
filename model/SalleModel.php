<?php
require_once "ConnexionBd.php";

class SalleModel {
    private ?PDO $pdo;
    private ?int $idsalle;
    private ?string $design;
    private ?string $occupation;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getIdsalle(): ?int {
        return $this->idsalle;
    }

    public function getDesign(): ?string {
        return $this->design;
    }

    public function setDesign(string $design): void {
        $this->design = $design;
    }

    public function getOccupation(): ?string {
        return $this->occupation;
    }

    public function setOccupation(string $occupation): void {
        $this->occupation = $occupation;
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO SALLE (design, occupation) VALUES (:design, :occupation)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':design' => $data['design'],
            ':occupation' => $data['occupation'] ?? 'Libre'
        ]);
    }

    public function read(int $id): ?array {
        $sql = "SELECT * FROM SALLE WHERE idsalle = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $salle = $stmt->fetch(PDO::FETCH_ASSOC);
        return $salle ?: null;
    }

    public function readAll(): array {
        $sql = "SELECT * FROM SALLE";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE SALLE SET design = :design, occupation = :occupation WHERE idsalle = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':design' => $data['design'],
            ':occupation' => $data['occupation'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM SALLE WHERE idsalle = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Retourne la liste des idsalle occupées à une date donnée (pour l'AJAX du formulaire EDT)
    public function getOccupeesByDate(string $date): array {
        $sql = "SELECT DISTINCT idsalle FROM edt WHERE DATE(Date) = :date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':date' => $date]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idsalle');
    }

    public function searchSallesLibres(string $datetime): array {
        // $datetime arrive au format 'YYYY-MM-DDTHH:MM' (datetime-local)
        // On remplace le T par un espace pour obtenir un format DATETIME valide
        $datetimeSql = str_replace('T', ' ', $datetime) . ':00';

        // On cherche les salles qui n'ont aucun cours qui se chevauche avec ce créneau
        // (on considère qu'un cours dure 2h, donc on vérifie ±2h autour de l'heure choisie)
        $sql = "SELECT * FROM SALLE
                WHERE idsalle NOT IN (
                    SELECT idsalle FROM edt
                    WHERE Date = :datetime
                )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':datetime' => $datetimeSql]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
