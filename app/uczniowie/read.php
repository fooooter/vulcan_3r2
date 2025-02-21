<?php
require_once __DIR__ . "/../../db/connection.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT uczniowie.*, oddzialy.oddzial 
        FROM uczniowie 
        LEFT JOIN oddzialy ON uczniowie.oddzial_id = oddzialy.id 
        WHERE uczniowie.id = :id";

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
    <title>Szczegóły ucznia</title>
</head>
<body>
    <h1>Szczegóły ucznia</h1>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($record['id']); ?></p>
    <p><strong>Imię:</strong> <?php echo htmlspecialchars($record['imie']); ?></p>
    <p><strong>Drugie imię:</strong> <?php echo htmlspecialchars($record['imie2'] ?? 'Brak'); ?></p>
    <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars($record['nazwisko']); ?></p>
    <p><strong>Data urodzenia:</strong> <?php echo htmlspecialchars($record['data_ur']); ?></p>
    <p><strong>PESEL:</strong> <?php echo htmlspecialchars($record['pesel']); ?></p>
    <p><strong>Kraj:</strong> <?php echo htmlspecialchars($record['kraj'] ?? 'Brak'); ?></p>
    <p><strong>Miasto:</strong> <?php echo htmlspecialchars($record['miasto'] ?? 'Brak'); ?></p>
    <p><strong>Ulica:</strong> <?php echo htmlspecialchars($record['ulica'] ?? 'Brak'); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($record['email']); ?></p>
    <p><strong>Numer telefonu:</strong> <?php echo htmlspecialchars($record['nr_tel']); ?></p>
    <p><strong>Płeć:</strong> <?php echo ($record['plec'] == 'M') ? 'Mężczyzna' : 'Kobieta'; ?></p>
    <p><strong>Narodowość:</strong> <?php echo htmlspecialchars($record['narodowosc'] ?? 'Brak'); ?></p>
    <p><strong>Kod pocztowy:</strong> <?php echo htmlspecialchars($record['kod_pocztowy'] ?? 'Brak'); ?></p>
    <p><strong>Numer domu:</strong> <?php echo htmlspecialchars($record['nr_domu'] ?? 'Brak'); ?></p>
    <p><strong>Oddział:</strong> <?php echo htmlspecialchars($record['oddzial'] ?? 'Brak'); ?></p>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>