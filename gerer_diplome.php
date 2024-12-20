<?php
session_start();
include 'db_connection.php';
$title = "Gérer Diplôme";
include 'includes/header2.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['diplome_type'])) {
        $type_diplome = $conn->real_escape_string($_POST['diplome_type']);
        $check_query = $conn->prepare("SELECT COUNT(*) FROM diplome WHERE diplome_type = ?");
        $check_query->bind_param("s", $type_diplome);
        $check_query->execute();
        $check_query->bind_result($count);
        $check_query->fetch();
        $check_query->close();

        if ($count > 0) {
            $message = "Le diplôme existe déjà.";
            $message_type = 'error';
        } else {
            $stmt = $conn->prepare("INSERT INTO diplome (diplome_type) VALUES (?)");
            $stmt->bind_param("s", $type_diplome);

            if ($stmt->execute()) {
                $message = "Diplôme ajouté avec succès.";
                $message_type = 'success';
            } else {
                $message = "Erreur lors de l'ajout du diplôme: " . $stmt->error;
                $message_type = 'error';
            }

            $stmt->close();
        }
    }

    if (isset($_POST['delete_id'])) {
        $delete_id = $conn->real_escape_string($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM diplome WHERE diplome_id = ?");
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            $message = "Diplôme supprimé avec succès.";
            $message_type = 'success';
        } else {
            $message = "Erreur lors de la suppression du diplôme: " . $stmt->error;
            $message_type = 'error';
        }

        $stmt->close();
    }
}

$sql_diplome = "SELECT * FROM diplome";
$result_diplome = $conn->query($sql_diplome);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/styles_gerer_diplome.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Gérer Diplôme</h2>
            <h3>Ajouter un nouveau diplôme</h3>
            <form action="gerer_diplome.php" method="post">
                <label for="diplome_type">Type de diplôme:</label>
                <input type="text" id="diplome_type" name="diplome_type" required>
                <button type="submit">Ajouter Diplôme</button>
            </form>
            <?php if (!empty($message)): ?>
                <p class="<?php echo ($message_type); ?>"><?php echo ($message); ?></p>
            <?php endif; ?>
        </div>

        <div class="list-section">
            <h3>Liste des Diplômes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Type de Diplôme</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_diplome->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo ($row['diplome_type']); ?></td>
                            <td>
                                <form action="gerer_diplome.php" method="post" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['diplome_id']; ?>">
                                    <button type="submit" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce diplôme?');">Supprimer</button>
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
