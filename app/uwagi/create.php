<?php
require_once '../../db/connection.php';

$sql = "SELECT  pracownicy.id AS pracownik_id,
                pracownicy.imie AS pracownik_imie, 
                pracownicy.nazwisko AS pracownik_nazwisko
        FROM    oddzialy   INNER JOIN pracownicy ON oddzialy.id = pracownicy.oddzial_id
                           INNER JOIN szkoly     ON oddzialy.szkoly_id = szkoly.id
        WHERE   szkoly.id=:szkola_id;";
$stmt = $connection->prepare($sql);
$params = [
    ":szkola_id"=>1
];
$result = fetchData($stmt, $params);

$sql = "SELECT  uczniowie.id AS uczen_id,
                uczniowie.imie AS uczen_imie, 
                uczniowie.nazwisko AS uczen_nazwisko,
                oddzialy.oddzial as oddzial
        FROM    uczniowie INNER JOIN oddzialy ON oddzialy.id = uczniowie.oddzialy_id
                         INNER JOIN szkoly   ON uczniowie.szkola_id = szkoly.id
        WHERE   szkoly.id=:szkola_id;";
$stmt = $connection->prepare($sql);
$result2 = fetchData($stmt, $params);

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
    <title>Dodaj nową uwagę</title>
</head>
<body>
    <h1>Dodaj nową uwagę</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;">Błąd: <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="uczen_id">Uczeń:</label>
        <select name="uczen_id" id="uczen_id" required>
            <?php foreach($result2 as $row): ?>
                <option value="<?=$row['uczen_id']?>"><?=$row['uczen_imie']?> <?=$row['uczen_nazwisko']?> <?=$row['oddzial']?></option>
            <?php endforeach; ?>
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
            <?php foreach($result as $row): ?>
                <option value="<?=$row['pracownik_id']?>"><?=$row['pracownik_imie']?> <?=$row['pracownik_nazwisko']?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">Dodaj</button>
    </form>
    <br>
    <a href="index.php">Powrót do listy</a>
</body>
</html>
