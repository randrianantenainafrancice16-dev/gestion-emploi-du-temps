<?php
require_once __DIR__ . '/../Model/ProfModel.php';
require_once __DIR__ . '/../Model/EdtModel.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ProfesseurController {
    private ProfModel $ProfesseurModel;
    private EdtModel  $EdtModel;

    public function __construct() {
        $this->ProfesseurModel = new ProfModel();
        $this->EdtModel        = new EdtModel();
    }

    public function index(): void {
        if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
            $profs = $this->ProfesseurModel->search(trim($_GET['keyword']));
        } else {
            $profs = $this->ProfesseurModel->readAll();
        }
        require_once __DIR__ . '/../views/Professeur/index.php';
    }

    public function create(): void {
        $prof = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idProfesseur'     => htmlspecialchars(trim($_POST['idprof'] ?? '')),
                'nomProfesseur'    => htmlspecialchars(trim($_POST['nom'] ?? '')),
                'prenomProfesseur' => htmlspecialchars(trim($_POST['prenoms'] ?? '')),
                'gradeProfesseur'  => htmlspecialchars(trim($_POST['grade'] ?? '')),
                'email'            => htmlspecialchars(trim($_POST['email'] ?? ''))
            ];

            if (!empty($data['idProfesseur']) && !empty($data['nomProfesseur'])) {
                try {
                    if ($this->ProfesseurModel->create($data)) {
                        header("Location: index.php?action=professeurs&success=create");
                        exit();
                    } else {
                        $error = "Cet ID existe déjà. Choisissez un autre ID.";
                    }
                } catch (PDOException $e) {
                    $error = "Erreur : " . $e->getMessage();
                }
            } else {
                $error = "L'ID et le nom sont obligatoires.";
            }
        }
        require_once __DIR__ . '/../views/Professeur/form.php';
    }

    public function edit(string $id): void {
        $prof = $this->ProfesseurModel->read($id);
        if (!$prof) die("Erreur : Professeur introuvable.");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nomProfesseur'    => htmlspecialchars(trim($_POST['nom'] ?? '')),
                'prenomProfesseur' => htmlspecialchars(trim($_POST['prenoms'] ?? '')),
                'gradeProfesseur'  => htmlspecialchars(trim($_POST['grade'] ?? '')),
                'email'            => htmlspecialchars(trim($_POST['email'] ?? ''))
            ];

            if (!empty($data['nomProfesseur'])) {
                if ($this->ProfesseurModel->update($id, $data)) {
                    header("Location: index.php?action=professeurs&success=update");
                    exit();
                } else {
                    $error = "Erreur lors de la mise à jour.";
                }
            } else {
                $error = "Le nom ne peut pas être vide.";
            }
        }
        require_once __DIR__ . '/../views/Professeur/form.php';
    }

    public function delete(string $id): void {
        try {
            if ($this->ProfesseurModel->delete($id)) {
                header("Location: index.php?action=professeurs&success=delete");
            } else {
                header("Location: index.php?action=professeurs&error=delete");
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                header("Location: index.php?action=professeurs&error=foreign_key");
            } else {
                throw $e;
            }
        }
        exit();
    }

    // Envoyer l'EDT par email au professeur
    public function envoyerEmail(string $id): void {
        $prof = $this->ProfesseurModel->read($id);
        if (!$prof) die("Professeur introuvable.");

        if (empty($prof['email'])) {
            header("Location: index.php?action=professeurs&error=no_email");
            exit();
        }

        // Récupérer tous les cours du professeur
        $cours = $this->EdtModel->search(['idprof' => $id]);

        // Construire le corps de l'email en HTML
        $lignes = '';
        foreach ($cours as $c) {
            $date = date('d/m/Y H:i', strtotime($c['Date']));
            $lignes .= "<tr>
                <td style='padding:8px;border:1px solid #ddd'>{$date}</td>
                <td style='padding:8px;border:1px solid #ddd'>{$c['cours']}</td>
                <td style='padding:8px;border:1px solid #ddd'>{$c['classe_nom']}</td>
                <td style='padding:8px;border:1px solid #ddd'>{$c['salle_nom']}</td>
            </tr>";
        }

        $corps = "
        <html><body style='font-family:Arial,sans-serif;color:#333'>
        <h2 style='color:#1a237e'>Emploi du Temps — {$prof['Nom']} {$prof['Prenoms']}</h2>
        <p>Bonjour {$prof['Prenoms']} {$prof['Nom']},</p>
        <p>Voici votre emploi du temps :</p>
        <table style='border-collapse:collapse;width:100%'>
            <thead>
                <tr style='background:#1a237e;color:#fff'>
                    <th style='padding:8px'>Date</th>
                    <th style='padding:8px'>Cours</th>
                    <th style='padding:8px'>Classe</th>
                    <th style='padding:8px'>Salle</th>
                </tr>
            </thead>
            <tbody>{$lignes}</tbody>
        </table>
        <p style='margin-top:20px;color:#666;font-size:12px'>
            Ce message a été envoyé automatiquement par le système EDT Universitaire.
        </p>
        </body></html>";

        $sujet = "Votre emploi du temps — EDT Universitaire";

        // ─── Envoi via PHPMailer + SMTP (remplace mail()) ───────────────
        $mail = new PHPMailer(true);

        // ─── DEBUG TEMPORAIRE : à retirer une fois le problème résolu ───
        $mail->SMTPDebug   = 2;                 // niveau de détail (2 = infos serveur + client)
        $mail->Debugoutput = 'error_log';       // écrit tout dans le log PHP au lieu de l'écran

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   =      'francice920@gmail.com'; // ← à remplacer
            $mail->Password   = 'yuyhrikncjdnrafx'; // ← à remplacer (16 caractères, PAS le mot de passe Gmail normal)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Expéditeur / destinataire
            $mail->setFrom('francice920@gmail.com', 'EDT Universitaire'); // ← même email que Username
            $mail->addAddress($prof['email'], $prof['Nom'] . ' ' . $prof['Prenoms']);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body    = $corps;

            $mail->send();
            header("Location: index.php?action=professeurs&success=email");
        } catch (Exception $e) {
            // Log l'erreur réelle pour le débogage (visible dans error_log PHP)
            error_log("Erreur envoi email PHPMailer : " . $mail->ErrorInfo);
            header("Location: index.php?action=professeurs&error=email");
        }
        exit();
    }
}