<?php

require_once(__DIR__ . "..\..\..\db\connection.php");

session_start();

$statementSzkoly = $connection->prepare("SELECT id, nazwa FROM szkoly");
$statementOddzialy = $connection->prepare("SELECT id, oddzial FROM oddzialy");
$id_szkol = fetchData($statementSzkoly);
$id_odzialow = fetchData($statementOddzialy);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $miasto = empty($_POST['miasto']) ? null : htmlspecialchars(trim($_POST['miasto']));
    $ulica = empty($_POST['ulica']) ? null : htmlspecialchars(trim($_POST['ulica']));
    $nr_budynku = empty($_POST['nr_budynku']) ? null : htmlspecialchars(trim($_POST['nr_budynku']));
    $kod_poczt = empty($_POST['kod_poczt']) ? null : htmlspecialchars(trim($_POST['kod_poczt']));
    $nazwa = empty($_POST['nazwa']) ? null : htmlspecialchars(trim($_POST['nazwa']));
    $rodzaj = empty($_POST['rodzaj']) ? null : htmlspecialchars(trim($_POST['rodzaj']));
    $nip = empty($_POST['nip']) ? null : htmlspecialchars(trim($_POST['nip']));

    $params = [
        ':miasto' => $miasto,
        ':ulica' => $ulica,
        ':nr_budynku' => $nr_budynku,
        ':kod_poczt' => $kod_poczt,
        ':nazwa' => $nazwa,
        ':rodzaj' => $rodzaj,
        ':nip' => $nip,
    ];
    
    $statement = $connection->prepare("insert into szkoly(id, miasto, ulica, nr_budynku, kod_poczt, nazwa, rodzaj, nip) 
            values(null, :miasto, :ulica, :nr_budynku, :kod_poczt, :nazwa, :rodzaj, :nip)");
    $result = fetchData($statement, $params);

    if ($result instanceof DbError) {
        echo "jest jakis błąd - to trzeba dopracować";
    } else {
        echo "Poprawnie wprowadzono dane!";
    }
}

?>
<!DOCTYPE html>
<html lang="pl">

<!-- <head>
    <?php //require_once(__DIR__ . "\..\layout\head.php");?>
</head> -->

<body>
    <main class="d-flex flex-nowrap">
        <form class="form" action="" method="post">
            <div class="container">
                <div class="row">
                    <div class="col-md-auto">
                        <h4 class="border-bottom border-dark">Dane szkoły</h4>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="miasto" class="sr-only">Miasto</label>
                            <input type="text" class="form-control" id="miasto" placeholder="Podaj miasto" name="miasto" required>
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="ulica" class="sr-only">Ulica</label>
                            <input type="text" class="form-control" id="ulica" placeholder="Podaj ulicę" name="ulica" required>
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="nr_budynku" class="sr-only">Numer budynku</label>
                            <input type="text" class="form-control" id="nr_budynku" placeholder="Podaj numer budynku" name="nr_budynku">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="kod_poczt" class="sr-only">Kod pocztowy</label>
                            <input type="text" class="form-control" id="kod_poczt" placeholder="Podaj kod pocztowy" name="kod_poczt">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="nazwa" class="sr-only">Nazwa</label>
                            <input type="text" class="form-control" id="nazwa" placeholder="Podaj nazwę" name="nazwa">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="rodzaj" class="sr-only">Rodzaj</label>
                            <input type="text" class="form-control" id="rodzaj" placeholder="Podaj rodzaj" name="rodzaj">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="nip" class="sr-only">Nip</label>
                            <input type="text" class="form-control" id="nip" placeholder="Podaj nip" name="nip">
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" name="dodajszkole" class="btn btn-primary py-2 mx-sm-3">Dodaj szkołę</button>
            <button type="reset" name="dodajszkole" class="btn btn-secondary py-2">Reset</button>
        </form>
</body>

</html>