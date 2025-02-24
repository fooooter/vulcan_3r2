<?php
require_once __DIR__ . "/../../db/connection.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM pracownicy WHERE id = :id";
$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':id' => $id]);

if (!is_array($result) || count($result) == 0) {
    echo "Brak rekordu o podanym ID.";
    exit;
}

$record = $result[0];

$sql = "SELECT id, nazwa FROM szkoly";
$stmt = $connection->prepare($sql);
$szkoly = fetchData($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_zwo = empty($_POST['data_zwo']) ? null : $_POST['data_zwo'];

    $params = [
        ':id' => $id,
        ':imie' => $_POST['imie'] ?? null,
        ':imie2' => $_POST['imie2'] ?? null,
        ':nazwisko' => $_POST['nazwisko'] ?? null,
        ':pesel' => $_POST['pesel'] ?? null,
        ':miasto' => $_POST['miasto'] ?? null,
        ':ulica' => $_POST['ulica'] ?? null,
        ':nr_domu' => $_POST['nr_domu'] ?? null,
        ':kod_pocztowy' => $_POST['kod_pocztowy'] ?? null,
        ':kraj' => $_POST['kraj'] ?? null,
        ':narodowosc' => $_POST['narodowosc'] ?? null,
        ':zarobki' => $_POST['zarobki'] ?? null,
        ':plec' => $_POST['plec'] ?? null,
        ':wyksztalcenie' => $_POST['wyksztalcenie'] ?? null,
        ':data_zatr' => $_POST['data_zatr'] ?? null,
        ':data_zwo' => $data_zwo,
        ':nr_tel' => $_POST['nr_tel'] ?? null,
        ':email' => $_POST['email'] ?? null,
        ':uzytk' => $_POST['uzytk'] ?? null,
        ':hash' => $_POST['hash'] ?? null,
        ':szkola_id' => $_POST['szkola_id'] ?? null
    ];

    if ($params[':imie'] && $params[':nazwisko'] && $params[':pesel'] && $params[':email'] && $params[':nr_tel'] && $params[':szkola_id'] && $params[':plec']) {
        $sql = "UPDATE pracownicy
                SET imie = :imie, 
                    imie2 = :imie2, 
                    nazwisko = :nazwisko, 
                    pesel = :pesel, 
                    miasto = :miasto, 
                    ulica = :ulica, 
                    nr_domu = :nr_domu, 
                    kod_pocztowy = :kod_pocztowy, 
                    kraj = :kraj, 
                    narodowosc = :narodowosc, 
                    zarobki = :zarobki, 
                    plec = :plec, 
                    wyksztalcenie = :wyksztalcenie, 
                    data_zatr = :data_zatr, 
                    data_zwo = :data_zwo, 
                    nr_tel = :nr_tel, 
                    email = :email, 
                    uzytk = :uzytk, 
                    hash = :hash, 
                    szkola_id = :szkola_id
                WHERE id = :id";

        $stmt = $connection->prepare($sql);
        $result = fetchData($stmt, $params);

        if (is_array($result)) {
            header("Location: index.php");
            exit;
        } else {
            $error = $result;
        }
    } else {
        $error = "Wypełnij wszystkie wymagane pola.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj pracownika</title>
</head>
<body>
    <h1>Edytuj pracownika</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="imie">Imię:</label>
        <input type="text" name="imie" id="imie" value="<?php echo htmlspecialchars($record['imie'] ?? ''); ?>" required><br><br>
        
        <label for="imie2">Drugie imię:</label>
        <input type="text" name="imie2" id="imie2" value="<?php echo htmlspecialchars($record['imie2'] ?? ''); ?>"><br><br>

        <label for="nazwisko">Nazwisko:</label>
        <input type="text" name="nazwisko" id="nazwisko" value="<?php echo htmlspecialchars($record['nazwisko'] ?? ''); ?>" required><br><br>

        <label for="pesel">PESEL:</label>
        <input type="text" name="pesel" id="pesel" value="<?php echo htmlspecialchars($record['pesel'] ?? ''); ?>" required><br><br>

        <label for="miasto">Miasto:</label>
        <input type="text" name="miasto" id="miasto" value="<?php echo htmlspecialchars($record['miasto'] ?? ''); ?>"><br><br>

        <label for="ulica">Ulica:</label>
        <input type="text" name="ulica" id="ulica" value="<?php echo htmlspecialchars($record['ulica'] ?? ''); ?>"><br><br>

        <label for="nr_domu">Numer domu:</label>
        <input type="text" name="nr_domu" id="nr_domu" value="<?php echo htmlspecialchars($record['nr_domu'] ?? ''); ?>"><br><br>

        <label for="kod_pocztowy">Kod pocztowy:</label>
        <input type="text" name="kod_pocztowy" id="kod_pocztowy" value="<?php echo htmlspecialchars($record['kod_pocztowy'] ?? ''); ?>"><br><br>

        <label for="kraj">Kraj:</label>
        <input type="text" name="kraj" id="kraj" value="<?php echo htmlspecialchars($record['kraj'] ?? ''); ?>"><br><br>

        <label for="narodowosc">Narodowość:</label>
        <input type="text" name="narodowosc" id="narodowosc" value="<?php echo htmlspecialchars($record['narodowosc'] ?? ''); ?>"><br><br>

        <label for="zarobki">Zarobki:</label>
        <input type="text" name="zarobki" id="zarobki" value="<?php echo htmlspecialchars($record['zarobki'] ?? ''); ?>"><br><br>

        <label for="plec">Płeć:</label>
        <select name="plec" id="plec" required>
            <option value="K" <?php echo ($record['plec'] ?? '') == 'K' ? 'selected' : ''; ?>>Kobieta</option>
            <option value="M" <?php echo ($record['plec'] ?? '') == 'M' ? 'selected' : ''; ?>>Mężczyzna</option>
        </select><br><br>

        <label for="wyksztalcenie">Wykształcenie:</label>
        <input type="text" name="wyksztalcenie" id="wyksztalcenie" value="<?php echo htmlspecialchars($record['wyksztalcenie'] ?? ''); ?>"><br><br>

        <label for="data_zatr">Data zatrudnienia:</label>
        <input type="date" name="data_zatr" id="data_zatr" value="<?php echo htmlspecialchars($record['data_zatr'] ?? ''); ?>"><br><br>

        <label for="data_zwo">Data zwolnienia:</label>
        <input type="date" name="data_zwo" id="data_zwo" value="<?php echo htmlspecialchars($record['data_zwo'] ?? ''); ?>"><br><br>

        <label for="nr_tel">Numer telefonu:</label>
        <input type="text" name="nr_tel" id="nr_tel" value="<?php echo htmlspecialchars($record['nr_tel'] ?? ''); ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($record['email'] ?? ''); ?>" required><br><br>

        <label for="uzytk">Użytkownik:</label>
        <input type="text" name="uzytk" id="uzytk" value="<?php echo htmlspecialchars($record['uzytk'] ?? ''); ?>"><br><br>

        <label for="hash">Hash:</label>
        <input type="text" name="hash" id="hash" value=""><br><br>

        <label for="szkola_id">Szkoła:</label>
        <select name="szkola_id" id="szkola_id" required>
            <?php foreach ($szkoly as $szkola): ?>
                <option value="<?php echo $szkola['id']; ?>" <?php echo ($record['szkola_id'] ?? '') == $szkola['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($szkola['nazwa'] ?? ''); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" value="Zaktualizuj">
    </form>
</body>
</html>