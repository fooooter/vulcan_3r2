<?php
require_once '../../db/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT uwagi.*, 
               uczniowie.nazwisko AS uczen_nazwisko, 
               pracownicy.nazwisko AS pracownik_nazwisko 
        FROM uwagi 
        LEFT JOIN uczniowie ON uwagi.uczen_id = uczniowie.id 
        LEFT JOIN pracownicy ON uwagi.pracownik_id = pracownicy.id 
        WHERE uwagi.id = :id";

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
    <title>Szczegóły uwagi</title>
</head>
<body>
    <h1>Szczegóły uwagi</h1>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($record['id']); ?></p>
    <p>
        <strong>Uczeń:</strong> 
        <?php echo htmlspecialchars($record['uczen_nazwisko']); ?> 
        (ID: <?php echo htmlspecialchars($record['uczen_id']); ?>)
    </p>
    <p><strong>Typ uwagi:</strong> <?php echo htmlspecialchars($record['typ_uwagi']); ?></p>
    <p><strong>Data:</strong> <?php echo htmlspecialchars($record['data']); ?></p>
    <p><strong>Godzina:</strong> <?php echo htmlspecialchars($record['godzina']); ?></p>
    <p><strong>Treść:</strong> <?php echo nl2br(htmlspecialchars($record['tresc'])); ?></p>
    <p>
        <strong>Pracownik:</strong> 
        <?php echo htmlspecialchars($record['pracownik_nazwisko']); ?> 
        (ID: <?php echo htmlspecialchars($record['pracownik_id']); ?>)
    </p>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
