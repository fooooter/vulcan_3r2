<?php
require_once '../../db/connection.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "DELETE FROM uwagi WHERE id = :id";
$stmt = $connection->prepare($sql);
$result = fetchData($stmt, [':id' => $id]);

if (is_array($result)) {
    header("Location: index.php");
    exit;
} else {
    echo "Błąd usuwania: " . htmlspecialchars($result);
}
