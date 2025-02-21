<?php
    require_once(__DIR__ . "/../../db/connection.php");

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM dict_typy_ocen WHERE id = :id";
$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':id' => $id]);

if (!is_array($result) || count($result) == 0) {
    echo "Brak rekordu o podanym ID.";
    exit;
}

$sql = "SELECT id, nazwa FROM szkoly";
$stmt = $connection->prepare($sql);
$szkoly = fetchData($stmt);

$record = $result[0];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        ':id' => $id,
        ':ocena' => $_POST['ocena'] ?? null,
        ':wartosc' => $_POST['wartosc'] ?? null,
        ':szkola_id' => $_POST['szkola_id'] ?? null,
    ];

    if ($params[':ocena'] && $params[':wartosc'] && $params[':szkola_id']) {
        $sql = "UPDATE dict_typy_ocen
                SET ocena = :ocena, 
                    wartosc = :wartosc, 
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
    <title>Edytuj typ oceny</title>
</head>
<body>
    <h1>Edytuj typ oceny</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="ocena">Ocena:</label>
        <input type="text" name="ocena" id="ocena" value="<?php echo htmlspecialchars($record['ocena']); ?>" required><br><br>
        
        <label for="wartosc">Wartość:</label>
        <input type="wartosc" name="wartosc" id="wartosc" value="<?php echo htmlspecialchars($record['wartosc']); ?>"><br><br>
        <label for="szkola_id">Szkoła:</label>
        <select name="szkola_id" id="szkola_id" required>
            <?php foreach ($szkoly as $szkola): ?>
                <option value="<?php echo $szkola['id']; ?>" <?php echo $record['szkola_id'] == $szkola['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($szkola['nazwa']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Zaaktualizuj</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>