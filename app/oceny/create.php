<?php
require_once __DIR__ . "/../../db/connection.php";

$query = "SELECT id, ocena FROM typy_ocen";
$statement = $connection->prepare($query);
$typy_ocen = fetchData($statement);

if ($typy_ocen instanceof DbError) {
    echo "Wystąpił błąd: " . $pracownicy->name;
    exit();
}

$query = "SELECT id, imie, nazwisko FROM pracownicy";
$statement = $connection->prepare($query);
$pracownicy = fetchData($statement);

if ($pracownicy instanceof DbError) {
    echo "Wystąpił błąd: " . $pracownicy->name;
    exit();
}

// Pobieranie danych o przedmiotach
$query = "SELECT id, nazwa FROM przedmioty";
$statement = $connection->prepare($query);
$przedmioty = fetchData($statement);

if ($przedmioty instanceof DbError) {
    echo "Wystąpił błąd: " . $przedmioty->name;
    exit();
}

// Pobieranie danych o uczniach
$query = "SELECT id, imie, imie2, nazwisko FROM uczniowie";
$statement = $connection->prepare($query);
$uczniowie = fetchData($statement);

if ($uczniowie instanceof DbError) {
    echo "Wystąpił błąd: " . $uczniowie->name;
    exit();
}

// Przetwarzanie formularza
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $typ_oceny = htmlentities($_POST['typ_oceny'] ?? null);
    $tytul_oceny = htmlentities( $_POST['tytul_oceny'] ?? null);
    $opis_oceny = htmlentities($_POST['opis_oceny'] ?? null);
    $przedmiot_id = htmlentities($_POST['przedmiot_id'] ?? null);
    $pracownik_id = htmlentities($_POST['pracownik_id'] ?? null);
    $uczen_id = htmlentities($_POST['uczen_id'] ?? null);

    // Pobieramy bieżącą datę i czas na serwerze
    $data_wystawienia = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    if ($typ_oceny && $tytul_oceny && $opis_oceny && $przedmiot_id && $pracownik_id && $uczen_id && $data_wystawienia) {
        $query = "INSERT INTO oceny (typ_oceny, tytul_oceny, opis_oceny, przedmiot_id, pracownik_id, uczen_id, data_wystawienia)
                  VALUES (:typ_oceny, :tytul_oceny, :opis_oceny, :przedmiot_id, :pracownik_id, :uczen_id, :data_wystawienia)";

        $params = [
            ':typ_oceny' => $typ_oceny,
            ':tytul_oceny' => $tytul_oceny,
            ':opis_oceny' => $opis_oceny,
            ':przedmiot_id' => $przedmiot_id,
            ':pracownik_id' => $pracownik_id,
            ':uczen_id' => $uczen_id,
            ':data_wystawienia' => $data_wystawienia
        ];

        $stmt = $connection->prepare($query);
        $result = $stmt->execute($params);

        if (!$result) {
            echo "Wystąpił błąd podczas wstawiania danych do bazy.";
            exit();
        }

        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj ocenę</title>
</head>
<body>
    <form action="" method="post">
        <label for="typ_oceny">Typ oceny</label>
        <select name="typ_oceny" id="typ_oceny">
            <?php foreach ($typy_ocen as $typ): ?>
                <option value="<?= $typ['id'] ?>"><?= $typ['ocena'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="tytul_oceny">Tytuł oceny</label>
        <input type="text" name="tytul_oceny" id="tytul_oceny">

        <label for="opis_oceny">Opis oceny</label>
        <textarea name="opis_oceny" id="opis_oceny"></textarea>

        <label for="przedmiot_id">Przedmiot</label>
        <select name="przedmiot_id" id="przedmiot_id">
            <?php foreach ($przedmioty as $przedmiot): ?>
                <option value="<?= $przedmiot['id'] ?>"><?= $przedmiot['nazwa'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="pracownik_id">Nauczyciel</label>
        <select name="pracownik_id" id="pracownik_id">
            <?php foreach ($pracownicy as $pracownik): ?>
                <option value="<?= $pracownik['id'] ?>"><?= $pracownik['imie'] ?> <?= $pracownik['nazwisko'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="uczen_id">Uczeń</label>
        <select name="uczen_id" id="uczen_id">
            <?php foreach ($uczniowie as $uczen): ?>
                <option value="<?= $uczen['id'] ?>"><?= $uczen['imie'] ?> <?= $uczen['nazwisko'] ?> (<?= $uczen['imie2'] ?>)</option>
            <?php endforeach; ?>
        </select>

        <!-- Usunięto pole 'data_wystawienia', ponieważ teraz jest automatycznie ustawiane w PHP -->

        <button type="submit">Dodaj ocenę</button>
    </form>
</body>
</html>
