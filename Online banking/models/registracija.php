<?php
session_start();

require_once '../controllers/baza.php';

$defaultUloga = 'klijent';
$defaultAktivnost = 'aktivan';
$defaultDatumOtvaranja = date("Y-m-d H:i:s");
$prefiks = '908';
$sufiks = '37';
$randomBroj = mt_rand(10000, 99999);
$brojRacuna = $prefiks . $randomBroj . $sufiks;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];
    $uloga = $_POST['uloga'] ?? $defaultUloga;
    $jmbg = $_POST['jmbg'];
    $adresa = $_POST['adresa'];
    $broj_telefona = $_POST['broj_telefona'];
    $hashedPassword = password_hash($lozinka, PASSWORD_DEFAULT);

    $upitProveraEmail = "SELECT COUNT(*) FROM korisnici WHERE email = ?";
    if ($stmtProveraEmail = $conn->prepare($upitProveraEmail)) {
        $stmtProveraEmail->bind_param('s', $email);
        $stmtProveraEmail->execute();
        $stmtProveraEmail->bind_result($brojEmaila);
        $stmtProveraEmail->fetch();
        $stmtProveraEmail->close();

        if ($brojEmaila > 0) {
            exit('E-mail adresa je već registrovana. Molimo pokušajte sa drugom e-mail adresom.');
        }
    }

    $upitProveraBroj = "SELECT COUNT(*) FROM korisnici WHERE broj_telefona = ?";
    if ($upitProveraBroj = $conn->prepare($upitProveraBroj)) {
        $upitProveraBroj->bind_param('s', $broj_telefona);
        $upitProveraBroj->execute();
        $upitProveraBroj->bind_result($brojTelefona);
        $upitProveraBroj->fetch();
        $upitProveraBroj->close();

        if ($brojTelefona > 0) {
            exit('Broj telefona je već registrovan. Molimo pokušajte sa drugom brojem.');
        }
    }

    $upitProveraJMBG = "SELECT COUNT(*) FROM korisnici WHERE jmbg = ?";
    if ($upitProveraJMBG = $conn->prepare($upitProveraJMBG)) {
        $upitProveraJMBG->bind_param('s', $jmbg);
        $upitProveraJMBG->execute();
        $upitProveraJMBG->bind_result($brojJMBG);
        $upitProveraJMBG->fetch();
        $upitProveraJMBG->close();

        if ($brojJMBG > 0) {
            exit('JMBG je već registrovan. Molimo pokušajte sa drugom JMBG-om.');
        }
    }

    $tip_racuna = $_POST['tip_racuna'];
    $valuta = $_POST['valuta'];
    $aktivnost_racuna = $_POST['aktivnost_racuna'] ?? $defaultAktivnost;
    $datum_otvaranja = $_POST['datum_otvaranja'] ?? $defaultDatumOtvaranja;
    $broj_racuna = $_POST['broj_racuna'] ?? $brojRacuna;

    $upitKorisnici = "INSERT INTO korisnici (ime, prezime, email, lozinka, uloga, jmbg, adresa, broj_telefona) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmtKorisnici = $conn->prepare($upitKorisnici)) {
        $stmtKorisnici->bind_param('ssssssss', $ime, $prezime, $email, $hashedPassword, $uloga, $jmbg, $adresa, $broj_telefona);
        $stmtKorisnici->execute();
        $stmtKorisnici->close();
    }

    $korisnikId = $conn->insert_id;

    $upitRacuni = "INSERT INTO racuni (id_korisnika, broj_racuna, stanje_racuna, tip_racuna, valuta, aktivnost_racuna, datum_otvaranja, poslednja_transakcija) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    if ($stmtRacuni = $conn->prepare($upitRacuni)) {
        $stanjeRacuna = 0;
        $stmtRacuni->bind_param('iiissss', $korisnikId, $broj_racuna, $stanjeRacuna, $tip_racuna, $valuta, $aktivnost_racuna, $datum_otvaranja);
        $stmtRacuni->execute();
        $stmtRacuni->close();
    }

    header('Location: ../models/dashboard.php');
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;600&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container">
        <div class="form-box">
        <p id="error-message" style="color: red;"></p>
            <h1>Registracija</h1>
            <form method="POST" action="../models/registracija.php" onsubmit="return validateForm()">
                <label for="ime">Ime:</label><br>
                <input type="text" name="ime" id="ime" placeholder="Unesite Vase ime" required><br>
                <p id="error-message-name" style="color: red;"></p>

                <label for="prezime">Prezime:</label><br>
                <input type="text" name="prezime" id="prezime" placeholder="Unesite Vase prezime" required><br>

                <label for="email">Email:</label><br>
                <input type="email" name="email" id="email" placeholder="Unesite Vas email" required><br>

                <label for="lozinka">Lozinka:</label><br>
                <input type="password" name="lozinka" id="lozinka" placeholder="Unesite Vasu lozinku" required><br>

                <label for="jmbg">JMBG:</label><br>
                <input type="text" name="jmbg" id="jmbg" placeholder="Unesite Vas JMBG" required><br>

                <label for="adresa">Adresa:</label><br>
                <input type="text" name="adresa" placeholder="Unesite adresu stanovanja" required><br>

                <label for="broj_telefona">Broj telefona:</label><br>
                    <span id="staticni_tekst">+381</span>
                    <input type="text" name="broj_telefona" id="broj_telefona" placeholder="Unesite ostatak broja" required><br>

                <div class="labeli">
                    <div id="lab1">
                        <label for="tip_racuna">Tip računa:</label><br>
                        <select name="tip_racuna" required>
                            <option value="Tekuci">Tekuci</option>
                            <option value="Devizni">Devizni</option>
                            <option value="Stedni">Stedni</option>
                        </select>
                    </div>
                    <div id="lab2">
                        <label for="valuta">Valuta:</label><br>
                        <select name="valuta" required>
                            <option value="RSD">RSD</option>
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                </div>
                <div class="btn-field">
                    <button type="submit">Registracija</button>
                </div>
                <p>Zaboravljena sifra? <a href="#">Klikni ovde!</a></p>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const ime = document.getElementById('ime').value;
            const prezime = document.getElementById('prezime').value;
            const email = document.getElementById('email').value;
            const lozinka = document.getElementById('lozinka').value;
            const jmbg = document.getElementById('jmbg').value;
            const broj_Telefona = document.getElementById('broj_telefona').value;

            if (!isValidName(ime) || !isValidName(prezime) || !isValidEmail(email) || !isValidLozinka(lozinka) || !isValidJMBG(jmbg) || !isValidBroj(broj_Telefona)) {
                document.getElementById('error-message').innerText = 'Molimo vas da ispravno popunite sva polja.';
                return false;
            }
            return true;
        }

        function isValidName(name) {
            const nameRegex = /^[a-zA-Z]{2,15}$/;
            return nameRegex.test(name);
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidLozinka(lozinka) {
            const lozinkaRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
            return lozinkaRegex.test(lozinka);
        }

        function isValidJMBG(jmbg) {
            const jmbgRegex = /^[0-9]{13}$/;
            return jmbgRegex.test(jmbg);
        }

        function isValidBroj(broj_Telefona) {
            const numberRegex = /^6\d{0,8}$/;
            return numberRegex.test(broj_Telefona);
        }
    </script>
</body>
</html>