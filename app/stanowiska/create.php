<?php
require '../../db/connection.php';
if ($_POST) {
    $connection->prepare("INSERT INTO stanowiska (nazwa) VALUES (?)")->execute([$_POST['nazwa']]);
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dodaj stanowisko</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f4f4f4; }
        .container { width: 50%; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { color: #333; }
        form { display: flex; flex-direction: column; align-items: center; }
        input, button { padding: 10px; margin: 5px; border-radius: 5px; }
        button { background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dodaj stanowisko</h1>
        <form method="post">
            <input type="text" name="nazwa" required>
            <button type="submit">Dodaj</button>
        </form>
    </div>
</body>
</html>