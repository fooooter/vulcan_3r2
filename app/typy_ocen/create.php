<?php

require_once(__DIR__ . "/../../db/connection.php");

session_start();

$statementSzkoly = $connection->prepare("SELECT id, nazwa FROM szkoly");
$id_szkol = fetchData($statementSzkoly);

// $id_odzialow = fetchData($statementOddzialy);

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $ocena = empty($_POST['ocena']) ? null : $_POST['ocena'];
//     $wartosc = empty($_POST['wartosc']) ? null : $_POST['wartosc'];
//     $idSzkoly = empty($_SESSION['szkola_id']) ? 1 : $_SESSION['szkola_id'] ;
//     $params = [
//         ':ocena' => $ocena,
//         ':wartosc' => $wartosc ,
//         ':szkola_id' => $idSzkoly  
//     ];

//     $statement = $connection->prepare("INSERT INTO typy_ocen(id, ocena , wartosc,szkola_id)  VALUES(null, :ocena, :wartosc , :szkola_id");
//     $result = fetchData($statement, $params);

//     echo $result;
//     if ($result instanceof DbError) {
//         echo "jest jakiś błąd - to trzeba dopracować";
//     } else {
//         echo "Poprawnie wprowadzono dane!";
//     }
// }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $ocena = isset($_POST['ocena']) ? null : $_POST['ocena']; 
    // $wartosc = isset($_POST['wartosc']) ? null : $_POST['wartosc'];
    // $idSzkoly = empty($_SESSION['szkola_id']) ? null : $_SESSION['szkola_id'];

    $ocena = isset($_POST['ocena']) ? $_POST['ocena'] : null; 
    $wartosc = isset($_POST['wartosc']) ? $_POST['wartosc'] : null; 
    $idSzkoly = isset($_SESSION['szkola_id']) ? $_SESSION['szkola_id'] : null;

    $params = [
        ':ocena' => $ocena,
        ':wartosc' => $wartosc,
        ':szkola_id' => $idSzkoly  
    ];

    try {
        // POPRAWIONE ZAPYTANIE SQL
        $statement = $connection->prepare("INSERT INTO dict_typy_ocen (ocena, wartosc, szkola_id) VALUES (:ocena, :wartosc, :szkola_id)");
        
        // UŻYWAMY `execute()` ZAMIAST `fetchData()`
        if ($statement->execute($params)) {
            echo "✅ Poprawnie wprowadzono dane!";
            // sleep(3);
            // header('Location:index.php');
        } else {
            echo "❌ Błąd podczas wprowadzania danych.";
        }

    } catch (PDOException $e) {
        echo "❌ Błąd SQL: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj ucznia</title>
</head>

<body>
    <main>
        <form action="" method="post">
            <div>
                <h1>Dodaj nowy typ oceny</h1>
                <div style="margin-bottom: 0.5%">
                    <label for="ocena">Ocena *</label>
                    <input type="text" id="ocena" placeholder="Podaj ocenę" name="ocena" required>
                </div>
                <div style="margin-bottom: 0.5%">
                    <label for="wartosc">Wartość oceny</label>
                    <input type="text" id="wartosc" placeholder="Podaj wartość oceny" name="wartosc" required>
                </div>
                <button type="submit" name="dodajUcznia">Dodaj typ oceny</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </main>
    <a href="index.php"><button>Powrót</button></a>
</body>

</html>
