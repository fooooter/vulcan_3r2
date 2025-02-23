<?php
require_once __DIR__ . "/../../db/connection.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$query = "SELECT * FROM oceny WHERE id = :id";
$stmt = $connection->prepare($query);
$result = fetchData($stmt, [':id' => $id]);

if (!is_array($result) || count($result) == 0) {
    echo "Brak rekordu o podanym ID.";
    exit;
}

$ocena = $result[0];

$query = "SELECT id, imie, imie2, nazwisko FROM uczniowie WHERE id = :id";
$stmt = $connection->prepare($query);
$uczen_result = fetchData($stmt, [':id' => $ocena['uczen_id']]);
if (!is_array($result) || count($result) == 0 || $uczen_result instanceof DbError) {
    echo "Wystąpił błąd: " . $uczen_result->name;
    exit();
}
$uczen = $uczen_result[0];

$query = "SELECT id, ocena FROM dict_typy_ocen";
$stmt = $connection->prepare($query);
$typy_ocen = fetchData($stmt);
if ($typy_ocen instanceof DbError) {
    echo "Wystąpił błąd: " . $typy_ocen->name;
    exit();
}

$query = "SELECT id, imie, nazwisko FROM pracownicy";
$stmt = $connection->prepare($query);
$pracownicy = fetchData($stmt);

if ($pracownicy instanceof DbError) {
    echo "Wystąpił błąd: " . $pracownicy->name;
    exit();
}

// Pobieranie danych o przedmiotach
$query = "SELECT id, nazwa FROM dict_przedmioty";
$stmt = $connection->prepare($query);
$przedmioty = fetchData($stmt);

if ($przedmioty instanceof DbError) {
    echo "Wystąpił błąd: " . $przedmioty->name;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id             = $_GET['id'] ?? null;
    $typ_oceny      = $_POST['typ_oceny'] ?? null;
    $tytul_oceny    = $_POST['tytul_oceny'] ?? null;
    $opis_oceny     = $_POST['opis_oceny'] ?? null;
    $przedmiot_id   = $_POST['przedmiot_id'] ?? null;
    $pracownik_id   = $_POST['pracownik_id'] ?? null;
    $uczen_id       = $ocena['uczen_id'];

    if (!$id || !$typ_oceny || !$tytul_oceny || !$przedmiot_id || !$pracownik_id || !$uczen_id) {
        echo "Brak wymaganych danych!";
        exit();
    }

    $sql = "UPDATE oceny 
            SET typ_oceny = :typ_oceny, 
                tytul_oceny = :tytul_oceny, 
                opis_oceny = :opis_oceny, 
                przedmiot_id = :przedmiot_id, 
                pracownik_id = :pracownik_id, 
                uczen_id = :uczen_id
            WHERE id = :id";

    $statement = $connection->prepare($sql);
    
    $params = [
        ':id'           => $id,
        ':typ_oceny'    => $typ_oceny,
        ':tytul_oceny'  => $tytul_oceny,
        ':opis_oceny'   => $opis_oceny,
        ':przedmiot_id' => $przedmiot_id,
        ':pracownik_id' => $pracownik_id,
        ':uczen_id'     => $uczen_id
    ];

    if ($statement->execute($params)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Błąd podczas aktualizacji.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edycja oceny (id:<?= $id ?>)</title>
</head>
<body>
    <h1>Edytuj ocenę</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Uczeń:</label>
        <input type="text" value="<?php echo htmlspecialchars($uczen['imie'] . ' ' . $uczen['imie2'] . ' ' . $uczen['nazwisko']); ?>" disabled><br><br>
        
        <label for="typ_oceny">Typ oceny</label>
        <select name="typ_oceny" id="typ_oceny">
            <?php foreach ($typy_ocen as $typ): ?>
                <option value="<?= $typ['id'] ?>" <?php if ($typ['id'] == $ocena['typ_oceny']) echo 'selected'; ?> ><?= $typ['ocena'] ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="tytul_oceny">Tytuł oceny</label>
        <input type="text" name="tytul_oceny" id="tytul_oceny" value="<?= $ocena['tytul_oceny']; ?>">

        <label for="opis_oceny">Opis oceny</label>
        <textarea name="opis_oceny" id="opis_oceny"><?= $ocena['opis_oceny']; ?></textarea>
        
        <label for="przedmiot_id">Przedmiot</label>
        <select name="przedmiot_id" id="przedmiot_id">
            <?php foreach ($przedmioty as $przedmiot): ?>
                <option value="<?= $przedmiot['id'] ?>" <?php if ($przedmiot['id'] == $ocena['przedmiot_id']) echo 'selected'; ?>><?= $przedmiot['nazwa'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="pracownik_id">Nauczyciel</label>
        <select name="pracownik_id" id="pracownik_id">
            <?php foreach ($pracownicy as $pracownik): ?>
                <option value="<?= $pracownik['id'] ?>" <?php if ($pracownik['id'] == $ocena['pracownik_id']) echo 'selected'; ?>><?= $pracownik['imie'] ?> <?= $pracownik['nazwisko'] ?></option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Aktualizuj</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
