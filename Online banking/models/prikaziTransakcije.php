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

$upitTransakcije = "SELECT t.id, t.racun_posiljaoca, t.racun_primaoca, t.iznos, t.datum_transakcije
                    FROM transakcije t
                    LEFT JOIN korisnici k_posiljalac ON t.racun_posiljaoca = k_posiljalac.id
                    LEFT JOIN korisnici k_primalac ON t.racun_primaoca = k_primalac.id";

$rezultatTransakcije = $conn->query($upitTransakcije);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sve transakcije</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <?php if ($rezultatTransakcije->num_rows > 0) : ?>
        <h1>Sve transakcije</h1>
        <table border="1" id="transakcije">
            <tr>
                <th>ID</th>
                <th>Posiljalac</th>
                <th>Primalac</th>
                <th>Iznos</th>
                <th>Datum</th>
            </tr>
            <?php while ($red = $rezultatTransakcije->fetch_assoc()) : ?>
                <tr>
                    <td><?= $red['id'] ?></td>
                    <td><?= $red['racun_posiljaoca'] ?? 'N/A' ?></td>
                    <td><?= $red['racun_primaoca'] ?? 'N/A' ?></td>
                    <td><?= $red['iznos'] ?></td>
                    <td><?= $red['datum_transakcije'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>Nema dostupnih transakcija.</p>
    <?php endif; ?>

    <br>
    <button id="exportExcel">Preuzmi Excel</button>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script>
        document.getElementById('exportExcel').addEventListener('click', function () {
            exportTableToExcel('transakcije');
        });

        function exportTableToExcel(tableId) {
            const table = document.getElementById(tableId);
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Transakcije');
            XLSX.writeFile(wb, 'transakcije.xlsx');
        }
    </script>
    <a id="nazad" href="../models/administatorPanel.php">Nazad na kontrolnu tablu</a>
</body>
</html>