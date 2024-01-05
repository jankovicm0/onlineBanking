<?php
require_once '../controllers/baza.php';

function getChartData() {
    global $conn;

    $query = "SELECT DATE(datum_otvaranja) AS datum_otvaranja, COUNT(*) AS broj_korisnika FROM korisnici GROUP BY datum_otvaranja";
    $result = $conn->query($query);

    if (!$result) {
        die("Greška pri izvršavanju upita: " . $conn->error);
    }

    $data = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'Registrovani korisnici',
                'data' => [],
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1
            ]
        ]
    ];

    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['datum_otvaranja'];
        $data['datasets'][0]['data'][] = $row['broj_korisnika'];
    }

    return $data;
}

$data = getChartData();

header('Content-Type: application/json');
echo json_encode($data);
?>