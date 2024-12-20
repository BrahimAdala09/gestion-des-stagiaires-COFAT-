<?php
session_start();
include 'db_connection.php';
$title = "Gérer Stage";
include 'includes/header2.php';

$message = '';
$message_type = '';

// Handle adding a new stage
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['stage_type'])) {
    $stage_type = $conn->real_escape_string($_POST['stage_type']);

    // Check if stage type already exists
    $check_query = $conn->prepare("SELECT COUNT(*) FROM stage WHERE stage_type = ?");
    $check_query->bind_param("s", $stage_type);
    $check_query->execute();
    $check_query->bind_result($count);
    $check_query->fetch();
    $check_query->close();

    if ($count > 0) {
        $message = "Le type de stage existe déjà.";
        $message_type = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO stage (stage_type) VALUES (?)");
        $stmt->bind_param("s", $stage_type);

        if ($stmt->execute()) {
            $message = "Type de stage ajouté avec succès.";
            $message_type = 'success';
        } else {
            $message = "Erreur lors de l'ajout du type de stage: " . $stmt->error;
            $message_type = 'error';
        }

        $stmt->close();
    }
}

// Handle deleting a stage
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_query = $conn->prepare("DELETE FROM stage WHERE stage_id = ?");
    $delete_query->bind_param("i", $delete_id);

    if ($delete_query->execute()) {
        $message = "Type de stage supprimé avec succès.";
        $message_type = 'success';
    } else {
        $message = "Erreur lors de la suppression du type de stage: " . $delete_query->error;
        $message_type = 'error';
    }

    $delete_query->close();
}

// Fetch all stages
$sql_stages = "SELECT * FROM stage";
$result_stages = $conn->query($sql_stages);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/styles_gerer_diplome.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Gérer Stage</h2>
            <h3>Ajouter un nouveau type de stage</h3>
            <form action="gerer_stage.php" method="post">
                <label for="stage_type">Type de stage:</label>
                <input type="text" id="stage_type" name="stage_type" required>
                <button type="submit">Ajouter Stage</button>
            </form>
            <?php if (!empty($message)): ?>
                <p class="<?php echo $message_type; ?>"><?php echo $message; ?></p>
            <?php endif; ?>
        </div>

        <div class="list-section">
            <h3>Liste des Types de Stage</h3>
            <table>
                <thead>
                    <tr>
                        <th>Type de Stage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_stages->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['stage_type']; ?></td>
                            <td>
                                <form action="gerer_stage.php" method="get" style="display:inline;">
                                  <input type="hidden" name="delete_id" value="<?php echo $row['stage_id']; ?>">
                                  <button type="submit" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de stage?');">Supprimer</button>
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
