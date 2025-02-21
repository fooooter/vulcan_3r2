<?php
require_once(__DIR__ . "/../../db/connection.php");

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Brak ID oceny w URL.";
    exit();
}

// Zapytanie pobierające dane oceny
$query = "SELECT oceny.id, oceny.typ_oceny, oceny.tytul_oceny, oceny.opis_oceny, oceny.przedmiot_id, 
                 oceny.pracownik_id, oceny.uczen_id, oceny.data_wystawienia
          FROM oceny 
          WHERE oceny.id = :id";

$statement = $connection->prepare($query);
$params = [':id' => $id];

$ocena = fetchData($statement, $params);

if ($ocena instanceof DbError) {
    echo "Wystąpił błąd: " . $ocena->name;
    exit();
}

if (!$ocena) {
    echo "Nie znaleziono danych dla ID: " . htmlspecialchars($id);
    exit();
}

// Pobranie danych o pracownikach, przedmiotach i uczniach
$query = "SELECT id, imie, nazwisko FROM pracownicy JOIN oceny ON oceny.pracownik_id = pracownicy.id WHERE oceny.id =".$id;
$statement = $connection->prepare($query);
$pracownicy = fetchData($statement);

$query = "SELECT id, nazwa FROM przedmioty JOIN oceny ON oceny.przedmiot_id = przedmioty.id WHERE oceny.id =".$id;
$statement = $connection->prepare($query);
$przedmioty = fetchData($statement);

$query = "SELECT id, imie, imie2, nazwisko FROM uczniowie JOIN oceny ON oceny.pracownicy_id = pracownicy.id WHERE oceny.id =".$id;
$statement = $connection->prepare($query);
$uczniowie = fetchData($statement);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktualizuj ocenę</title>
    <link rel="stylesheet" href="../css/main.css"> 
</head>
<body>
    <h1 class="primary-text">Aktualizuj ocenę - ID: <?= htmlspecialchars($id); ?></h1>
    <form action="update_ocena.php?id=<?= htmlspecialchars($id) ?>" method="post">
        <label for="typ_oceny">Typ oceny</label>
        <select name="typ_oceny" id="typ_oceny">
            <?php foreach ($typy_ocen as $typ): ?>
                <option value="<?= $typ['id'] ?>" <?= isset($ocena['typ_oceny']) && $ocena['typ_oceny'] == $typ['id'] ? 'selected' : '' ?>>
                    <?= $typ['ocena'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tytul_oceny">Tytuł oceny</label>
        <input type="text" name="tytul_oceny" id="tytul_oceny" value="<?= htmlspecialchars($ocena['tytul_oceny'] ?? ''); ?>">

        <label for="opis_oceny">Opis oceny</label>
        <textarea name="opis_oceny" id="opis_oceny"><?= htmlspecialchars($ocena['opis_oceny'] ?? ''); ?></textarea>

        <label for="przedmiot_id">Przedmiot</label>
        <select name="przedmiot_id" id="przedmiot_id">
            <?php foreach ($przedmioty as $przedmiot): ?>
                <option value="<?= $przedmiot['id'] ?>" <?= isset($ocena['przedmiot_id']) && $ocena['przedmiot_id'] == $przedmiot['id'] ? 'selected' : '' ?>>
                    <?= $przedmiot['nazwa'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="pracownik_id">Nauczyciel</label>
        <select name="pracownik_id" id="pracownik_id">
            <?php foreach ($pracownicy as $pracownik): ?>
                <option value="<?= $pracownik['id'] ?>" <?= isset($ocena['pracownik_id']) && $ocena['pracownik_id'] == $pracownik['id'] ? 'selected' : '' ?>>
                    <?= $pracownik['imie'] ?> <?= $pracownik['nazwisko'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="uczen_id">Uczeń</label>
        <select name="uczen_id" id="uczen_id">
            <?php foreach ($uczniowie as $uczen): ?>
                <option value="<?= $uczen['id'] ?>" <?= isset($ocena['uczen_id']) && $ocena['uczen_id'] == $uczen['id'] ? 'selected' : '' ?>>
                    <?= $uczen['imie'] ?> <?= $uczen['nazwisko'] ?> (<?= $uczen['imie2'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="data_wystawienia">Data wystawienia</label>
        <input type="date" name="data_wystawienia" id="data_wystawienia" value="<?= htmlspecialchars($ocena['data_wystawienia'] ?? ''); ?>">

        <button type="submit">Aktualizuj ocenę</button>
    </form>

    <a class="btn btn-primary margin-medium" href="index.php">Powrót do listy</a>
</body>
</html>
