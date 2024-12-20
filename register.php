<?php
include 'db_connection.php';
$title = "Register";
include 'includes/header.php';
include 'includes/footer.php';

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($nom) || empty($prenom) || empty($password)) {
        $message = "All fields are required!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admin (username, nom, prenom, password) VALUES (?, ?, ?, ?)");

        if ($stmt === false) {
            $message = "Failed to prepare the SQL statement.";
        } else {
            $stmt->bind_param("ssss", $username, $nom, $prenom, $hashed_password);

            try {
                if ($stmt->execute()) {
                    $message = "User registered successfully!";
                } else {
                    $message = "This account is registered!";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                
                    $message = "Username already exists. Please choose a different username.";
                } else {
                    $message = "An error occurred: " . $e->getMessage();
                }
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles_message.css">
</head>
<body>
<div class="register-container">
    <h2>Inscription</h2>
    <form action="register.php" method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prenom" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">S'inscrire</button>
        <p><?php echo $message; ?></p> 
    </form>
</div>

</body>
</html>
