<?php
session_start();

require_once '../controllers/baza.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iznos = $_POST['iznos'];
    $racunPrimaoca = $_POST['racun_primaoca'];
    $korisnikId = $_SESSION['user_id'];
    $svrha_uplate = $_POST['svrha_uplate'];
    $datum_transakcije = date("Y-m-d H:i:s");

    $upitBrojRacuna = "SELECT broj_racuna FROM racuni WHERE id = ?";
    if ($stmtBrojRacuna = $conn->prepare($upitBrojRacuna)) {
        $stmtBrojRacuna->bind_param('i', $korisnikId);
        $stmtBrojRacuna->execute();
        $stmtBrojRacuna->bind_result($brojRacunaPosiljaoca);
        $stmtBrojRacuna->fetch();
        $stmtBrojRacuna->close();
    }

    $proveraSredstava = "SELECT stanje_racuna, broj_racuna, aktivnost_racuna FROM racuni WHERE id_korisnika = ?";
    if ($stmtProvera = $conn->prepare($proveraSredstava)) {
        $stmtProvera->bind_param('i', $korisnikId);
        $stmtProvera->execute();
        $stmtProvera->bind_result($stanjeRacuna, $brojRacuna, $aktivnostRacuna);
        $stmtProvera->fetch();
        $stmtProvera->close();

        if ($stanjeRacuna < $iznos) {
            echo "<script>alert('Doslo je do greske');</script>";
            exit();
        }

        if ($iznos < 10){
            echo "<p>Iznos mora biti veci od 10 dinara.<p>";
            exit();
        }
        if($aktivnostRacuna === 'Zatvoren' || $aktivnostRacuna === 'Blokiran'){
            echo "Vas racun je zatvoren/blokiran";
            exit();
        }
    }

    
    $procenatProvizije = 0.01;
    $provizija = $iznos * $procenatProvizije;
    $upitTransfer = "UPDATE racuni SET stanje_racuna = stanje_racuna - $provizija - ? WHERE id_korisnika = ?";
    if ($stmtTransfer = $conn->prepare($upitTransfer)) {
        $stmtTransfer->bind_param('ii', $iznos, $korisnikId);
        $stmtTransfer->execute();
        $stmtTransfer->close();
    }

    $upitKorisnikPrimaoca = "SELECT id_korisnika FROM racuni WHERE broj_racuna = ?";
    if ($stmtKorisnikPrimaoca = $conn->prepare($upitKorisnikPrimaoca)) {
        $stmtKorisnikPrimaoca->bind_param('s', $racunPrimaoca);
        $stmtKorisnikPrimaoca->execute();
        $stmtKorisnikPrimaoca->bind_result($korisnikPrimaoca);
        $stmtKorisnikPrimaoca->fetch();
        $stmtKorisnikPrimaoca->close();
    }

    $upitAzuriranje = "UPDATE racuni SET stanje_racuna = stanje_racuna + ? WHERE id_korisnika = ?";
    if ($stmtAzuriranje = $conn->prepare($upitAzuriranje)) {
        $stmtAzuriranje->bind_param('ii', $iznos, $korisnikPrimaoca);
        $stmtAzuriranje->execute();
        $stmtAzuriranje->close();
    }

    $upitTransakcija = "INSERT INTO transakcije (id_posiljaoca, id_primaoca, iznos, svrha_uplate, datum_transakcije, racun_posiljaoca, racun_primaoca, provizija) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmtTransakcija = $conn->prepare($upitTransakcija)) {
        $stmtTransakcija->bind_param('iiissiii', $korisnikId, $korisnikPrimaoca, $iznos, $svrha_uplate, $datum_transakcije, $brojRacuna, $racunPrimaoca, $provizija);
        $stmtTransakcija->execute();
        $stmtTransakcija->close();
    }

    echo "Transfer uspešno izvršen.";
}else{
    echo "Transfer nije izvrsen.";
    $upitTransakcije = "INSERT INTO transakcije (status_transakcije) VALUES (Neuspesno)";
    if ($stmtTransakcija = $conn->prepare($upitTransakcija)) {
        $stmtTransakcija->bind_param('s', $status_transakcije);
        $stmtTransakcija->execute();
        $stmtTransakcija->close();
    }
}

$conn->close();
?>