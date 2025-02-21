<?php
    require_once(__DIR__ . "/../../db/connection.php");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT dict_typy_ocen.*, szkoly.nazwa 
        FROM dict_typy_ocen 
        LEFT JOIN szkoly ON dict_typy_ocen.szkola_id = szkoly.id 
        WHERE dict_typy_ocen.id = :id";

$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':id' => $id]);

if (!is_array($result) || count($result) === 0) {
    echo "Brak rekordu o podanym ID.";
    exit;
}

$record = $result[0];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Szczegóły typu oceny</title>
</head>
<body>
    <h1>Szczegóły typu oceny</h1>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($record['id']); ?></p>
    <p><strong>Ocena:</strong> <?php echo htmlspecialchars($record['ocena']); ?></p>
    <p><strong>Wartość:</strong> <?php echo htmlspecialchars($record['wartosc'] ?? 'Brak'); ?></p>
    <p><strong>Szkoła:</strong> <?php echo htmlspecialchars($record['nazwa'] ?? 'Brak'); ?></p>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>