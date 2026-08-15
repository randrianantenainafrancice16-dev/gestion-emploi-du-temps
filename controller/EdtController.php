<?php
require_once __DIR__ . '/../Model/EdtModel.php';
require_once __DIR__ . '/../Model/ProfModel.php';
require_once __DIR__ . '/../Model/SalleModel.php';
require_once __DIR__ . '/../Model/ClasseModel.php';

class EdtController
{
    private EdtModel $EdtModel;
    private ProfModel $ProfModel;
    private SalleModel $SalleModel;
    private ClasseModel $ClasseModel;

    public function __construct()
    {
        $this->EdtModel = new EdtModel();
        $this->ProfModel = new ProfModel();
        $this->SalleModel = new SalleModel();
        $this->ClasseModel = new ClasseModel();
    }

    // Liste de tous les cours
    public function list(): void
    {
        $cours = $this->EdtModel->readAll();
        require_once __DIR__ . '/../views/edt/list.php';
    }

    // Ajouter un cours
    public function add(): void
    {
        $cours   = null;
        $profs   = $this->ProfModel->readAll();
        $salles  = $this->SalleModel->readAll();
        $classes = $this->ClasseModel->readAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'date'     => htmlspecialchars(trim($_POST['date'] ?? '')),
                'idprof'   => htmlspecialchars(trim($_POST['idprof'] ?? '')),
                'idsalle'  => htmlspecialchars(trim($_POST['idsalle'] ?? '')),
                'idclasse' => htmlspecialchars(trim($_POST['idclasse'] ?? '')),
                'cours'    => htmlspecialchars(trim($_POST['cours'] ?? ''))
            ];

