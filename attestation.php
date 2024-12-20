<?php

require('C:\Users\HP\Desktop\fpdf\fpdf.php');
include "db_connection.php";
$title= "attestation";
include "includes/header2.php";

if (isset($_GET['cin'])) {
    $cin = $conn->real_escape_string($_GET['cin']); 

    $sql = "
        SELECT s.cin, s.prenom, s.nom, s.etablissement, s.date_de_debut, s.date_de_fin, d.diplome_type AS diplome_type, st.stage_type, c.nom_categorie
        FROM stagiaire s
        JOIN diplome d ON s.diplome_id = d.diplome_id
        JOIN stage st ON s.stage_id = st.stage_id
        JOIN categorie_de_stage c ON s.categorie_id = c.categorie_id
        WHERE s.cin = '$cin'
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cin = $row['cin'];
        $surname = $row['prenom'];
        $name= $row['nom'];
        $institution = $row['etablissement'];
        $start_date = date('d/m/Y', strtotime($row['date_de_debut']));
        $end_date = date('d/m/Y', strtotime($row['date_de_fin']));
        $diploma = $row['diplome_type'];
        $stage_type = $row['stage_type'];
        $category_name = $row['nom_categorie'];

        $current_date = date('d/m/Y');

        class PDF extends FPDF {
            function Header() {
                $this->Image('images/logo.png', 10, 10, 50);
                $this->Ln(40); 
                $this->SetFont('Arial', 'B', 26);
                $this->Cell(0, 10, 'Attestation de Stage', 0, 1, 'C');
                $this->SetFont('Arial', 'I', 14);
                $this->Ln(20);
            }

            function Footer() {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 10);
                $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
            }

            function FancyTable($header, $data) {
                $this->SetFillColor(255, 255, 255);
                $this->SetTextColor(0);
                $this->SetDrawColor(0, 0, 0);
                $this->SetLineWidth(0.3);
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(95, 10, $header[0], 1, 0, 'C', true);
                $this->Cell(95, 10, $header[1], 1, 1, 'C', true);
                $this->SetFont('Arial', '', 12);
                foreach($data as $row) {
                    $this->Cell(95, 10, $row[0], 1);
                    $this->Cell(95, 10, $row[1], 1, 1);
                }
            }
        }

        
        ob_end_clean();

        $pdf = new PDF();
        $pdf->AddPage();
        
        $pdf->SetFont('Arial', '', 12);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, 'Stagiaire', 0, 1, 'C', true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);

        $data = [
            ['Nom & Prenom :', "$name $surname"],
            ['CIN :', "$cin"],
            ['Etablissement :', "$institution"],
            ['Diplome :', "$diploma"],
            ['Type de Stage :', "$stage_type"],
            ['Departement :', "$category_name"],
            ['Duree :', "Du $start_date au $end_date"]
        ];
        $header = ['Description', 'Details'];
        $pdf->FancyTable($header, $data);

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Remis à Wassila le ' . $current_date, 0, 1, 'R');
        $pdf->Ln(10);
        $pdf->Cell(0, 1, 'Signature:', 0, 1, 'R');

        $pdf->Output('D', 'attestation.pdf');
        exit; 
    } else {
        $message = "Aucun stagiaire trouvé avec le CIN: $cin";
    }
} else {
    $message = "Veuillez entrer un CIN.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générer Attestation</title>
    <link rel="stylesheet" href="css/styles_attestation.css">
    <link rel="stylesheet" href="css/styles_message.css">
</head>
<body>
    <div class="form-container">
        <form action="attestation.php" method="GET" target="_blank">
            <label for="cin">Entrer CIN:</label>
            <input type="text" id="cin" name="cin" required>
            <button type="submit">Générer Attestation</button>
        </form>
        
        <?php if (!empty($message)): ?>
            <div class="message error"><?php echo ($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
