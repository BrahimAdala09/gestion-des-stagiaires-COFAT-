<?php
session_start();
include 'db_connection.php';
$title = "Ajouter un Stagiaire";
include "includes/header2.php";

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $date_de_naissance = $_POST['date_de_naissance'];
    $adress = $_POST['adress'];
    $categorie_id = $_POST['categorie_id'];
    $diplome_id = $_POST['diplome_id'];
    $stage_id = $_POST['stage_id'];
    $etablissement = $_POST['etablissement'];
    $date_de_debut = $_POST['date_de_debut'];
    $date_de_fin = $_POST['date_de_fin'];
    $Etat = $_POST['Etat'];

    if (strlen($cin) !== 8) {
        $message = "Le CIN doit contenir exactement 8 caractères.";
    } elseif (strlen($telephone) !== 8) {
        $message = "Le téléphone doit contenir exactement 8 caractères.";
    } else {
       
        $sql = "INSERT INTO stagiaire (cin, nom, prenom, email, telephone, date_de_naissance, adress, categorie_id, diplome_id, stage_id, etablissement, date_de_debut, date_de_fin, Etat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssssssssssss", $cin, $nom, $prenom, $email, $telephone, $date_de_naissance, $adress, $categorie_id, $diplome_id, $stage_id, $etablissement, $date_de_debut, $date_de_fin, $Etat);
            
            if ($stmt->execute()) {
                header("Location: stagiaires.php");
                exit();
            } else {
                $message = "Erreur lors de l'ajout du stagiaire : " . htmlspecialchars($conn->error);
            }

            $stmt->close();
        } else {
            $message = "Erreur lors de la préparation de la requête SQL.";
        }
        
        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Stagiaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles_ajouter.css">
    <link rel="stylesheet" href="css/styles_erreur.css">
    <script src="js/comfi_ajout.js"></script>
 
</head>
<body>
    <div class="form-container">
        <h2>Ajouter un Stagiaire</h2>
        <form action="ajouter.php" method="POST" onsubmit="return confirmSubmission();">
            <div class="form-group">
                <label for="cin">CIN :</label>
                <input type="text" id="cin" name="cin" placeholder="CIN" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone :</label>
                <input type="text" id="telephone" name="telephone" placeholder="Téléphone" required>
            </div>
            <div class="form-group">
                <label for="date_de_naissance">Date de Naissance :</label>
                <input type="date" id="date_de_naissance" name="date_de_naissance" required>
            </div>
            <div class="form-group">
                <label for="adress">Adresse :</label>
                <input type="text" id="adress" name="adress" placeholder="Adresse" required>
            </div>
            <div class="form-group">
                <label for="categorie_id">Département :</label>
                <select id="categorie_id" name="categorie_id" required>
                    <?php
                    $result = $conn->query("SELECT * FROM categorie_de_stage");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . ($row['categorie_id']) . '">' . ($row['nom_categorie']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="diplome_id">Diplôme :</label>
                <select id="diplome_id" name="diplome_id" required>
                    <?php
                    $result = $conn->query("SELECT * FROM diplome");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . ($row['diplome_id']) . '">' . ($row['diplome_type']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="stage_id">Stage :</label>
                <select id="stage_id" name="stage_id" required>
                    <?php
                    $result = $conn->query("SELECT * FROM stage");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . ($row['stage_id']) . '">' . ($row['stage_type']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="etablissement">Établissement :</label>
                <input type="text" id="etablissement" name="etablissement" placeholder="Établissement" required>
            </div>
            <div class="form-group">
                <label for="date_de_debut">Date de Début :</label>
                <input type="date" id="date_de_debut" name="date_de_debut" required>
            </div>
            <div class="form-group">
                <label for="date_de_fin">Date de Fin :</label>
                <input type="date" id="date_de_fin" name="date_de_fin" required>
            </div>
            <div class="form-group">
                <label for="Etat">État :</label>
                <select id="Etat" name="Etat" required>
                    <option value="à faire">À faire</option>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                    <option value="annulé">Annulé</option>
                </select>
            </div>
            <button type="submit">Ajouter</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message error"><?php echo ($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
