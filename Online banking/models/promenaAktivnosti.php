<?php
session_start();

require_once '../controllers/baza.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: models/login.php');
    exit();
}

$korisnikId = $_SESSION['user_id'];

$upitInformacije = "SELECT uloga FROM korisnici WHERE id = ?";
if ($stmtInformacije = $conn->prepare($upitInformacije)) {
    $stmtInformacije->bind_param('i', $korisnikId);
    $stmtInformacije->execute();
    $stmtInformacije->bind_result($uloga);
    $stmtInformacije->fetch();
    $stmtInformacije->close();
}

if ($uloga != 'Administrator') {
    header('Location: ../models/dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $broj_racuna = $_POST['broj_racuna'];
    $nova_aktivnost = $_POST['nova_aktivnost'];

    $upit = "UPDATE racuni SET aktivnost_racuna = ? WHERE broj_racuna = ?";
    if ($stmt = $conn->prepare($upit)) {
        $stmt->bind_param('si', $nova_aktivnost, $broj_racuna);
        $stmt->execute();
        $stmt->close();

        echo "Aktivnost racuna $broj_racuna uspešno promenjena na $nova_aktivnost.";
    } else {
        echo "Došlo je do greške prilikom promene aktivnosti.";
    }
}
?>