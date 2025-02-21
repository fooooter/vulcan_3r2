<?php
require_once __DIR__ . "/../../db/connection.php";

if (isset($_POST['add'])) {
    $szkola_id = $_POST['szkola_id'];
    $data = $_POST['data'];
    $dzientyg = $_POST['dzientyg'];
    $nazwa = $_POST['nazwa'];
    $status = $_POST['status'];
    
    $query = "INSERT INTO kalendarz (szkola_id, data, dzien_tyg, nazwa, status) 
              VALUES (:szkola_id, :data, :dzientyg, :nazwa, :status)";
    $stmt = $connection->prepare($query);
    $stmt->execute([
        ':szkola_id' => $szkola_id, 
        ':data' => $data, 
        ':dzientyg' => $dzientyg, 
        ':nazwa' => $nazwa, 
        ':status' => $status
    ]);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie użytkownikami</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <h1>Dodaj nowy rekord</h1>
    <form method="POST">
        <input type="number" name="szkola_id" placeholder="ID szkoły" required>
        <input type="date" name="data" placeholder="Data" required>
        <input type="text" name="dzientyg" placeholder="Dzień tygodnia" required>
        <input type="text" name="nazwa" placeholder="Nazwa" required>
        <select name="status" required>
            <option value="W">Wolny</option>
            <option value="R">Roboczy</option>
        </select>
        <button type="submit" name="add">Dodaj</button>
    </form>
</body>
</html>
