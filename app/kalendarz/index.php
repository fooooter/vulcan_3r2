<?php
require_once __DIR__ . "/../../db/connection.php";

$szkola_id = $_GET['szkola_id'] ?? null;
$data = $_GET['data'] ?? null;

$query = "SELECT    kalendarz.szkola_id, 
                    kalendarz.data, 
                    kalendarz.dzientyg, 
                    kalendarz.nazwa, 
                    kalendarz.status 
          FROM kalendarz
          WHERE kalendarz.szkola_id = :szkola_id
            AND kalendarz.data = :data";

$statement = $connection->prepare($query);
$params = [
    ':szkola_id' => $szkola_id,
    ':data' => $data
];

$kalendarz = fetchData($statement, $params);

if ($kalendarz instanceof DbError) {
    echo 'Wystąpił błąd: ' . $kalendarz->name;
    exit();
}

if (!$kalendarz) {
    echo "Nie znaleziono danych dla Szkoła ID: " . htmlspecialchars($szkola_id) . " i Data: " . htmlspecialchars($data);
    exit();
}
var_dump($kalendarz);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły Kalendarza - <?= htmlspecialchars($data); ?></title>
    <link rel="stylesheet" href="css/main.css"> 
</head>
<body>
    <h1 class="primary-text">Szczegóły wpisu w kalendarzu</h1>
    <table class="w-100 margin-medium">
        <tbody>
            <tr>
                <th>Szkoła ID</th>
                <td><?= htmlspecialchars($kalendarz['szkola_id']); ?></td>
            </tr>
            <tr>
                <th>Data</th>
                <td><?= htmlspecialchars($kalendarz['data']); ?></td>
            </tr>
            <tr>
                <th>Dzień Tygodnia</th>
                <td><?= htmlspecialchars($kalendarz['dzientyg']); ?></td>
            </tr>
            <tr>
                <th>Nazwa</th>
                <td><?= htmlspecialchars($kalendarz['nazwa']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($kalendarz['status']); ?></td>
            </tr>
        </tbody>
    </table>
    <a class="btn btn-primary margin-medium" href="index.php">Powrót do listy</a>
</body>
</html>