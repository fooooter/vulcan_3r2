<?php
require_once __DIR__ . "/../../db/connection.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM szkoly WHERE id = :id";
$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':id' => $id]);

if (!is_array($result) || count($result) == 0) {
    echo "Brak rekordu o podanym ID.";
    exit;
}

$record = $result[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        ':id' => $id,
        ':nazwa' => $_POST['nazwa'] ?? null,
        ':miasto' => $_POST['miasto'] ?? null,
        ':ulica' => $_POST['ulica'] ?? null,
        ':nr_budynku' => $_POST['nr_budynku'] ?? null,
        ':kod_poczt' => $_POST['kod_poczt'] ?? null,
        ':rodzaj' => $_POST['rodzaj'] ?? null,
        ':nip' => $_POST['nip'] ?? null
    ];

    if ($params[':nazwa'] && $params[':miasto'] && $params[':ulica'] && $params[':nr_budynku'] && $params[':kod_poczt'] && $params[':rodzaj'] && $params[':nip']) {
        $sql = "UPDATE szkoly
                SET nazwa = :nazwa, 
                    miasto = :miasto, 
                    ulica = :ulica, 
                    nr_budynku = :nr_budynku, 
                    kod_poczt = :kod_poczt, 
                    rodzaj = :rodzaj, 
                    nip = :nip
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
    <title>Edytuj szkołę</title>
</head>
<body>
    <h1>Edytuj szkołę</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="nazwa">Nazwa:</label>
        <input type="text" name="nazwa" id="nazwa" value="<?php echo htmlspecialchars($record['nazwa']); ?>" required><br><br>
        
        <label for="miasto">Miasto:</label>
        <input type="text" name="miasto" id="miasto" value="<?php echo htmlspecialchars($record['miasto']); ?>" required><br><br>

        <label for="ulica">Ulica:</label>
        <input type="text" name="ulica" id="ulica" value="<?php echo htmlspecialchars($record['ulica']); ?>" required><br><br>

        <label for="nr_budynku">Numer budynku:</label>
        <input type="text" name="nr_budynku" id="nr_budynku" value="<?php echo htmlspecialchars($record['nr_budynku']); ?>" required><br><br>

        <label for="kod_poczt">Kod pocztowy:</label>
        <input type="text" name="kod_poczt" id="kod_poczt" value="<?php echo htmlspecialchars($record['kod_poczt']); ?>" required><br><br>

        <label for="rodzaj">Rodzaj:</label>
        <input type="text" name="rodzaj" id="rodzaj" value="<?php echo htmlspecialchars($record['rodzaj']); ?>" required><br><br>

        <label for="nip">NIP:</label>
        <input type="text" name="nip" id="nip" value="<?php echo htmlspecialchars($record['nip']); ?>" required><br><br>

        <button type="submit">Zaktualizuj</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>