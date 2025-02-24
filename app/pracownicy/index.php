<?php
require_once __DIR__ . "/../../db/connection.php";

$sql = "SELECT * FROM pracownicy WHERE szkola_id = :szkola_id";

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
    <title>Lista pracowników</title>
</head>
<body>
    <h1>Lista pracowników</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Drugie imię</th>
                <th>Nazwisko</th>
                <th>PESEL</th>
                <th>Kraj</th>
                <th>Miasto</th>
                <th>Ulica</th>
                <th>Nr domu</th>
                <th>Kod pocztowy</th>
                <th>Narodowość</th>
                <th>Płeć</th>
                <th>Wykształcenie</th>
                <th>Data zatrudnienia</th>
                <th>Data zwolnienia</th>
                <th>Nr telefonu</th>
                <th>Email</th>
                <th>Zarobki</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($result) && is_array($result)): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?= !empty($row['id']) ? $row['id'] : '-' ?></td>
                        <td><?= !empty($row['imie']) ? $row['imie'] : '-' ?></td>
                        <td><?= !empty($row['imie2']) ? $row['imie2'] : '-' ?></td>
                        <td><?= !empty($row['nazwisko']) ? $row['nazwisko'] : '-' ?></td>
                        <td><?= !empty($row['pesel']) ? $row['pesel'] : '-' ?></td>
                        <td><?= !empty($row['kraj']) ? $row['kraj'] : '-' ?></td>
                        <td><?= !empty($row['miasto']) ? $row['miasto'] : '-' ?></td>
                        <td><?= !empty($row['ulica']) ? $row['ulica'] : '-' ?></td>
                        <td><?= !empty($row['nr_domu']) ? $row['nr_domu'] : '-' ?></td>
                        <td><?= !empty($row['kod_pocztowy']) ? $row['kod_pocztowy'] : '-' ?></td>
                        <td><?= !empty($row['narodowosc']) ? $row['narodowosc'] : '-' ?></td>
                        <td><?= !empty($row['plec']) ? $row['plec'] : '-' ?></td>
                        <td><?= !empty($row['wyksztalcenie']) ? $row['wyksztalcenie'] : '-' ?></td>
                        <td><?= !empty($row['data_zatr']) ? $row['data_zatr'] : '-' ?></td>
                        <td><?= !empty($row['data_zwo']) ? $row['data_zwo'] : '-' ?></td>
                        <td><?= !empty($row['nr_tel']) ? $row['nr_tel'] : '-' ?></td>
                        <td><?= !empty($row['email']) ? $row['email'] : '-' ?></td>
                        <td><?= !empty($row['zarobki']) ? $row['zarobki'] : '-' ?></td>
                        <td>
                            <a href="read.php?id=<?=$row['id']?>">Szczegóły</a> |
                            <a href="update.php?id=<?=$row['id']?>">Edytuj</a> |
                            <a href="delete.php?id=<?=$row['id']?>" onclick="return confirm('Czy na pewno usunąć?')">Usuń</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="19" style="text-align: center">Brak danych</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="create.php">Dodaj nowego pracownika</a>
</body>
</html>