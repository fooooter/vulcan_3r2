<?php
require_once __DIR__ . "/../../db/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        ':nazwisko' => $_POST['nazwisko'] ?? null,
        ':imie' => $_POST['imie'] ?? null,
        ':imie2' => $_POST['imie2'] ?? null,  // Drugie imię może być puste
        ':pesel' => $_POST['pesel'] ?? null,
        ':kraj' => $_POST['kraj'] ?? null,
        ':miasto' => $_POST['miasto'] ?? null,
        ':ulica' => $_POST['ulica'] ?? null,
        ':nr_domu' => $_POST['nr_domu'] ?? null,
        ':kod_pocztowy' => $_POST['kod_pocztowy'] ?? null,
        ':narodowosc' => $_POST['narodowosc'] ?? null,
        ':plec' => $_POST['plec'] ?? null,
        ':wyksztalcenie' => $_POST['wyksztalcenie'] ?? null,
        ':data_zatr' => $_POST['data_zatr'] ?? null,
        ':nr_tel' => $_POST['nr_tel'] ?? null,
        ':email' => $_POST['email'] ?? null,
        ':zarobki' => $_POST['zarobki'] ?? null,
        ':uzytk' => $_POST['uzytk'] ?? null,
        ':hash' => $_POST['hash'] ?? null,
        ':szkola_id' => 1
    ];

    $dataZwo = $_POST['data_zwo'] ?? null;
    if (!empty($dataZwo)) {
        $params[':data_zwo'] = $dataZwo;
    }

    $sql = "INSERT INTO pracownicy (nazwisko, imie, imie2, pesel, kraj, miasto, ulica, nr_domu, kod_pocztowy, narodowosc, plec, wyksztalcenie, data_zatr, nr_tel, email, zarobki, uzytk, hash, szkola_id" . 
           (!empty($dataZwo) ? ", data_zwo" : "") . ") VALUES (:nazwisko, :imie, :imie2, :pesel, :kraj, :miasto, :ulica, :nr_domu, :kod_pocztowy, :narodowosc, :plec, :wyksztalcenie, :data_zatr, :nr_tel, :email, :zarobki, :uzytk, :hash, :szkola_id" . 
           (!empty($dataZwo) ? ", :data_zwo" : "") . ")";
    
    $stmt = $connection->prepare($sql);
    $result = fetchData($stmt, $params);

    if (is_array($result)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Błąd podczas dodawania pracownika.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj nowego pracownika</title>
</head>
<body>
    <h1>Dodaj nowego pracownika</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <?php 
        $fields = [
            'nazwisko' => 'Nazwisko',
            'imie' => 'Imię',
            'imie2' => 'Drugie imię',
            'pesel' => 'PESEL',
            'kraj' => 'Kraj',
            'miasto' => 'Miasto',
            'ulica' => 'Ulica',
            'nr_domu' => 'Numer domu',
            'kod_pocztowy' => 'Kod pocztowy',
            'narodowosc' => 'Narodowość',
            'wyksztalcenie' => 'Wykształcenie',
            'nr_tel' => 'Numer telefonu',
            'email' => 'E-mail',
            'zarobki' => 'Zarobki',
            'uzytk' => 'Użytkownik',
            'hash' => 'Hash'
        ];
        foreach ($fields as $name => $label): ?>
            <label for="<?= $name ?>"><?= $label ?>: </label>
            <input type="text" name="<?= $name ?>" id="<?= $name ?>" <?php if ($name != 'imie2') echo 'required'; ?>><br><br>
        <?php endforeach; ?>

        <label for="data_zatr">Data zatrudnienia:</label>
        <input type="date" name="data_zatr" id="data_zatr" required><br><br>

        <label for="data_zwo">Data zwolnienia:</label>
        <input type="date" name="data_zwo" id="data_zwo"><br><br>

        <label for="plec">Płeć:</label>
        <select name="plec" id="plec" required>
            <option value="K">Kobieta</option>
            <option value="M">Mężczyzna</option>
        </select><br><br>

        <button type="submit">Dodaj pracownika</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>