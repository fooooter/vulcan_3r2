<?php
require_once __DIR__ . "/../../db/connection.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT pracownicy.*, szkoly.nazwa AS szkola_nazwa
        FROM pracownicy 
        LEFT JOIN szkoly ON pracownicy.szkola_id = szkoly.id 
        WHERE pracownicy.id = :id";

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
    <title>Szczegóły pracownika</title>
</head>
<body>
    <h1>Szczegóły pracownika</h1>
    <p><strong>ID:</strong> <?php echo htmlspecialchars($record['id']); ?></p>
    <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars($record['nazwisko']); ?></p>
    <p><strong>Imię:</strong> <?php echo htmlspecialchars($record['imie']); ?></p>
    <p><strong>Drugie imię:</strong> <?php echo htmlspecialchars($record['imie2'] ?? 'Brak'); ?></p>
    <p><strong>PESEL:</strong> <?php echo htmlspecialchars($record['pesel']); ?></p>
    <p><strong>Miasto:</strong> <?php echo htmlspecialchars($record['miasto'] ?? 'Brak'); ?></p>
    <p><strong>Ulica:</strong> <?php echo htmlspecialchars($record['ulica'] ?? 'Brak'); ?></p>
    <p><strong>Numer domu:</strong> <?php echo htmlspecialchars($record['nr_domu'] ?? 'Brak'); ?></p>
    <p><strong>Kod pocztowy:</strong> <?php echo htmlspecialchars($record['kod_pocztowy'] ?? 'Brak'); ?></p>
    <p><strong>Kraj:</strong> <?php echo htmlspecialchars($record['kraj'] ?? 'Brak'); ?></p>
    <p><strong>Narodowość:</strong> <?php echo htmlspecialchars($record['narodowosc'] ?? 'Brak'); ?></p>
    <p><strong>Zarobki:</strong> <?php echo htmlspecialchars($record['zarobki'] ?? 'Brak'); ?></p>
    <p><strong>Płeć:</strong> <?php echo ($record['plec'] == 'M') ? 'Mężczyzna' : 'Kobieta'; ?></p>
    <p><strong>Wykształcenie:</strong> <?php echo htmlspecialchars($record['wyksztalcenie'] ?? 'Brak'); ?></p>
    <p><strong>Data zatrudnienia:</strong> <?php echo htmlspecialchars($record['data_zatr']); ?></p>
    <p><strong>Data zwolnienia:</strong> <?php echo htmlspecialchars($record['data_zwo'] ?? 'Brak'); ?></p>
    <p><strong>Numer telefonu:</strong> <?php echo htmlspecialchars($record['nr_tel']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($record['email']); ?></p>
    <p><strong>Użytkownik:</strong> <?php echo htmlspecialchars($record['uzytk'] ?? 'Brak'); ?></p>
    <p><strong>Hash:</strong> <?php echo htmlspecialchars($record['hash'] ?? 'Brak'); ?></p>
    <p><strong>Szkoła:</strong> <?php echo htmlspecialchars($record['szkola_nazwa'] ?? 'Brak'); ?></p>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>