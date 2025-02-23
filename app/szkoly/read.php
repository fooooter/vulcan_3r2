<?php
require_once __DIR__ . "/../../db/connection.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM szkoly WHERE id = :id";

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
    <title>Szczegóły szkoły</title>
</head>
<body>
    <h1>Szczegóły szkoły</h1>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($record['id']); ?></p>
    <p><strong>Miasto:</strong> <?php echo htmlspecialchars($record['miasto']); ?></p>
    <p><strong>Ulica:</strong> <?php echo htmlspecialchars($record['ulica'] ?? 'Brak'); ?></p>
    <p><strong>Numer budynku:</strong> <?php echo htmlspecialchars($record['nr_budynku'] ?? 'Brak'); ?></p>
    <p><strong>Kod pocztowy:</strong> <?php echo htmlspecialchars($record['kod_poczt'] ?? 'Brak'); ?></p>
    <p><strong>Nazwa:</strong> <?php echo htmlspecialchars($record['nazwa']); ?></p>
    <p><strong>Rodzaj:</strong> <?php echo htmlspecialchars($record['rodzaj'] ?? 'Brak'); ?></p>
    <p><strong>NIP:</strong> <?php echo htmlspecialchars($record['nip'] ?? 'Brak'); ?></p>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
