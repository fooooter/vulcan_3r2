<?php
require_once __DIR__ . "/../../db/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $miasto = empty($_POST['miasto']) ? null : htmlspecialchars(trim($_POST['miasto']));
    $ulica = empty($_POST['ulica']) ? null : htmlspecialchars(trim($_POST['ulica']));
    $nr_budynku = empty($_POST['nr_budynku']) ? null : htmlspecialchars(trim($_POST['nr_budynku']));
    $kod_poczt = empty($_POST['kod_poczt']) ? null : htmlspecialchars(trim($_POST['kod_poczt']));
    $nazwa = empty($_POST['nazwa']) ? null : htmlspecialchars(trim($_POST['nazwa']));
    $rodzaj = empty($_POST['rodzaj']) ? null : htmlspecialchars(trim($_POST['rodzaj']));
    $nip = empty($_POST['nip']) ? null : htmlspecialchars(trim($_POST['nip']));

    if (!empty($miasto) && !empty($ulica) && !empty($nr_budynku) && !empty($kod_poczt) && !empty($nazwa) && !empty($rodzaj) && !empty($nip)) {
        $statement = $connection->prepare(
            "INSERT INTO szkoly (id, miasto, ulica, nr_budynku, kod_poczt, nazwa, rodzaj, nip) 
            VALUES (null, :miasto, :ulica, :nr_budynku, :kod_poczt, :nazwa, :rodzaj, :nip);"
        );

        $params = [
            ':miasto' => $miasto,
            ':ulica' => $ulica,
            ':nr_budynku' => $nr_budynku,
            ':kod_poczt' => $kod_poczt,
            ':nazwa' => $nazwa,
            ':rodzaj' => $rodzaj,
            ':nip' => $nip,
        ];
        
        $result = fetchData($statement, $params);
    
        if (is_array($result)) {
            header("Location: index.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj nową szkołę</title>
</head>
<body>
    <h1>Dodaj nową szkołę</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;">Błąd: <?=htmlspecialchars($error)?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label for="miasto">Miasto</label>
        <input type="text" name="miasto" id="miasto" placeholder="Podaj miasto" required>
        <br><br>
        <label for="miasto">Ulica</label>
        <input type="text" name="ulica" id="ulica" placeholder="Podaj ulicę" required>
        <br><br>
        <label for="nr_budynku">Numer budynku</label>
        <input type="text" name="nr_budynku" id="nr_budynku" placeholder="Podaj numer budynku">
        <br><br>
        <label for="kod_poczt">Kod pocztowy</label>
        <input type="text" name="kod_poczt" id="kod_poczt" placeholder="Podaj kod pocztowy">
        <br><br>
        <label for="nazwa">Nazwa szkoły</label>
        <input type="text" name="nazwa" id="nazwa" placeholder="Podaj nazwę">
        <br><br>
        <label for="rodzaj">Rodzaj</label>
        <input type="text" name="rodzaj" id="rodzaj" placeholder="Podaj rodzaj">
        <br><br>
        <label for="nip">Nip</label>
        <input type="text" name="nip" id="nip" placeholder="Podaj nip">
        <br><br>
        <button type="submit">Dodaj szkołę</button>
    </form>
</body>
</html>