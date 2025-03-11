<?php
require_once __DIR__ . "/../../db/connection.php";

$sql = "SELECT * FROM szkoly";

$stmt = $connection->prepare($sql);
$params = [];
$szkoly = fetchData($stmt, $params);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista szkół</title>
</head>
<body>
    <h1>Lista szkół</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Miasto</th>
                <th>Ulica</th>
                <th>Nr budynku</th>
                <th>Kod pocztowy</th>
                <th>Nazwa</th>
                <th>Rodzaj</th>
                <th>NIP</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($szkoly) && is_array($szkoly)): ?>
                <?php foreach ($szkoly as $row): ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['miasto']?></td>
                        <td><?=$row['ulica']?></td>
                        <td><?=$row['nr_budynku']?></td>
                        <td><?=$row['kod_poczt']?></td>
                        <td><?=$row['nazwa']?></td>
                        <td><?=$row['rodzaj']?></td>
                        <td><?=$row['nip']?></td>
                        <td>
                            <a href="read.php?id=<?=$row['id']?>">Szczegóły</a> |
                            <a href="update.php?id=<?=$row['id']?>">Edytuj</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center"><?=is_array($szkoly) || empty($szkoly) ? "Brak danych" : ("Błąd: " . htmlspecialchars($szkoly))?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="create.php">Dodaj nową szkołę</a>
</body>
</html>