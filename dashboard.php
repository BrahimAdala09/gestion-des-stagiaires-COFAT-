<?php
session_start();
include 'db_connection.php';
$title = "Dashboard";
include 'includes/header2.php';

$sql_total = "SELECT COUNT(*) as total FROM stagiaire";
$result_total = $conn->query($sql_total);
$total_stagiaires = $result_total->fetch_assoc()['total'];

$sql_status = "SELECT Etat, COUNT(*) as count FROM stagiaire GROUP BY Etat";
$result_status = $conn->query($sql_status);
$stagiaires_by_status = [];
while ($row = $result_status->fetch_assoc()) {
    $stagiaires_by_status[$row['Etat']] = $row['count'];
}

$sql_category = "SELECT categorie_de_stage.nom_categorie, COUNT(*) as count 
                 FROM stagiaire 
                 JOIN categorie_de_stage ON stagiaire.categorie_id = categorie_de_stage.categorie_id 
                 GROUP BY stagiaire.categorie_id";
$result_category = $conn->query($sql_category);
$stagiaires_by_category = [];
while ($row = $result_category->fetch_assoc()) {
    $stagiaires_by_category[$row['nom_categorie']] = $row['count'];
}

$sql_diplome = "SELECT diplome.diplome_type, COUNT(*) as count 
                FROM stagiaire 
                JOIN diplome ON stagiaire.diplome_id = diplome.diplome_id 
                GROUP BY stagiaire.diplome_id";
$result_diplome = $conn->query($sql_diplome);
$stagiaires_by_diplome = [];
while ($row = $result_diplome->fetch_assoc()) {
    $stagiaires_by_diplome[$row['diplome_type']] = $row['count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <div class="stat-box">
            <h2>Total Stagiaires</h2>
            <p><?php echo htmlspecialchars($total_stagiaires); ?></p>
        </div>
        <div class="stat-box">
            <h2>Stagiaires par État</h2>
            <canvas id="statusChart"></canvas>
        </div>
        <div class="stat-box">
            <h2>Stagiaires par Catégorie</h2>
            <canvas id="categoryChart"></canvas>
        </div>
        <div class="stat-box">
            <h2>Stagiaires par Diplôme</h2>
            <canvas id="diplomeChart"></canvas>
        </div>
    </div>

    <script>
        const statusData = {
            labels: <?php echo json_encode(array_keys($stagiaires_by_status)); ?>,
            datasets: [{
                label: 'Stagiaires par État',
                data: <?php echo json_encode(array_values($stagiaires_by_status)); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        const categoryData = {
            labels: <?php echo json_encode(array_keys($stagiaires_by_category)); ?>,
            datasets: [{
                label: 'Stagiaires par Catégorie',
                data: <?php echo json_encode(array_values($stagiaires_by_category)); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        const diplomeData = {
            labels: <?php echo json_encode(array_keys($stagiaires_by_diplome)); ?>,
            datasets: [{
                label: 'Stagiaires par Diplôme',
                data: <?php echo json_encode(array_values($stagiaires_by_diplome)); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        const statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'bar',
            data: statusData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const categoryChart = new Chart(document.getElementById('categoryChart'), {
            type: 'pie',
            data: categoryData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Stagiaires par Catégorie'
                    }
                }
            }
        });

        const diplomeChart = new Chart(document.getElementById('diplomeChart'), {
            type: 'pie',
            data: diplomeData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Stagiaires par Diplôme'
                    }
                }
            }
        });
    </script>
</body>
</html>
