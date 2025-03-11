<?php
require '../../db/connection.php';
$stanowiska = $connection->query("SELECT * FROM stanowiska")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stanowiska</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f4f4f4; }
        h1 { color: #333; }
        .container { width: 50%; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        ul { list-style: none; padding: 0; }
        li { margin: 10px 0; padding: 10px; background: #fff; border-radius: 5px; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); display: flex; justify-content: space-between; align-items: center; }
        a, button { padding: 10px; background: #007bff; color: white; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; }
        a:hover, button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista stanowisk</h1>
        <a href="create.php">Dodaj nowe stanowisko</a>
        <ul>
            <?php foreach ($stanowiska as $s): ?>
                <li><?= htmlspecialchars($s['nazwa']) ?> <a href="update.php?id=<?= $s['id'] ?>">Edytuj</a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>