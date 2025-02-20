<?php
require_once __DIR__ . "../../../db/connection.php";   
$query = "SELECT id, nazwa FROM szkoly";

$statement = $connection->prepare($query);

$id_szkol = fetchData($statement);

if ($id_szkol instanceof DbError) {
    echo "Wystąpił błąd: " . $egzaminy->name;
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj egzaminy</title>
</head>
<body>
    <form action="" method="post">
        <label for="szkola_id"></label>
        <select name="szkola_id" id="szkola_id">
            <?php foreach($id_szkol as $id): ?>
                // TODO: Zrobić to
            <?php endforeach; ?>
        </select>

    </form>
</body>
</html>