<?php
require_once __DIR__ . '/../../db/connection.php';

$sql = "SELECT  uwagi.*,
                uczniowie.imie AS uczen_imie,
                uczniowie.nazwisko AS uczen_nazwisko,
                oddzialy.oddzial AS uczen_oddzial, 
                pracownicy.imie AS pracownik_imie, 
                pracownicy.nazwisko AS pracownik_nazwisko 
        FROM uwagi 
        LEFT JOIN uczniowie   ON uwagi.uczen_id = uczniowie.id 
        LEFT JOIN pracownicy  ON uwagi.pracownik_id = pracownicy.id
        INNER JOIN oddzialy   ON uczniowie.oddzial_id = oddzialy.id
        INNER JOIN szkoly     ON pracownicy.szkola_id = szkoly.id
        WHERE szkoly.id = :szkola_id";

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
    <title>Lista uwag</title>
</head>
<body>
    <h1>Lista uwag</h1>
    <a href="create.php">Dodaj nową uwagę</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Uczeń</th>
                <th>Typ uwagi</th>
                <th>Data</th>
                <th>Godzina</th>
                <th>Treść</th>
                <th>Nauczyciel</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($result)): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['uczen_imie']?> <?=$row['uczen_nazwisko']?> <?=$row['uczen_oddzial']?></td>
                        <td><?=$row['typ_uwagi']?></td>
                        <td><?=$row['data']?></td>
                        <td><?=$row['godzina']?></td>
                        <td><?=$row['tresc']?></td>
                        <td><?=$row['pracownik_imie']?> <?=$row['pracownik_nazwisko']?></td>
                        <td>
                            <a href="read.php?id=<?php echo $row['id']; ?>">Szczegóły</a> |
                            <a href="update.php?id=<?php echo $row['id']; ?>">Edytuj</a> |
                            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Czy na pewno usunąć?')">Usuń</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Brak danych lub błąd: <?php echo $result; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
