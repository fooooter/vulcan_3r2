<?php
require_once __DIR__ . "/../../db/connection.php";

$sql = "SELECT id, oddzial FROM oddzialy";
$stmt = $connection->prepare($sql);
$oddzialy = fetchData($stmt);

if (!is_array($oddzialy)) {
    die("Błąd pobierania oddziałów");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        ':nazwisko' => $_POST['nazwisko'] ?? null,
        ':imie' => $_POST['imie'] ?? null,
        ':imie2' => $_POST['imie2'] ?? null,
        ':data_ur' => $_POST['data_ur'] ?? null,
        ':pesel' => $_POST['pesel'] ?? null,
        ':kraj' => $_POST['kraj'] ?? null,
        ':miasto' => $_POST['miasto'] ?? null,
        ':ulica' => $_POST['ulica'] ?? null,
        ':email' => $_POST['email'] ?? null,
        ':nr_tel' => $_POST['nr_tel'] ?? null,
        ':plec' => $_POST['plec'] ?? null,
        ':narodowosc' => $_POST['narodowosc'] ?? null,
        ':kod_pocztowy' => $_POST['kod_pocztowy'] ?? null,
        ':nr_domu' => $_POST['nr_domu'] ?? null,
        ':uzytk' => $_POST['uzytk'] ?? null,
        ':hash' => $_POST['hash'] ?? null,
        ':oddzial_id' => $_POST['oddzial_id'] ?? null,
        ':szkola_id' => 1
    ];

    if ($params[':imie'] && $params[':nazwisko'] && $params[':data_ur'] && $params[':pesel'] && $params[':email'] && $params[':nr_tel'] && $params[':oddzial_id'] && $params[':plec']) {
        $sql = "INSERT INTO uczniowie (nazwisko, imie, imie2, data_ur, pesel, kraj, miasto, ulica, email, nr_tel, plec, narodowosc, kod_pocztowy, nr_domu, uzytk, hash, oddzial_id, szkola_id) 
                VALUES (:nazwisko, :imie, :imie2, :data_ur, :pesel, :kraj, :miasto, :ulica, :email, :nr_tel, :plec, :narodowosc, :kod_pocztowy, :nr_domu, :uzytk, :hash, :oddzial_id, :szkola_id)";
        
        $stmt = $connection->prepare($sql);
        $result = fetchData($stmt, $params);

        if (is_array($result)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Błąd podczas dodawania ucznia.";
        }
    } else {
        $error = "Nie wypełniono wszystkich wymaganych pól.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj nowego ucznia</title>
</head>
<body>
    <h1>Dodaj nowego ucznia</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <?php 
        $fields = [
            'nazwisko' => 'Nazwisko',
            'imie' => 'Imię',
            'imie2' => 'Drugie imię',
            'data_ur' => 'Data urodzenia',
            'pesel' => 'PESEL',
            'kraj' => 'Kraj',
            'miasto' => 'Miasto',
            'ulica' => 'Ulica',
            'email' => 'E-mail',
            'nr_tel' => 'Numer telefonu',
            'narodowosc' => 'Narodowość',
            'kod_pocztowy' => 'Kod pocztowy',
            'nr_domu' => 'Numer domu',
            'uzytk' => 'Użytkownik',
            'hash' => 'Hash'
        ];
        foreach ($fields as $name => $label): ?>
            <?php if ($name === 'data_ur'): ?>
                <label for="<?= $name ?>"><?= $label ?>: </label>
                <input type="date" name="<?= $name ?>" id="<?= $name ?>" required><br><br>
            <?php else: ?>
                <label for="<?= $name ?>"><?= $label ?>: </label>
                <input type="text" name="<?= $name ?>" id="<?= $name ?>" required><br><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <label for="plec">Płeć:</label>
        <select name="plec" id="plec" required>
            <option value="K">Kobieta</option>
            <option value="M">Mężczyzna</option>
        </select><br><br>

        <label for="oddzial_id">Oddział:</label>
        <select name="oddzial_id" id="oddzial_id" required>
            <?php foreach ($oddzialy as $oddzial): ?>
                <option value="<?= $oddzial['id'] ?>"><?= $oddzial['oddzial'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Dodaj ucznia</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>