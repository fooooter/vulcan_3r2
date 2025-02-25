<?php

require_once __DIR__ . "..\..\..\db\connection.php";

// Wybór szkoły jest obecnie konieczny. 
// Wraz z wprowadzeniem id szkoły do sesji będzie on mógł zostać usunięty.

$szkoly = $connection->query("SELECT id, nazwa FROM szkoly");

$szkola_id = $_GET['szkola'] ?? null;

$oddzialy = [];
$pracownicy = [];
$sale = [];

if (isset($szkola_id)) {
    $statement = $connection->prepare("
        SELECT oddzialy.id, oddzialy.oddzial, oddzialy.grupa
        FROM oddzialy 
        INNER JOIN szkoly ON szkoly.id = oddzialy.szkoly_id 
        WHERE szkoly.id = :id;
    ");
    $oddzialy = fetchData($statement, [':id' => $szkola_id]);
    
    if ($oddzialy instanceof DbError) {
        echo "Wystąpił błąd: " . $oddzialy->name;
        exit();
    }

    $statement = $connection->prepare("
        SELECT pracownicy.id, pracownicy.imie, pracownicy.nazwisko
        FROM pracownicy
        INNER JOIN szkoly ON szkoly.id = pracownicy.szkola_id
        INNER JOIN stanowiska ON pracownicy.stanowisko_id = stanowiska.id
        WHERE szkoly.id = :id
        AND stanowiska.nazwa LIKE \"Nauczyciel\";
    ");
    $pracownicy = fetchData($statement, [':id' => $szkola_id]);
    
    if ($pracownicy instanceof DbError) {
        echo "Wystąpił błąd: " . $pracownicy->name;
        exit();
    }

    $statement = $connection->prepare("
        SELECT DISTINCT sala
        FROM harmonogram 
        INNER JOIN szkoly ON szkoly.id = harmonogram.szkola_id 
        WHERE szkoly.id = :id;
    ");
    $sale = fetchData($statement, [':id' => $szkola_id]);

    if ($sale instanceof DbError) {
        echo "Wystąpił błąd: " . $sale->name;
        exit();
    }
}

$pracownik_id = $_GET['pracownik'] ?? null;
$oddzial_id = $_GET['oddzial'] ?? null;
$sala_id = $_GET['sala'] ?? null;

if (isset($pracownik_id)) {
    $statement = $connection->prepare(
        "SELECT harmonogram.*, pracownicy.imie, pracownicy.nazwisko, przedmioty.nazwa
        FROM harmonogram 
        INNER JOIN pracownicy ON harmonogram.pracownik_id = pracownicy.id
        INNER JOIN przedmioty ON harmonogram.przedmiot_id = przedmioty.id
        WHERE pracownicy.id = :pracownik_id
        AND harmonogram.szkola_id = :szkola_id
        ORDER BY harmonogram.godz_lek ASC, harmonogram.dzien_tyg ASC");
    $harmonogram = fetchData($statement, [':pracownik_id' => $pracownik_id, ':szkola_id' => $szkola_id]);
} else if (isset($oddzial_id)) {
    $statement = $connection->prepare(
        "SELECT harmonogram.*, pracownicy.imie, pracownicy.nazwisko, przedmioty.nazwa
        FROM harmonogram 
        INNER JOIN pracownicy ON harmonogram.pracownik_id = pracownicy.id
        INNER JOIN przedmioty ON harmonogram.przedmiot_id = przedmioty.id
        WHERE harmonogram.oddzial_id = :oddzial_id
        AND harmonogram.szkola_id = :szkola_id
        ORDER BY harmonogram.godz_lek ASC, harmonogram.dzien_tyg ASC");
    $harmonogram = fetchData($statement, [':oddzial_id' => $oddzial_id, ':szkola_id' => $szkola_id]);
      
} else if (isset($sala_id)) {
    $statement = $connection->prepare(
        "SELECT harmonogram.*, pracownicy.imie, pracownicy.nazwisko, przedmioty.nazwa 
        FROM harmonogram 
        INNER JOIN pracownicy ON harmonogram.pracownik_id = pracownicy.id
        INNER JOIN przedmioty ON harmonogram.przedmiot_id = przedmioty.id
        WHERE harmonogram.sala = :sala_id
        AND harmonogram.szkola_id = :szkola_id
        ORDER BY harmonogram.godz_lek ASC, harmonogram.dzien_tyg ASC
    ");
    $harmonogram = fetchData($statement, [':sala_id' => $sala_id, ':szkola_id' => $szkola_id]);
}

if (isset($harmonogram)) {
    if ($harmonogram instanceof DbError) {
        echo "Wystąpił błąd: " . $harmonogram->name;
        exit();
    }   
    $godz = max(array_map(function($value) {
        return $value['godz_lek'];
    }, $harmonogram));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Harmonogram</h1>
    <hr>
    <form action="" method="get">
        <label for="szkola">Wybierz szkołę</label>
        <select name="szkola" id="szkola">
            <?php foreach($szkoly as $s): ?>
                <option <?= $s['id'] == $szkola_id ? "selected" : "" ?> value="<?= $s['id'] ?>"><?= $s['nazwa'] ?></option>
            <?php endforeach; ?>
        </select>
        <button>Pokaż harmonogram szkoły</button>
    </form>
    <hr>
    <form action="" method="get">
        <label for="oddzial">Filtruj według oddziału:</label>    
        <select id="oddzial" name="oddzial">
            <?php foreach($oddzialy as $oddzial): ?>
                <option value="<?= $oddzial['id'] ?>"><?= $oddzial['oddzial'] . "/" . $oddzial['grupa']?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="szkola" value="<?= $szkola_id ?>">
        <button>Filtruj</button>
    </form>
    <form action="" method="get">
        <label for="pracownik">Filtruj według pracownika:</label>    
        <select id="pracownik" name="pracownik">
            <?php foreach($pracownicy as $pracownik): ?>
                <option value="<?= $pracownik['id'] ?>"><?= $pracownik['nazwisko'] . " " . $pracownik['imie'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="szkola" value="<?= $szkola_id ?>">
        <button>Filtruj</button>
    </form>
    <form action="" method="get">
        <label for="sala">Filtruj według sal:</label>    
        <select id="sala" name="sala">
            <?php foreach($sale as $sala): ?>
                <option value="<?= $sala['sala'] ?>"><?= $sala['sala'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="szkola" value="<?= $szkola_id ?>">
        <button>Filtruj</button>
    </form>
    <hr>
    <?php if (isset($harmonogram)): ?>
        <table border="1px solid black">
            <thead>
                <tr>
                    <th>Godz. lek.</th>
                    <th>Poniedziałek</th>
                    <th>Wtorek</th>
                    <th>Środa</th>
                    <th>Czwartek</th>
                    <th>Piątek</th>
                    <th>Sobota</th>
                    <th>Niedziela</th>
                </tr>
            </thead>
            <tbody>
                <?php $idx = 0; for($i = 1; $i <= $godz; $i++): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <?php for($j = 1; $j <= 7; $j++): ?>
                            <td>
                                <?php if(isset($harmonogram[$idx]) && $harmonogram[$idx]['godz_lek'] == $i && $harmonogram[$idx]['dzien_tyg'] == $j): ?>
                                    <?= $harmonogram[$idx]['nazwa'] ?> <br>
                                    <?= $harmonogram[$idx]['imie'] . " " . $harmonogram[$idx]['nazwisko'] . ", " . $harmonogram[$idx]['sala'] ?>
                                <?php    $idx++; endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>