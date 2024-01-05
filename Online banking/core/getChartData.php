<?php
require_once '../controllers/baza.php';

function getRevenueData() {
    global $conn;

    $query = "SELECT datum_transakcije, provizija FROM transakcije";
    $result = $conn->query($query);

    $data = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'Provizija',
                'data' => [],
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
        ],
    ];

    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['datum_transakcije'];
        $data['datasets'][0]['data'][] = $row['provizija'];
    }

    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    echo json_encode(getRevenueData());
}
?>