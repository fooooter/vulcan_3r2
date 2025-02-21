<?php
require_once __DIR__ . "/../../db/connection.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM uczniowie WHERE id = :id";
$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':id' => $id]);

if (!is_array($result) || count($result) == 0) {
    echo "Brak rekordu o podanym ID.";
    exit;
}

$record = $result[0];

$sql = "SELECT id, oddzial FROM oddzialy";
$stmt = $connection->prepare($sql);
$oddzialy = fetchData($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        ':id' => $id,
        ':imie' => $_POST['imie'] ?? null,
        ':imie2' => $_POST['imie2'] ?? null,
        ':nazwisko' => $_POST['nazwisko'] ?? null,
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
        ':oddzial_id' => $_POST['oddzial_id'] ?? null
    ];

    if ($params[':imie'] && $params[':nazwisko'] && $params[':data_ur'] && $params[':pesel'] && $params[':email'] && $params[':nr_tel'] && $params[':oddzial_id'] && $params[':plec']) {
        $sql = "UPDATE uczniowie
                SET imie = :imie, 
                    imie2 = :imie2, 
                    nazwisko = :nazwisko, 
                    data_ur = :data_ur, 
                    pesel = :pesel, 
                    kraj = :kraj, 
                    miasto = :miasto, 
                    ulica = :ulica, 
                    email = :email, 
                    nr_tel = :nr_tel, 
                    plec = :plec, 
                    narodowosc = :narodowosc, 
                    kod_pocztowy = :kod_pocztowy, 
                    nr_domu = :nr_domu, 
                    oddzial_id = :oddzial_id
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
    <title>Edytuj ucznia</title>
</head>
<body>
    <h1>Edytuj ucznia</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="imie">Imię:</label>
        <input type="text" name="imie" id="imie" value="<?php echo htmlspecialchars($record['imie']); ?>" required><br><br>
        
        <label for="imie2">Drugie imię:</label>
        <input type="text" name="imie2" id="imie2" value="<?php echo htmlspecialchars($record['imie2']); ?>"><br><br>

        <label for="nazwisko">Nazwisko:</label>
        <input type="text" name="nazwisko" id="nazwisko" value="<?php echo htmlspecialchars($record['nazwisko']); ?>" required><br><br>

        <label for="data_ur">Data urodzenia:</label>
        <input type="date" name="data_ur" id="data_ur" value="<?php echo htmlspecialchars($record['data_ur']); ?>" required><br><br>

        <label for="pesel">PESEL:</label>
        <input type="text" name="pesel" id="pesel" value="<?php echo htmlspecialchars($record['pesel']); ?>" required><br><br>

        <label for="kraj">Kraj:</label>
        <input type="text" name="kraj" id="kraj" value="<?php echo htmlspecialchars($record['kraj']); ?>"><br><br>

        <label for="miasto">Miasto:</label>
        <input type="text" name="miasto" id="miasto" value="<?php echo htmlspecialchars($record['miasto']); ?>"><br><br>

        <label for="ulica">Ulica:</label>
        <input type="text" name="ulica" id="ulica" value="<?php echo htmlspecialchars($record['ulica']); ?>"><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($record['email']); ?>" required><br><br>

        <label for="nr_tel">Numer telefonu:</label>
        <input type="text" name="nr_tel" id="nr_tel" value="<?php echo htmlspecialchars($record['nr_tel']); ?>" required><br><br>

        <label for="plec">Płeć:</label>
        <select name="plec" id="plec" required>
            <option value="K" <?php echo $record['plec'] == 'K' ? 'selected' : ''; ?>>Kobieta</option>
            <option value="M" <?php echo $record['plec'] == 'M' ? 'selected' : ''; ?>>Mężczyzna</option>
        </select><br><br>

        <label for="narodowosc">Narodowość:</label>
        <input type="text" name="narodowosc" id="narodowosc" value="<?php echo htmlspecialchars($record['narodowosc']); ?>"><br><br>

        <label for="kod_pocztowy">Kod pocztowy:</label>
        <input type="text" name="kod_pocztowy" id="kod_pocztowy" value="<?php echo htmlspecialchars($record['kod_pocztowy']); ?>"><br><br>

        <label for="nr_domu">Numer domu:</label>
        <input type="text" name="nr_domu" id="nr_domu" value="<?php echo htmlspecialchars($record['nr_domu']); ?>"><br><br>

        <label for="oddzial_id">Oddział:</label>
        <select name="oddzial_id" id="oddzial_id" required>
            <?php foreach ($oddzialy as $oddzial): ?>
                <option value="<?php echo $oddzial['id']; ?>" <?php echo $record['oddzial_id'] == $oddzial['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($oddzial['oddzial']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Zaktualizuj</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>