<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="css/styles2.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/comfi_deconex.js"></script>
    
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
        </div>
        <nav class="menu">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="stagiaires.php"><i class="fas fa-user"></i> Stagiaires</a></li>
                <li><a href="attestation.php"><i class="fas fa-file"></i> Attestation</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-cog"></i> Parametre</a>
                    <div class="dropdown-content">
                        <a href="gerer_departement.php"><i class="fas fa-building"></i> Gérer Département</a>
                        <a href="gerer_diplome.php"><i class="fas fa-graduation-cap"></i> Gérer Diplôme</a>
                        <a href="gerer_stage.php"><i class="fas fa-briefcase"></i> Gérer type de stage</a>
                    </div>
                </li>
                <li><a href="logout.php" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
