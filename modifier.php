<?php
session_start();
include 'db_connection.php';
$title = "Modifier stagiaire";
include "includes/header2.php";

$cin = $_GET['cin']; 

$sql = "SELECT * FROM stagiaire WHERE cin = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cin);
$stmt->execute();
$result = $stmt->get_result();
$stagiaire = $result->fetch_assoc();

if (!$stagiaire) {
    echo "Stagiaire non trouvé!";
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (strlen($telephone) !== 8) {
        $message = "Le numéro de téléphone doit contenir exactement 8 caractères.";
    } else {
        $sql = "UPDATE stagiaire SET nom = ?, prenom = ?, email = ?, telephone = ?, date_de_naissance = ?, adress = ?, categorie_id = ?, diplome_id = ?, stage_id = ?, etablissement = ?, date_de_debut = ?, date_de_fin = ?, Etat = ? WHERE cin = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssss", $nom, $prenom, $email, $telephone, $date_de_naissance, $adress, $categorie_id, $diplome_id, $stage_id, $etablissement, $date_de_debut, $date_de_fin, $Etat, $cin);

        if ($stmt->execute()) {
            header("Location: stagiaires.php");
            exit();
        } else {
            $message = "Erreur lors de la modification du stagiaire : " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Stagiaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles_modifier.css">
    <script src="js/comfi_modif.js"></script>
    <script src="js/telephone.js"></script>
</head>
<body>
    <div class="form-container">
        <h2>Modifier un Stagiaire</h2>
        <form action="modifier.php?cin=<?php echo ($cin); ?>" method="POST" onsubmit="return validatePhoneNumber();">
            <div class="form-group">
                <label for="cin">CIN :</label>
                <input type="text" id="cin" name="cin" value="<?php echo ($stagiaire['cin']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo ($stagiaire['nom']); ?>" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo ($stagiaire['prenom']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?php echo ($stagiaire['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone :</label>
                <input type="text" id="telephone" name="telephone" value="<?php echo ($stagiaire['telephone']); ?>" required>
                <div id="phone-error" class="error-message"><?php echo ($message); ?></div>
            </div>
            <div class="form-group">
                <label for="date_de_naissance">Date de Naissance :</label>
                <input type="date" id="date_de_naissance" name="date_de_naissance" value="<?php echo ($stagiaire['date_de_naissance']); ?>" required>
            </div>
            <div class="form-group">
                <label for="adress">Adresse :</label>
                <input type="text" id="adress" name="adress" value="<?php echo ($stagiaire['adress']); ?>" required>
            </div>
            <div class="form-group">
                <label for="categorie_id">Département :</label>
                <select id="categorie_id" name="categorie_id" required>
                    <?php
                    $result = $conn->query("SELECT * FROM categorie_de_stage");
                    while ($row = $result->fetch_assoc()) {
                        $selected = $row['categorie_id'] == $stagiaire['categorie_id'] ? 'selected' : '';
                        echo '<option value="' . ($row['categorie_id']) . '" ' . $selected . '>' . ($row['nom_categorie']) . '</option>';
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
                        $selected = $row['diplome_id'] == $stagiaire['diplome_id'] ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['diplome_id']) . '" ' . $selected . '>' . ($row['diplome_type']) . '</option>';
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
                        $selected = $row['stage_id'] == $stagiaire['stage_id'] ? 'selected' : '';
                        echo '<option value="' . ($row['stage_id']) . '" ' . $selected . '>' . ($row['stage_type']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="etablissement">Établissement :</label>
                <input type="text" id="etablissement" name="etablissement" value="<?php echo ($stagiaire['etablissement']); ?>" required>
            </div>
            <div class="form-group">
                <label for="date_de_debut">Date de Début :</label>
                <input type="date" id="date_de_debut" name="date_de_debut" value="<?php echo ($stagiaire['date_de_debut']); ?>" required>
            </div>
            <div class="form-group">
                <label for="date_de_fin">Date de Fin :</label>
                <input type="date" id="date_de_fin" name="date_de_fin" value="<?php echo ($stagiaire['date_de_fin']); ?>" required>
            </div>
            <div class="form-group">
                <label for="Etat">État :</label>
                <select id="Etat" name="Etat" required>
                    <option value="à faire" <?php if($stagiaire['Etat'] == 'à faire') echo 'selected'; ?>>À faire</option>
                    <option value="en cours" <?php if($stagiaire['Etat'] == 'en cours') echo 'selected'; ?>>En cours</option>
                    <option value="terminé" <?php if($stagiaire['Etat'] == 'terminé') echo 'selected'; ?>>Terminé</option>
                    <option value="annulé" <?php if($stagiaire['Etat'] == 'annulé') echo 'selected'; ?>>Annulé</option>
                </select>
            </div>
            <button type="submit">Modifier</button>
        </form>
      
    </div>
</body>
</html>
