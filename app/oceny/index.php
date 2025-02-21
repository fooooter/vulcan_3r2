<?php
require_once __DIR__ . "/../../db/connection.php";

$query_oceny = "SELECT  oceny.id AS                 'oceny_id',
                        dict_typy_ocen.ocena AS     'oceny_typy_ocena',
                        oceny.tytul_oceny AS        'oceny_tytul',
                        oceny.opis_oceny AS         'oceny_opis',
                        dict_przedmioty.nazwa AS    'oceny_przedmiot',
                        pracownicy.imie AS			'oceny_pracownik_imie',
                        pracownicy.nazwisko AS		'oceny_pracownik_nazwisko',
                        uczniowie.imie AS 			'oceny_uczen_imie',
                        uczniowie.nazwisko AS		'oceny_uczen_nazwisko',
                        oceny.data_wystawienia AS   'oceny_data'
                FROM oceny
                JOIN dict_przedmioty ON dict_przedmioty.id = oceny.przedmiot_id
                JOIN pracownicy ON pracownicy.id  = oceny.pracownik_id
                JOIN uczniowie ON uczniowie.id = oceny.uczen_id
                JOIN dict_typy_ocen ON dict_typy_ocen.id = oceny.typ_oceny";


$statement = $connection->prepare($query_oceny);
$statement->execute();
$oceny = fetchData($statement);

if ($oceny instanceof DbError) {
    echo "Wystąpił błąd: " . $oceny->name;
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista ocen</title>
    <link rel="stylesheet" href="../../css/main.css">
</head>
<body>
<table class="w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Typ</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Przedmiot</th>
                <th>Imię Pracownika</th>
                <th>Nazwisko Pracownika</th>
                <th>Imię Ucznia</th>
                <th>Nazwisko Ucznia</th>
                <th>Data Wystawienia</th>
                <th>Szczegóły</th>
                <th>Aktualizuj</th> 
            </tr>
        </thead>
        <tbody>
            <?php foreach ($oceny as $rekord): ?>
                <tr>
                    <td><?= htmlspecialchars($rekord['oceny_id']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_typy_ocena']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_tytul']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_opis']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_przedmiot']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_pracownik_imie']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_pracownik_nazwisko']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_uczen_imie']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_uczen_nazwisko']); ?></td>
                    <td><?= htmlspecialchars($rekord['oceny_data']); ?></td>
                    <td><a class="btn btn-accent" href="read.php?id=<?= $rekord['oceny_id'] ?>">Szczegóły</a></td>
                    <td><a class="btn btn-secondary" href="update.php?id=<?= $rekord['oceny_id'] ?>">Aktualizuj</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a class="btn btn-primary" href="create.php">Dodaj ocenę</a>
</body>
</html>
