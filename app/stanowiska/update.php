<?php
require '../../db/connection.php';
$id = $_GET['id'];
$stanowisko = $connection->prepare("SELECT * FROM stanowiska WHERE id = ?");
$stanowisko->execute([$id]);
$stanowisko = $stanowisko->fetch();
if ($_POST) {
    $connection->prepare("UPDATE stanowiska SET nazwa = ? WHERE id = ?")->execute([$_POST['nazwa'], $id]);
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edytuj stanowisko</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f4f4f4; }
        .container { width: 50%; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { color: #333; }
        form { display: flex; flex-direction: column; align-items: center; }
        input, button { padding: 10px; margin: 5px; border-radius: 5px; }
        button { background: #ffc107; color: black; border: none; cursor: pointer; }
        button:hover { background: #e0a800; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edytuj stanowisko</h1>
        <form method="post">
            <input type="text" name="nazwa" value="<?= htmlspecialchars($stanowisko['nazwa']) ?>" required>
            <button type="submit">Zapisz</button>
        </form>
    </div>
</body>
</html>