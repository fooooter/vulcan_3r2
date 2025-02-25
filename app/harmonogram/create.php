<?php

require_once __DIR__ . '../../../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $szkola_id = htmlentities($_POST['szkola_id'] ?? null);
    $oddzial_id = htmlentities( $_POST['oddzial_id'] ?? null);
    $przedmiot_id = htmlentities($_POST['przedmiot_id'] ?? null);
    $pracownik_id = htmlentities($_POST['pracownik_id'] ?? null);
    $godz_lek = htmlentities($_POST['godz_lek'] ?? null);
    $sala = htmlentities($_POST['sala'] ?? null);
    $dzien_tyg = htmlentities($_POST['dzien_tyg'] ?? null);

    if ($szkola_id && 
        $oddzial_id && 
        $przedmiot_id && 
        $pracownik_id && 
        $godz_lek && 
        $sala && 
        $dzien_tyg) {
        $query = "INSERT INTO harmonogram (szkola_id, oddzial_id, przedmiot_id, pracownik_id, godz_lek, sala, dzien_tyg)
                  VALUES (:szkola_id, :oddzial_id, :przedmiot_id, :pracownik_id, :godz_lek, :sala, :dzien_tyg)";

        $params = [
            ':szkola_id' => $szkola_id,
            ':oddzial_id' => $oddzial_id,
            ':przedmiot_id' => $przedmiot_id,
            ':pracownik_id' => $pracownik_id,
            ':godz_lek' => $godz_lek,
            ':sala' => $sala,
            ':dzien_tyg' => $dzien_tyg
        ];

        $stmt = $connection->prepare($query);
        $result = $stmt->execute($params);

        if (!$result) {
            echo "Wystąpił błąd podczas wstawiania danych do bazy.";
            exit();
        }

        header('Location: index.php?szkola=' . $szkola_id);
        exit();
    }
}

$szkoly = $connection->query("SELECT id, nazwa FROM szkoly")->fetchAll(PDO::FETCH_ASSOC);

$szkola_id = $_GET['szkola'] ?? null;

if (isset($szkola_id)) {
    $statement = $connection->prepare("
        SELECT oddzialy.id, oddzialy.oddzial, oddzialy.grupa
        FROM oddzialy INNER JOIN szkoly ON szkoly.id = oddzialy.szkoly_id
        WHERE szkoly.id = :szkoly_id; 
    ");

    $oddzialy = fetchData($statement, [':szkoly_id' => $szkola_id]);

    if ($oddzialy instanceof DbError) {
        echo "Wystąpił błąd: " . $oddzialy->name;
        exit();
    }

    $przedmioty = $connection->query("SELECT id, nazwa FROM przedmioty;")->fetchAll(PDO::FETCH_ASSOC);

    $statement = $connection->prepare("
        SELECT pracownicy.id, imie, nazwisko
        FROM pracownicy
        INNER JOIN stanowiska ON pracownicy.stanowisko_id = stanowiska.id
        WHERE pracownicy.szkola_id = :szkola_id;
    ");

    $pracownicy = fetchData($statement, [':szkola_id' => $szkola_id]);

    if ($pracownicy instanceof DbError) {
        echo "Wystąpił błąd: " . $pracownicy->name;
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harmonogram</title>
</head>
<body>
    <form action="" method="get">
        <label for="szkola">ID Szkoły</label>
        <select name="szkola" id="szkola">
            <!-- szkola_id -->
            <?php foreach($szkoly as $szkola): ?>
                <option <?= $szkola['id'] == $szkola_id ? "selected" : "" ?> value="<?= $szkola['id'] ?>"><?= $szkola['nazwa'] ?></option>
            <?php endforeach; ?>
        </select>
        <button>Wybierz</button>
    </form>
    <h1>Dodaj wpis w harmonogramie</h1>
    <?php if(isset($szkola_id)): ?>
    <form action="" method="post">
        <input type="hidden" name="szkola_id" value="<?= $szkola_id ?>">
        <label for="oddzial_id">Oddział</label>
        <select name="oddzial_id" id="oddzial_id">
            <?php foreach($oddzialy as $oddzial): ?>
                <option value="<?= $oddzial['id'] ?>"><?= $oddzial['oddzial'] . "/" . $oddzial['grupa'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="przedmiot_id">Przedmiot</label>
        <select name="przedmiot_id" id="przedmiot_id">
            <?php foreach($przedmioty as $przedmiot): ?>
                <option value="<?= $przedmiot['id'] ?>"><?= $przedmiot['nazwa'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="pracownik_id">Pracownik</label>
        <select name="pracownik_id" id="pracownik_id">
            <?php foreach($pracownicy as $pracownik): ?>
                <option value="<?= $pracownik['id'] ?>"><?= $pracownik['nazwisko'] . " " . $pracownik['imie'] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="godz_lek">Godzina lekcyjna</label>
        <input type="number" name="godz_lek" id="godz_lek">
        <label for="sala">Sala</label>
        <input type="text" name="sala" id="sala">
        <label for="dzien_tyg">Dzień tygodnia</label>
        <select name="dzien_tyg" id="dzien_tyg">
            <option value="1">Poniedziałek</option>
            <option value="2">Wtorek</option>
            <option value="3">Środa</option>
            <option value="4">Czwartek</option>
            <option value="5">Piątek</option>
            <option value="6">Sobota</option>
            <option value="7">Niedziela</option>
        </select>
        <button>Zapisz</button>
    </form>
    <?php endif; ?>
</body>
</html>