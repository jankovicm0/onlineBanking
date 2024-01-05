<?php
session_start();

require_once '../controllers/baza.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: models/login.php');
    exit();
}

$korisnikId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_aktivnost = $_POST['nova_aktivnost'];

    $upit = "UPDATE racuni SET aktivnost_racuna = ? WHERE id_korisnika = ?";
    if ($stmt = $conn->prepare($upit)) {
        $stmt->bind_param('si', $nova_aktivnost, $korisnikId);
        $stmt->execute();
        $stmt->close();

        echo "Aktivnost racuna uspešno promenjena na $nova_aktivnost.";
    } else {
        echo "Došlo je do greške prilikom promene aktivnosti.";
    }
}
?>