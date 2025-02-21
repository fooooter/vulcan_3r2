<?php
    session_start();
    require_once(__DIR__ . "/../../db/connection.php");
    $select= "SELECT id , nazwa from szkoly";
    $stmt = $connection->prepare($select);
    $dane = fetchData($stmt);

    if($dane instanceof DbError){
        echo $dane->name;
        exit;
    }
    $idSzkoly = $_POST['idszkoly'] ?? null;
    if($idSzkoly !=null && $_SERVER['REQUEST_METHOD']=='POST'){
        $_SESSION['szkola_id'] = $idSzkoly;
        header('Location:./create.php');
    }

?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post"> 
        <label for="idszkoly"> Wybór szkoły </label>
        <select name="idszkoly" id="idszkoly">
            <?php foreach ($dane as $d): ?>
                    <option value="<?= $d[0]?>"> <?= $d[1] ?> </option>
                <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Wybierz">
    </form>
</body>
</html>