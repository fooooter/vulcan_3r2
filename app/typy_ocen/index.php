<?php 
require_once(__DIR__ . "/../../db/connection.php");

$sql = "SELECT * FROM dict_typy_ocen";

$stmt = $connection->prepare($sql);
// $params = [
//     ':szkola_id' => 1
// ];
$result = fetchData($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Typy ocen</h1> 
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ocena</th>
                <th>Wartość</th>
                <th>ID Szkoły</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($result)): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['ocena']?></td>
                        <td><?=$row['wartosc']?></td>
                        <td><?=$row['szkola_id']?></td>
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
    <a href="szkola.php">Dodaj nowy typ oceny</a>
</body>
</html>