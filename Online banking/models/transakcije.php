<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: models/login.php');
    exit();
}

require_once '../controllers/baza.php';

$korisnikId = $_SESSION['user_id'];

$upitTransakcije = "SELECT * FROM transakcije WHERE id_posiljaoca = ? OR id_primaoca = ?";
if ($stmtTransakcije = $conn->prepare($upitTransakcije)) {
    $stmtTransakcije->bind_param('ii', $korisnikId, $korisnikId);
    $stmtTransakcije->execute();
    $rezultatTransakcije = $stmtTransakcije->get_result();
    $stmtTransakcije->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje transakcije</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #a15b06;
        }

        th, td {
            padding: 10px;
            text-align: left;
            color: #a15b06;
        }
    </style>
</head>
<body>
    <h1>Moje transakcije</h1>

    <table>
        <thead>
            <tr id="transakcije">
                <th>Datum</th>
                <th>Posiljalac</th>
                <th>Primalac</th>
                <th>Iznos</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($redTransakcije = $rezultatTransakcije->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $redTransakcije['datum_transakcije'] . '</td>';
                echo '<td>' . $redTransakcije['racun_posiljaoca'] . '</td>';
                echo '<td>' . $redTransakcije['racun_primaoca'] . '</td>';
                echo '<td>' . $redTransakcije['iznos'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>