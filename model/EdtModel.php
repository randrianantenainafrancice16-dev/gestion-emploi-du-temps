<?php
require_once "connexionBd.php";

class EdtModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO edt (idsalle, idprof, idclasse, cours, Date)
                VALUES (:idsalle, :idprof, :idclasse, :cours, :date)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idsalle'  => $data['idsalle'],
            ':idprof'   => $data['idprof'],
            ':idclasse' => $data['idclasse'],
            ':cours'    => $data['cours'],
            ':date'     => $data['date']
        ]);
    }

    public function read(int $idedt): ?array {
        $sql = "SELECT e.idedt AS id, e.Date AS date, e.*,
                       s.design AS salle_nom, p.Nom AS prof_nom, c.niveau AS classe_nom
                FROM edt e
                JOIN salle s ON s.idsalle = e.idsalle
                JOIN professeur p ON p.idprof = e.idprof
                JOIN classe c ON c.idclasse = e.idclasse
                WHERE e.idedt = :idedt";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idedt' => $idedt]);
        $edt = $stmt->fetch(PDO::FETCH_ASSOC);
        return $edt ?: null;
    }

    public function readAll(): array {
        $sql = "SELECT e.idedt AS id, e.Date AS date, e.*,
                       s.design AS salle_nom, p.Nom AS prof_nom, c.niveau AS classe_nom
                FROM edt e
                JOIN salle s ON s.idsalle = e.idsalle
                JOIN professeur p ON p.idprof = e.idprof
                JOIN classe c ON c.idclasse = e.idclasse
                ORDER BY e.Date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(int $idedt, array $data): bool {
        $sql = "UPDATE edt
                SET idsalle = :idsalle, idprof = :idprof, idclasse = :idclasse,
                    cours = :cours, Date = :date
                WHERE idedt = :idedt";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idsalle'  => $data['idsalle'],
            ':idprof'   => $data['idprof'],
            ':idclasse' => $data['idclasse'],
            ':cours'    => $data['cours'],
            ':date'     => $data['date'],
            ':idedt'    => $idedt
        ]);
    }

    public function delete(int $idedt): bool {
        $sql = "DELETE FROM edt WHERE idedt = :idedt";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':idedt' => $idedt]);
    }

    public function search(array $criteria): array {
        $sql = "SELECT e.idedt AS id, e.Date AS date, e.*,
                       s.design AS salle_nom, p.Nom AS prof_nom, c.niveau AS classe_nom
                FROM edt e
                JOIN salle s ON s.idsalle = e.idsalle
                JOIN professeur p ON p.idprof = e.idprof
                JOIN classe c ON c.idclasse = e.idclasse
                WHERE 1=1";

        $params = [];

        if (!empty($criteria['idsalle'])) {
            $sql .= " AND e.idsalle = :idsalle";
            $params[':idsalle'] = $criteria['idsalle'];
        }
        if (!empty($criteria['idprof'])) {
            $sql .= " AND e.idprof = :idprof";
            $params[':idprof'] = $criteria['idprof'];
        }
        if (!empty($criteria['idclasse'])) {
            $sql .= " AND e.idclasse = :idclasse";
            $params[':idclasse'] = $criteria['idclasse'];
        }
        if (!empty($criteria['dateDebut']) && !empty($criteria['dateFin'])) {
            $sql .= " AND e.Date BETWEEN :dateDebut AND :dateFin";
            $params[':dateDebut'] = $criteria['dateDebut'];
            $params[':dateFin']   = $criteria['dateFin'];
        }
        if (!empty($criteria['Date'])) {
            $sql .= " AND e.Date = :Date";
            $params[':Date'] = $criteria['Date'];
        }

        $sql .= " ORDER BY e.Date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readByClasse(string $idclasse): array {
        $sql = "SELECT e.idedt AS id, e.Date AS date, e.*,
                       s.design AS salle_nom, p.Nom AS prof_nom, c.niveau AS classe_nom
                FROM edt e
                JOIN salle s ON s.idsalle = e.idsalle
                JOIN professeur p ON p.idprof = e.idprof
                JOIN classe c ON c.idclasse = e.idclasse
                WHERE e.idclasse = :idclasse
                ORDER BY e.Date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idclasse' => $idclasse]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readBySemaine(string $dateDebut, string $dateFin): array {
        $sql = "SELECT e.idedt AS id, e.Date AS date, e.*,
                       s.design AS salle_nom, p.Nom AS prof_nom, c.niveau AS classe_nom
                FROM edt e
                JOIN salle s ON s.idsalle = e.idsalle
                JOIN professeur p ON p.idprof = e.idprof
                JOIN classe c ON c.idclasse = e.idclasse
                WHERE e.Date BETWEEN :dateDebut AND :dateFin
                ORDER BY e.Date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':dateDebut' => $dateDebut, ':dateFin' => $dateFin . ' 23:59:59']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nombre de cours par jour de la semaine (pour histogramme)
    public function countByJour(): array {
        $sql = "SELECT DAYOFWEEK(Date) AS jour_num,
                       CASE DAYOFWEEK(Date)
                           WHEN 2 THEN 'Lundi'
                           WHEN 3 THEN 'Mardi'
                           WHEN 4 THEN 'Mercredi'
                           WHEN 5 THEN 'Jeudi'
                           WHEN 6 THEN 'Vendredi'
                           WHEN 7 THEN 'Samedi'
                           ELSE 'Dimanche'
                       END AS label,
                       COUNT(*) AS nb
                FROM edt
                GROUP BY jour_num
                ORDER BY jour_num";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nombre de cours par classe (pour diagramme circulaire)
    public function countByClasse(): array {
        $sql = "SELECT c.niveau AS label, COUNT(e.idedt) AS nb
                FROM edt e
                JOIN classe c ON c.idclasse = e.idclasse
                GROUP BY e.idclasse, c.niveau
                ORDER BY nb DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nombre de cours par professeur (pour histogramme)
    public function countByProf(): array {
        $sql = "SELECT p.Nom AS label, COUNT(e.idedt) AS nb
                FROM edt e
                JOIN professeur p ON p.idprof = e.idprof
                GROUP BY e.idprof, p.Nom
                ORDER BY nb DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}