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

$upitUkupnoKorisnika = "SELECT COUNT(*) AS broj_korisnika FROM korisnici";
$rezultat = $conn->query($upitUkupnoKorisnika);

header('Content-Type: application/json');

if ($rezultat && $row = $rezultat->fetch_assoc()) {
    $ukupnoKorisnika = $row['broj_korisnika'];
    echo json_encode(['broj_korisnika' => $ukupnoKorisnika]);
} else {
    echo json_encode(['error' => 'Nema registrovanih korisnika.']);
}
?>