<?php
require_once __DIR__ . '/../../db/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

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
        WHERE uwagi.id = :uwaga_id";

$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':uwaga_id' => $id]);

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
    <p><strong>ID:</strong> <?=$record['id']?></p>
    <p>
        <strong>Uczeń:</strong> 
        <?=$record['uczen_imie']?> <?=$record['uczen_nazwisko']?> <?=$record['uczen_oddzial']?>
    </p>
    <p><strong>Typ uwagi:</strong> <?=$record['typ_uwagi']?></p>
    <p><strong>Data:</strong> <?=$record['data']?></p>
    <p><strong>Godzina:</strong> <?=$record['godzina']?></p>
    <p><strong>Treść:</strong> <?php echo nl2br($record['tresc']); ?></p>
    <p>
        <strong>Pracownik:</strong> 
        <?=$record['pracownik_imie']?> <?=$record['pracownik_nazwisko']?>
    </p>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
