<?php
require_once(__DIR__ . "../../../db/connection.php");

$id = $_GET['id'] ?? null;

$query = "SELECT egzaminy.szkola_id         AS      'szkola_id',
                 egzaminy.odzial_id         AS      'odzial_id',
                 egzaminy.nr_zdaj           AS      'nr_zdaj',
                 egzaminy.dopuszczony       AS      'dopuszczony',
                 egzaminy.kwalifikacja      AS      'kwalifikacja',
                 egzaminy.typ_egzaminu      AS      'typ_egzaminu',
                 egz_spec.id                AS      'id',
                 egz_spec.uczen_id          AS      'uczen_id',
                 egz_spec.etykieta_egz_id   AS      'etykieta_egz_id',
                 egz_spec.ilosc_pkt         AS      'ilosc_pkt',
                 egz_spec.termin            AS      'termin'
          FROM egzaminy 
          JOIN egz_spec ON egz_spec.etykieta_egz_id = egzaminy.id
          WHERE egz_spec.id = :id"; 

$statement = $connection->prepare($query);
$params = [':id' => $id];

$egzaminy = fetchData($statement, $params);

if ($egzaminy instanceof DbError) {
    echo "Wystąpił błąd: " . $egzaminy->name;
    exit();
}

if (!$egzaminy) {
    echo "Nie znaleziono danych dla ID: " . htmlspecialchars($id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły ID: <?= htmlspecialchars($id); ?></title>
    <link rel="stylesheet" href="../css/main.css"> 
</head>
<body>
    <h1 class="primary-text">Szczegóły egzaminu</h1>
    <table class="w-100 margin-medium">
        <tbody>
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($egzaminy['id']); ?></td>
            </tr>
            <tr>
                <th>ID ucznia</th>
                <td><?= htmlspecialchars($egzaminy['uczen_id']); ?></td>
            </tr>
            <tr>
                <th>Ilość punktów</th>
                <td><?= htmlspecialchars($egzaminy['ilosc_pkt']); ?></td>
            </tr>
            <tr>
                <th>Termin</th>
                <td><?= htmlspecialchars($egzaminy['termin']); ?></td>
            </tr>
            <tr>
                <th>ID ETYKIETA</th>
                <td><?= htmlspecialchars($egzaminy['etykieta_egz_id']); ?></td>
            </tr>
            <tr>
                <th>Szkoła ID</th>
                <td><?= htmlspecialchars($egzaminy['szkola_id']); ?></td>
            </tr>
            <tr>
                <th>Oddział ID</th>
                <td><?= htmlspecialchars($egzaminy['odzial_id']); ?></td>
            </tr>
            <tr>
                <th>Numer Zdającego</th>
                <td><?= htmlspecialchars($egzaminy['nr_zdaj']); ?></td>
            </tr>
            <tr>
                <th>Dopuszczony</th>
                <td><?= htmlspecialchars($egzaminy['dopuszczony']); ?></td>
            </tr>
            <tr>
                <th>Kwalifikacja</th>
                <td><?= htmlspecialchars($egzaminy['kwalifikacja']); ?></td>
            </tr>
            <tr>
                <th>Typ Egzaminu</th>
                <td><?= htmlspecialchars($egzaminy['typ_egzaminu']); ?></td>
            </tr>
        </tbody>
    </table>
    <a class="btn btn-primary margin-medium" href="index.php">Powrót do listy</a>
</body>
</html>
