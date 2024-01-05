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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrol panel</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Dobrodošli na Administratorsku kontrolnu tablu</h1>
            <div class="tl">
                <a href="../models/korisnici.php">Pregled korisnika</a><br>
                <a href="../models/prikaziTransakcije.php">Prikazi transakcije</a><br>
                <a href="../views/prikazProvizije.html">Prikaz provizije</a><br>
                <a href="../views/prikazKorisnika.html">Prikaz registrovanih korisnika</a><br>
                <a href="../models/logout.php">Odjavite se</a>
            </div>
        </div>
    </div>
    <div class="container1">
        <div class="form-box1">
            <h1>Podesavanja korisnickih naloga</h1>
            <div class="tl">
                <a href="../views/promenaAktivnosti.html">Promena aktivnosti</a>
            </div>
        </div>
    </div>
</body>
</html>