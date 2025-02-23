<?php
require_once __DIR__ . "/../../db/connection.php";

$query = "SELECT egzaminy.szkola_id         AS      'szkola_id',
                 egzaminy.oddzial_id        AS     'oddzial_id',
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
          ";

$statement = $connection->prepare($query);

$egzaminy = fetchData($statement);

if ($egzaminy instanceof DbError) {
    echo "Wystąpił błąd: " . $egzaminy->name;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egzaminy</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <h1>Lista egzaminów</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID ucznia</th>
                <th>Ilość punktów</th>
                <th>Termin</th>
                <th>ID ETYKIETA</th>
                <th>Szkoła ID</th>
                <th>Oddział ID</th>
                <th>Numer Zdającego</th>
                <th>Dopuszczony</th>
                <th>Kwalifikacja</th>
                <th>Typ Egzaminu</th>
                <th>Szczegóły</th>
                <th>Aktualizuj</th> 
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($egzaminy) && is_array($egzaminy)): ?>
                <?php foreach ($egzaminy as $rekord): ?>
                    <tr>
                        <td><?= htmlspecialchars($rekord['id']); ?></td>
                        <td><?= htmlspecialchars($rekord['uczen_id']); ?></td>
                        <td><?= htmlspecialchars($rekord['ilosc_pkt']); ?></td>
                        <td><?= htmlspecialchars($rekord['termin']); ?></td>
                        <td><?= htmlspecialchars($rekord['etykieta_egz_id']); ?></td>
                        <td><?= htmlspecialchars($rekord['szkola_id']); ?></td>
                        <td><?= htmlspecialchars($rekord['oddzial_id']); ?></td>
                        <td><?= htmlspecialchars($rekord['nr_zdaj']); ?></td>
                        <td><?= htmlspecialchars($rekord['dopuszczony']); ?></td>
                        <td><?= htmlspecialchars($rekord['kwalifikacja']); ?></td>
                        <td><?= htmlspecialchars($rekord['typ_egzaminu']); ?></td>
                        <td><a class="btn btn-accent" href="read.php?id=<?= $rekord['id'] ?>">Szczegóły</a></td>
                        <td><a class="btn btn-secondary" href="update.php?id=<?= $rekord['id'] ?>">Aktualizuj</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <td colspan="13" style="text-align: center"><?=is_array($result) || empty($result) ? "Brak danych" : ("Błąd: " . htmlspecialchars($result))?></td>
            <?php endif; ?>
        </tbody>
    </table>

    <a class="btn btn-primary" href="create_egz.php">Dodaj egzamin</a>
    <a class="btn btn-primary" href="create_ety.php">Dodaj etykietę</a>
</body>
</html>
