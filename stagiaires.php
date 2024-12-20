<?php
session_start();
include 'db_connection.php';
$title = "Stagiaires";
include 'includes/header2.php';


if (isset($_POST['delete_stagiaire'])) {
    $id_stagiaire = $_POST['id_stagiaire'];

    $sql_delete = "DELETE FROM stagiaire WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id_stagiaire);

    if ($stmt->execute()) {
        header("Location: stagiaires.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du stagiaire : " . $conn->error;
    }
    $stmt->close();
}

$search_query = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $search_query = " WHERE cin LIKE '%$search%' 
                        OR nom LIKE '%$search%' 
                        OR prenom LIKE '%$search%' 
                        OR etablissement LIKE '%$search%' 
                        OR date_de_debut LIKE '%$search%' 
                        OR date_de_fin LIKE '%$search%' 
                        OR Etat LIKE '%$search%' 
                        OR categorie_de_stage.nom_categorie LIKE '%$search%' 
                        OR stage.stage_type LIKE '%$search%'
                        OR diplome.diplome_type LIKE '%$search%'";
                        
}


$sql = "SELECT stagiaire.*, categorie_de_stage.nom_categorie, diplome.diplome_type, stage.stage_type 
        FROM stagiaire 
        LEFT JOIN categorie_de_stage ON stagiaire.categorie_id = categorie_de_stage.categorie_id
        LEFT JOIN diplome ON stagiaire.diplome_id = diplome.diplome_id
        LEFT JOIN stage ON stagiaire.stage_id = stage.stage_id" . $search_query;

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Stagiaires</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles_stagiaires.css">
    <script src="js/comfi_supp.js"></script>
</head>
<body>
    <div class="container">
        <div class="actions">
            <a href="ajouter.php" class="add"><i class="fas fa-plus"></i> Ajouter</a>
            <form class="search-form" method="GET" action="stagiaires.php">
                <input type="text" name="search" placeholder="Rechercher...">
                <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
            </form>
        </div>
        <h1>Liste des Stagiaires</h1>
        <table>
            <thead>
                <tr>
                    <th>CIN</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Date de Naissance</th>
                    <th>Adresse</th>
                    <th>Département</th>
                    <th>Type de diplôme</th>
                    <th>Type de Stage</th>
                    <th>Établissement</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . ($row["cin"]) . "</td>";
                        echo "<td>" . ($row["nom"]) . "</td>";
                        echo "<td>" . ($row["prenom"]) . "</td>";
                        echo "<td>" . ($row["email"]) . "</td>";
                        echo "<td>" . ($row["telephone"]) . "</td>";
                        echo "<td>" . ($row["date_de_naissance"]) . "</td>";
                        echo "<td>" . ($row["adress"]) . "</td>";
                        echo "<td>" . ($row["nom_categorie"]) . "</td>";
                        echo "<td>" . ($row["diplome_type"]) . "</td>";
                        echo "<td>" . ($row["stage_type"]) . "</td>";
                        echo "<td>" . ($row["etablissement"]) . "</td>";
                        echo "<td>" . ($row["date_de_debut"]) . "</td>";
                        echo "<td>" . ($row["date_de_fin"]) . "</td>";
                        echo "<td>" . ($row["Etat"]) . "</td>";
                        echo "<td class='actions'>
                                <a href='modifier.php?cin=" . ($row["cin"]) . "' class='edit'><i class='fas fa-pen'></i></a>
                                <form class='delete-form' method='POST' style='display: inline;' onsubmit='return confirmDeletion();'>
                                    <input type='hidden' name='id_stagiaire' value='" . ($row["id"]) . "'>
                                    <button type='submit' name='delete_stagiaire' class='delete'><i class='fas fa-trash-alt'></i></button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14'>Aucun résultat trouvé</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
