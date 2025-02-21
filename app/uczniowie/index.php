<?php
require_once __DIR__ . "/../../db/connection.php";

$sql = "SELECT * FROM uczniowie WHERE szkola_id = :szkola_id";

$stmt = $connection->prepare($sql);
$params = [
    ':szkola_id' => 1
];
$result = fetchData($stmt, $params);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista uczniów</title>
</head>
<body>
    <h1>Lista uczniów</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Drugie imię</th>
                <th>Nazwisko</th>
                <th>Data urodzenia</th>
                <th>PESEL</th>
                <th>Kraj</th>
                <th>Miasto</th>
                <th>Ulica</th>
                <th>Email</th>
                <th>Nr telefonu</th>
                <th>Płeć</th>
                <th>Narodowość</th>
                <th>Kod pocztowy</th>
                <th>Nr domu</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($result)): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['imie']?></td>
                        <td><?=$row['imie2']?></td>
                        <td><?=$row['nazwisko']?></td>
                        <td><?=$row['data_ur']?></td>
                        <td><?=$row['pesel']?></td>
                        <td><?=$row['kraj']?></td>
                        <td><?=$row['miasto']?></td>
                        <td><?=$row['ulica']?></td>
                        <td><?=$row['email']?></td>
                        <td><?=$row['nr_tel']?></td>
                        <td><?=$row['plec']?></td>
                        <td><?=$row['narodowosc']?></td>
                        <td><?=$row['kod_pocztowy']?></td>
                        <td><?=$row['nr_domu']?></td>
                        <td>
                            <a href="read.php?id=<?=$row['id']?>">Szczegóły</a> |
                            <a href="update.php?id=<?=$row['id']?>">Edytuj</a> |
                            <a href="delete.php?id=<?=$row['id']?>" onclick="return confirm('Czy na pewno usunąć?')">Usuń</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="16">Brak danych lub błąd: <?php echo $result; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="create.php">Dodaj nowego ucznia</a>
</body>
</html>