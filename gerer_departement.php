<?php
session_start();
include 'db_connection.php';
$title = "Gérer Catégorie";
include 'includes/header2.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nom_categorie'])) {
        $nom_categorie = $conn->real_escape_string($_POST['nom_categorie']);
        $check_query = $conn->prepare("SELECT COUNT(*) FROM categorie_de_stage WHERE nom_categorie = ?");
        $check_query->bind_param("s", $nom_categorie);
        $check_query->execute();
        $check_query->bind_result($count);
        $check_query->fetch();
        $check_query->close();

        if ($count > 0) {
            $message = "Le département existe déjà.";
            $message_type = 'error';
        } else {
           
            $stmt = $conn->prepare("INSERT INTO categorie_de_stage (nom_categorie) VALUES (?)");
            $stmt->bind_param("s", $nom_categorie);

            if ($stmt->execute()) {
                $message = "Département ajouté avec succès.";
                $message_type = 'success';
            } else {
                $message = "Erreur lors de l'ajout du département: " . $stmt->error;
                $message_type = 'error';
            }

            $stmt->close();
        }
    }

    if (isset($_POST['delete_id'])) {
        $delete_id = $conn->real_escape_string($_POST['delete_id']);
        $delete_query = $conn->prepare("DELETE FROM categorie_de_stage WHERE categorie_id = ?");
        $delete_query->bind_param("i", $delete_id);

        if ($delete_query->execute()) {
            $message = "Département supprimé avec succès.";
            $message_type = 'success';
        } else {
            $message = "Erreur lors de la suppression du département: " . $delete_query->error;
            $message_type = 'error';
        }

        $delete_query->close();
    }
}

$sql_categories = "SELECT * FROM categorie_de_stage";
$result_categories = $conn->query($sql_categories);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" href="css/styles_gerer_diplome.css">
    <link rel="stylesheet" href="css/styles_messages.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Gérer Département</h2>
            <h3>Ajouter un nouveau département</h3>
            <form action="gerer_departement.php" method="post">
                <label for="nom_categorie">Nom du département:</label>
                <input type="text" id="nom_categorie" name="nom_categorie" required>
                <button type="submit">Ajouter Département</button>
            </form>
            <?php if (!empty($message)): ?>
                <p class="message <?php echo ($message_type); ?>"><?php echo ($message); ?></p>
            <?php endif; ?>
        </div>

        <div class="list-section">
            <h3>Liste des Départements</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nom du Département</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_categories->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo ($row['nom_categorie']); ?></td>
                            <td>
                                <form action="gerer_departement.php" method="post" style="display:inline;">
                                  <input type="hidden" name="delete_id" value="<?php echo ($row['categorie_id']); ?>">
                                  <button type="submit" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département?');">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
