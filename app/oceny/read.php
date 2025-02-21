<?php
require_once '../../db/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT oceny.*, 
               dict_typy_ocen.ocena AS oceny_typ, 
               dict_przedmioty.nazwa AS oceny_przedmiot, 
               pracownicy.imie AS pracownik_imie, 
               pracownicy.nazwisko AS pracownik_nazwisko, 
               uczniowie.imie AS uczen_imie, 
               uczniowie.nazwisko AS uczen_nazwisko
        FROM oceny 
        JOIN dict_typy_ocen ON oceny.typ_oceny = dict_typy_ocen.id
        JOIN dict_przedmioty ON oceny.przedmiot_id = dict_przedmioty.id
        JOIN pracownicy ON oceny.pracownik_id = pracownicy.id
        JOIN uczniowie ON oceny.uczen_id = uczniowie.id
        WHERE oceny.id = :id";

$stmt = $connection->prepare($sql);
$stmt->execute([':id' => $id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$record) {
    echo "Brak rekordu o podanym ID.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Szczegóły oceny</title>
</head>
<body>
    <h1>Szczegóły oceny</h1>
    <p><strong>ID:</strong> <?= htmlspecialchars($record['id']); ?></p>
    <p><strong>Typ oceny:</strong> <?= htmlspecialchars($record['oceny_typ']); ?></p>
    <p><strong>Tytuł:</strong> <?= htmlspecialchars($record['tytul_oceny']); ?></p>
    <p><strong>Opis:</strong> <?= nl2br(htmlspecialchars($record['opis_oceny'])); ?></p>
    <p><strong>Przedmiot:</strong> <?= htmlspecialchars($record['oceny_przedmiot']); ?></p>
    <p><strong>Data wystawienia:</strong> <?= htmlspecialchars($record['data_wystawienia']); ?></p>
    <p>
        <strong>Pracownik:</strong> 
        <?= htmlspecialchars($record['pracownik_imie'] . ' ' . $record['pracownik_nazwisko']); ?>
    </p>
    <p>
        <strong>Uczeń:</strong> 
        <?= htmlspecialchars($record['uczen_imie'] . ' ' . $record['uczen_nazwisko']); ?>
    </p>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
