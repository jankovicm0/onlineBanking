<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../controllers/baza.php';

$korisnikId = $_SESSION['user_id'];

$upitRacuni = "SELECT stanje_racuna, broj_racuna, aktivnost_racuna FROM racuni WHERE id_korisnika = ?";
if ($stmtStanjeRacuna = $conn->prepare($upitRacuni)) {
    $stmtStanjeRacuna->bind_param('i', $korisnikId);
    $stmtStanjeRacuna->execute();
    $stmtStanjeRacuna->bind_result($stanjeRacuna, $brojRacuna, $aktivnost_racuna);
    $stmtStanjeRacuna->fetch();
    $stmtStanjeRacuna->close();
}

$upitInformacije = "SELECT ime, prezime, email, jmbg, adresa, broj_telefona, uloga FROM korisnici WHERE id = ?";
if ($stmtInformacije = $conn->prepare($upitInformacije)) {
    $stmtInformacije->bind_param('i', $korisnikId);
    $stmtInformacije->execute();
    $stmtInformacije->bind_result($ime, $prezime, $email, $jmbg, $adresa, $broj_telefona, $uloga);
    $stmtInformacije->fetch();
    $stmtInformacije->close();
}

if ($uloga == 'Administrator') {
    header('Location: ../models/administatorPanel.php');
    exit();
} else {
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-box">
    <h1>Dobrodošli na Vašu kontrolnu tablu</h1>

    <p>Ime: <?= $ime ?></p>
    <p>Prezime: <?= $prezime ?></p>
    <p>Email: <?= $email ?></p>
    <p>JMBG: <?= $jmbg ?></p>
    <p>Adresa: <?= $adresa ?></p>
    <p>Broj telefona: <?= $broj_telefona ?></p>
    <p>Stanje na računu: <?= $stanjeRacuna ?> dinara</p>
    <p>Broj racuna: <?= $brojRacuna ?></p>
    <p>Aktivnost racuna: <?= $aktivnost_racuna ?></p>
    <div class="tl">
        <a id="transfer" href="../core/transfer.php">Izvrši Transfer Novca</a><br>
        <a id="transakcije" href="../models/transakcije.php">Pregled transakcija</a><br>
        <a id="zNaloga" href="../views/zatvaranjeNaloga.html">Zatvaranje naloga</a><br>
        <a id="logout" href="../models/logout.php">Odjavite se</a>
    </div>
    </div>
    </div>
</body>
</html>