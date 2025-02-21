<?php
require_once __DIR__ . "/../../db/connection.php";

$stmt = $connection->prepare("SELECT * FROM szkoly");

$szkoly = fetchData($stmt);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($szkoly)): ?>
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
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Brak danych do wyświetlenia.</td>
            </tr>
        <?php endif; ?>
    </tbody>
    </table>
    <a href="create.php">Dodaj nową szkołę</a>
</body>
</html>