            if (!empty($data['date']) && !empty($data['idprof']) && !empty($data['idsalle']) && !empty($data['idclasse'])) {
                if ($this->EdtModel->create($data)) {
                    // Mettre la salle en "Occupée" automatiquement
                    $this->SalleModel->update((int)$data['idsalle'], [
                        'design'     => $this->SalleModel->read((int)$data['idsalle'])['design'],
                        'occupation' => 'Occupée'
                    ]);
                    header('Location: index.php?action=edt&success=1');
                    exit();
                } else {
                    $error = "Erreur lors de l'ajout du cours.";
                }
            } else {
                $error = "Tous les champs sont obligatoires.";
            }
        }

        require_once __DIR__ . '/../views/edt/form.php';
    }

    // Modifier un cours (identifié par idedt)
    public function edit(int $idedt): void
    {
        $cours = $this->EdtModel->read($idedt);
        if (!$cours) {
            die("Emploi du temps introuvable.");
        }

        $profs   = $this->ProfModel->readAll();
        $salles  = $this->SalleModel->readAll();
        $classes = $this->ClasseModel->readAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'date'     => htmlspecialchars(trim($_POST['date'] ?? '')),
                'idprof'   => htmlspecialchars(trim($_POST['idprof'] ?? '')),
                'idsalle'  => htmlspecialchars(trim($_POST['idsalle'] ?? '')),
                'idclasse' => htmlspecialchars(trim($_POST['idclasse'] ?? '')),
                'cours'    => htmlspecialchars(trim($_POST['cours'] ?? ''))
            ];

            if (!empty($data['date']) && !empty($data['idprof']) && !empty($data['idsalle']) && !empty($data['idclasse'])) {
                if ($this->EdtModel->update($idedt, $data)) {
                    header('Location: index.php?action=edt&success=1');
                    exit();
                } else {
                    $error = "Erreur lors de la modification.";
                }
            } else {
                $error = "Tous les champs sont obligatoires.";
            }
        }

        require_once __DIR__ . '/../views/edt/form.php';
    }

    // Supprimer un cours (identifié par idedt)
    public function delete(int $idedt): void
    {
        // Récupérer la salle avant suppression pour la remettre à Libre
        $cours = $this->EdtModel->read($idedt);

        if ($this->EdtModel->delete($idedt)) {
            // Vérifier si la salle a encore d'autres cours avant de la libérer
            if ($cours) {
                $autresCours = $this->EdtModel->search(['idsalle' => $cours['idsalle']]);
                if (empty($autresCours)) {
                    $salle = $this->SalleModel->read((int)$cours['idsalle']);
                    if ($salle) {
                        $this->SalleModel->update((int)$cours['idsalle'], [
                            'design'     => $salle['design'],
                            'occupation' => 'Libre'
                        ]);
                    }
                }
            }
            header('Location: index.php?action=edt&success=1');
        } else {
            header('Location: index.php?action=edt&error=1');
        }
        exit();
    }

    // EDT filtré par classe
    public function parClasse(): void
    {
        $classes = $this->ClasseModel->readAll();
        $idclasseSel = $_GET['idclasse'] ?? '';
        $cours = $idclasseSel !== '' ? $this->EdtModel->readByClasse($idclasseSel) : [];

        require_once __DIR__ . '/../views/edt/par_classe.php';
    }

    // Vue semaine
    public function semaine(): void
    {
        $dateDebut = $_GET['date_debut'] ?? date('Y-m-d', strtotime('monday this week'));
        $dateFin   = date('Y-m-d', strtotime($dateDebut . ' +6 days'));

        $cours = $this->EdtModel->readBySemaine($dateDebut, $dateFin);

        require_once __DIR__ . '/../views/edt/semaine.php';
    }

    // Recherche
    public function search(): void
    {
        $criteria = [
            'idsalle'   => $_GET['idsalle'] ?? '',
            'idprof'    => $_GET['idprof'] ?? '',
            'idclasse'  => $_GET['idclasse'] ?? '',
            'dateDebut' => $_GET['dateDebut'] ?? '',
            'dateFin'   => $_GET['dateFin'] ?? '',
            'Date'      => $_GET['date'] ?? ''
        ];

        $cours = $this->EdtModel->search($criteria);

        require_once __DIR__ . '/../views/Edt/search.php';
    }

    // Convertit l'UTF-8 vers ISO-8859-1 pour FPDF (évite les caractères cassés)
    private function pdfText(string $text): string
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
    }

    // Génère le PDF de l'emploi du temps de la semaine (format grille)
    public function genererPDF(): void
    {
        $fpdfPath = __DIR__ . '/../lib/fpdf/fpdf.php';
        if (!file_exists($fpdfPath)) {
            die("Bibliothèque FPDF introuvable à l'emplacement : $fpdfPath");
        }
        require_once $fpdfPath;

        $dateDebut = $_GET['date_debut'] ?? date('Y-m-d', strtotime('monday this week'));
        $dateFin   = date('Y-m-d', strtotime($dateDebut . ' +6 days'));
        $donnees   = $this->EdtModel->readBySemaine($dateDebut, $dateFin);

        // Créneaux horaires
        $creneaux = ['08:00','10:00','12:00','14:00','16:00','18:00'];
        $labels   = ['08:00-10:00','10:00-12:00','12:00-14:00','14:00-16:00','16:00-18:00','18:00-20:00'];
        $jours    = ['Lu','Ma','Me','Je','Ve','Sa'];
        $joursMap = [1=>'Lu',2=>'Ma',3=>'Me',4=>'Je',5=>'Ve',6=>'Sa'];

        // Indexer les cours par [jour][créneau]
        $grid = [];
        foreach ($donnees as $row) {
            $dt    = new DateTime($row['Date']);
            $jour  = $joursMap[(int)$dt->format('N')] ?? null;
            $heure = $dt->format('H:i');
            if ($jour && in_array($heure, $creneaux)) {
                $grid[$jour][$heure][] = $row;
            }
        }

        // Mise en page PDF (paysage A4)
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetMargins(8, 8, 8);

        // Titre
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 8, $this->pdfText('Emploi du temps du ' .
            date('d/m/Y', strtotime($dateDebut)) . ' au ' .
            date('d/m/Y', strtotime($dateFin))), 0, 1, 'C');
        $pdf->Ln(2);

        // Dimensions colonnes
        $wJour = 12;
        $nbCreneaux = count($creneaux);
        $wCreneau = (287 - $wJour) / $nbCreneaux; // 287 = largeur utile A4 paysage

        // En-tête des créneaux
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(26, 35, 126);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell($wJour, 8, '', 1, 0, 'C', true);
        foreach ($labels as $label) {
            $pdf->Cell($wCreneau, 8, $this->pdfText($label), 1, 0, 'C', true);
        }
        $pdf->Ln();

        $pdf->SetTextColor(0, 0, 0);
        $hRow = 22; // hauteur de chaque ligne de jour

        // Lignes des jours
        foreach ($jours as $j) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(236, 239, 241);
            $pdf->Cell($wJour, $hRow, $j, 1, 0, 'C', true);

            foreach ($creneaux as $cr) {
                $x = $pdf->GetX();
                $y = $pdf->GetY();

                if (!empty($grid[$j][$cr])) {
                    $c = $grid[$j][$cr][0]; // premier cours du créneau

                    $pdf->SetFont('Arial', 'B', 7);
                    $content  = $this->pdfText($c['classe_nom']) . "\n";
                    $content .= $this->pdfText($c['cours']) . "\n";
                    $content .= $this->pdfText($c['salle_nom']) . "\n";
                    $content .= $this->pdfText($c['prof_nom']);

                    // Cadre de la cellule
                    $pdf->Cell($wCreneau, $hRow, '', 1);
                    $pdf->SetXY($x + 1, $y + 1);
                    $pdf->MultiCell($wCreneau - 2, 4.5, $content, 0, 'L');
                    $pdf->SetXY($x + $wCreneau, $y);
                } else {
                    $pdf->Cell($wCreneau, $hRow, '', 1);
                }
            }
            $pdf->Ln();
        }

        $pdf->Output('D', 'emploi_du_temps.pdf');
        exit();
    }
}
