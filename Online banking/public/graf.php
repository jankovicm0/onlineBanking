<?php
include_once('../controllers/baza.php');

$query = "SELECT datum_transakcije, provizija FROM transakcije";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Greška u izvršavanju upita: ' . mysqli_error($conn));
}

$datumi = [];
$profitti = [];

while ($row = mysqli_fetch_assoc($result)) {
    $datumi[] = $row['datum_transakcije'];
    $profitti[] = floatval($row['provizija']);
}

echo json_encode(['datumi' => $datumi, 'profitti' => $profitti]);
?>