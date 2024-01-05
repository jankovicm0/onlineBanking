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

$upitUkupnaProvizija = "SELECT SUM(provizija) AS ukupna_provizija FROM transakcije";
$rezultat = $conn->query($upitUkupnaProvizija);

header('Content-Type: application/json');

if ($rezultat && $row = $rezultat->fetch_assoc()) {
    $ukupnaProvizija = $row['ukupna_provizija'];
    echo json_encode(['ukupna_provizija' => $ukupnaProvizija]);
} else {
    echo json_encode(['error' => 'Nema transakcija sa provizijama.']);
}
?>