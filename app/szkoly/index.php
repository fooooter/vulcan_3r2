<?php

require_once(__DIR__ . "..\..\..\db\connection.php");

$stmt = $connection->prepare("SELECT * FROM szkoly");

$szkoly = fetchData($stmt);

?>
<!DOCTYPE html>
<html lang="pl">

<!-- <head>
    <?php //require_once(__DIR__ . "\..\layout\head.php");?>
</head> -->

<body>
    <h1>Wyświetlanie szkół</h1>
    
    <!-- Tabela wyświetlania szkół -->
    <table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Miasto</th>
            <th scope="col">Ulica</th>
            <th scope="col">Nr budynku</th>
            <th scope="col">Kod pocztowy</th>
            <th scope="col">Nazwa</th>
            <th scope="col">Rodzaj</th>
            <th scope="col">NIP</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($szkoly)): ?>
            <?php foreach ($szkoly as $row): ?>
                <tr>
                    <th scope="row"><?= $row['id'] ?></th>
                    <td><?= htmlspecialchars($row['miasto']) ?></td>
                    <td><?= htmlspecialchars($row['ulica']) ?></td>
                    <td><?= htmlspecialchars($row['nr_budynku']) ?></td>
                    <td><?= htmlspecialchars($row['kod_poczt']) ?></td>
                    <td><?= htmlspecialchars($row['nazwa']) ?></td>
                    <td><?= htmlspecialchars($row['rodzaj']) ?></td>
                    <td><?= htmlspecialchars($row['nip']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Brak danych do wyświetlenia.</td>
            </tr>
        <?php endif; ?>
    </tbody>
    </table>
</body>

</html>
