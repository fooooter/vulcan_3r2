<?php
require_once __DIR__ . '/../../db/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM uwagi WHERE id = :id";
$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':id' => $id]);

if (!is_array($result) || count($result) == 0) {
    echo "Brak rekordu o podanym ID.";
    exit;
}

$record = $result[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $typ_uwagi    = !empty($_POST['typ_uwagi']) ? htmlspecialchars(trim($_POST['typ_uwagi'])) : null;
    $data         = !empty($_POST['data']) ? htmlspecialchars(trim($_POST['data'])) : null;
    $godzina      = !empty($_POST['godzina']) ? htmlspecialchars(trim($_POST['godzina'])) : null;
    $tresc        = !empty($_POST['tresc']) ? htmlspecialchars(trim($_POST['tresc'])) : null;
    if (!empty($typ_uwagi) && !empty($data) && !empty($godzina) && !empty($tresc)) {
        $sql = "UPDATE uwagi 
                SET typ_uwagi = :typ_uwagi, 
                    data = :data, 
                    godzina = :godzina, 
                    tresc = :tresc
                WHERE id = :id";
        $stmt = $connection->prepare($sql);
        $params = [
            ':typ_uwagi'    => $typ_uwagi,
            ':data'         => $data,
            ':godzina'      => $godzina,
            ':tresc'        => $tresc,
            ':id'           => $id,
        ];
        $result = fetchData($stmt, $params);
        if (is_array($result)) {
            header("Location: read.php?id=" . $id);
            exit;
        } else {
            $error = $result;
        }
    } else {
        $error = "Wypełnij wszystkie pola.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj uwagę</title>
</head>
<body>
    <h1>Edytuj uwagę</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Uczeń:</label>
        <input type="text" value="<?php echo htmlspecialchars($record['uczen_id']); ?>" disabled><br><br>
        
        <label for="typ_uwagi">Typ uwagi:</label>
        <select name="typ_uwagi" id="typ_uwagi" required>
            <option value="pozytywna" <?=$record['typ_uwagi'] == "pozytywna" ? "selected" : ""?>>Pozytywna</option>
            <option value="negatywna" <?=$record['typ_uwagi'] == "negatywna" ? "selected" : ""?>>Negatywna</option>
        </select><br><br>
        
        <label for="data">Data:</label>
        <input type="date" name="data" id="data" value="<?php echo htmlspecialchars($record['data']); ?>" required><br><br>
        
        <label for="godzina">Godzina:</label>
        <input type="time" name="godzina" id="godzina" value="<?php echo htmlspecialchars($record['godzina']); ?>" required><br><br>
        
        <label for="tresc">Treść:</label>
        <textarea name="tresc" id="tresc" required><?php echo htmlspecialchars($record['tresc']); ?></textarea><br><br>
        
        <label>Nauczyciel:</label>
        <input type="text" value="<?php echo htmlspecialchars($record['pracownik_id']); ?>" disabled><br><br>
        
        <button type="submit">Aktualizuj</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
