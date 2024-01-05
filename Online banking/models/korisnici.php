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

$brojKorisnikaPoStranici = 5;
$trenutnaStranica = isset($_GET['stranica']) ? $_GET['stranica'] : 1;
$offset = ($trenutnaStranica - 1) * $brojKorisnikaPoStranici;

$upitKorisnici = "SELECT korisnici.id, korisnici.ime, korisnici.prezime, racuni.broj_racuna, racuni.stanje_racuna FROM korisnici JOIN racuni ON korisnici.id = racuni.id_korisnika LIMIT $brojKorisnikaPoStranici OFFSET $offset";
$rezultatKorisnici = $conn->query($upitKorisnici);

$upitBrojKorisnika = "SELECT COUNT(*) AS broj_korisnika FROM korisnici";
$rezultatBrojKorisnika = $conn->query($upitBrojKorisnika);
$brojRedova = $rezultatBrojKorisnika->fetch_assoc()['broj_korisnika'];
$brojStranica = ceil($brojRedova / $brojKorisnikaPoStranici);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista korisnika</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <h1>Lista korisnika</h1>

    <table id="korisnici" border="1">
        <tr>
            <th>Id</th>
            <th>Ime</th>
            <th>Prezime</th>
            <th>Broj računa</th>
            <th>Stanje računa</th>
        </tr>
        <?php while ($red = $rezultatKorisnici->fetch_assoc()) : ?>
            <tr>
                <td><?= $red['id'] ?></td>
                <td><?= $red['ime'] ?></td>
                <td><?= $red['prezime'] ?></td>
                <td><?= $red['broj_racuna'] ?></td>
                <td><?= $red['stanje_racuna'] ?> dinara</td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <div id="stranice">
        <?php if ($trenutnaStranica >= 1) : ?>
            <a href="?stranica=<?php echo $trenutnaStranica + 1; ?>">Napred</a>
        <?php endif; ?>

    <?php for ($i = 1; $i <= $brojStranica; $i++) : ?>
        <a href="?stranica=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($trenutnaStranica < $brojStranica && $trenutnaStranica > 1) : ?>
            <a href="?stranica=<?php echo $trenutnaStranica - 1; ?>">Nazad</a>
        <?php endif; ?>
    </div>
    <button id="exportExcel">Preuzmi Excel</button>
    <script>
        document.getElementById('exportExcel').addEventListener('click', function () {
            exportTableToExcel('korisnici');
        });

        function exportTableToExcel(tableId) {
            const table = document.getElementById(tableId);
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Korisnici');
            XLSX.writeFile(wb, 'korisnici.xlsx');
        }
    </script>
    <a id="nazad" href="../models/administatorPanel.php">Nazad na kontrolnu tablu</a>
</body>
</html>

<?php
$rezultatKorisnici->close();
$rezultatBrojKorisnika->close();
$conn->close();
?>