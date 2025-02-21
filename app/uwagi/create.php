<?php
require_once __DIR__ . '/../../db/connection.php';

$sql = "SELECT   pracownicy.id       AS pracownik_id,
                 CONCAT(pracownicy.nazwisko, ' ', pracownicy.imie) AS pracownik_nazwa
        FROM     pracownicy INNER JOIN szkoly ON pracownicy.szkola_id = szkoly.id
        WHERE    szkoly.id=:szkola_id 
        ORDER BY pracownicy.nazwisko ASC;";
$stmt = $connection->prepare($sql);
$params = [
    ":szkola_id"=>1
];
$result = fetchData($stmt, $params);

if (!is_array($result)) {
    echo "Błąd: " . htmlspecialchars($result);
    exit;
}

$sql = "SELECT   uczniowie.id AS uczen_id,
                 CONCAT(
                    uczniowie.nazwisko, 
                    ' ', 
                    uczniowie.imie,
                    ' ',
                    oddzialy.oddzial
                ) AS uczen_nazwa
        FROM     uczniowie INNER JOIN oddzialy ON oddzialy.id = uczniowie.oddzial_id
                           INNER JOIN szkoly   ON uczniowie.szkola_id = szkoly.id
        WHERE    szkoly.id=:szkola_id
        GROUP BY uczen_id, oddzialy.oddzial
        ORDER BY oddzialy.oddzial, uczniowie.nazwisko;";
$stmt = $connection->prepare($sql);
$result2 = fetchData($stmt, $params);

if (!is_array($result2)) {
    echo "Błąd: " . htmlspecialchars($result2);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uczen_id     = !empty($_POST['uczen_id']) ? htmlspecialchars(trim($_POST['uczen_id'])) : null;
    $typ_uwagi    = !empty($_POST['typ_uwagi']) ? htmlspecialchars(trim($_POST['typ_uwagi'])) : null;
    $data         = !empty($_POST['data']) ? htmlspecialchars(trim($_POST['data'])) : null;
    $godzina      = !empty($_POST['godzina']) ? htmlspecialchars(trim($_POST['godzina'])) : null;
    $tresc        = !empty($_POST['tresc']) ? htmlspecialchars(trim($_POST['tresc'])) : null;
    $pracownik_id = !empty($_POST['pracownik_id']) ? htmlspecialchars(trim($_POST['pracownik_id'])) : null;

    if (!empty($uczen_id) && !empty($typ_uwagi) && !empty($data) && !empty($godzina) && !empty($tresc) && !empty($pracownik_id)) {
        $sql = "INSERT INTO uwagi (uczen_id, typ_uwagi, data, godzina, tresc, pracownik_id) 
            VALUES (:uczen_id, :typ_uwagi, :data, :godzina, :tresc, :pracownik_id)";
        $stmt = $connection->prepare($sql);
        $params = [
            ':uczen_id'     => $uczen_id,
            ':typ_uwagi'    => $typ_uwagi,
            ':data'         => $data,
            ':godzina'      => $godzina,
            ':tresc'        => $tresc,
            ':pracownik_id' => $pracownik_id,
        ];

        $result = fetchData($stmt, $params);

        if (is_array($result)) {
            header("Location: index.php");
            exit;
        } else {
            $error = $result;
        }
    } else {
        $error = "Nie wypełniono wszystkich pół.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj nową uwagę</title>
</head>
<body>
    <h1>Dodaj nową uwagę</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;">Błąd: <?=htmlspecialchars($error)?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label for="uczen_id">Uczeń:</label>
        <select name="uczen_id" id="uczen_id" required>
            <option value="">Wybierz ucznia</option>
            <hr/>
            <?php foreach($result2 as $row): ?>
                <option value="<?=$row['uczen_id']?>"><?=$row['uczen_nazwa']?></option>
            <?php endforeach; ?>
            <hr/>
        </select><br><br>
        
        <label for="typ_uwagi">Typ uwagi:</label>
        <select name="typ_uwagi" id="typ_uwagi" required>
            <option value="pozytywna">Pozytywna</option>
            <option value="negatywna">Negatywna</option>
        </select><br><br>
        
        <label for="data">Data:</label>
        <input type="date" name="data" id="data" required><br><br>
        
        <label for="godzina">Godzina:</label>
        <input type="time" name="godzina" id="godzina" required><br><br>
        
        <label for="tresc">Treść:</label>
        <textarea name="tresc" id="tresc" required></textarea><br><br>
        
        <label for="pracownik_id">Nauczyciel:</label>
        <select name="pracownik_id" id="pracownik_id" required>
            <option value="">Wybierz nauczyciela</option>
            <hr/>
            <?php foreach($result as $row): ?>
                <option value="<?=$row['pracownik_id']?>"><?=$row['pracownik_nazwa']?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">Dodaj</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
