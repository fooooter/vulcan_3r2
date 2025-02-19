[![Discord](https://img.shields.io/badge/Discord-gray?logo=discord)](https://discord.gg/DrDUJGjd)
# Vulcan
Projekt bazy danych dziennika szkolnego na tworzenie i zarządzanie bazami danych.

## Connection
Zawiera następującą strukturę

```
|-app
|-db
  |-config.php
  |-connection.php
```


Struktura znajduje się w vulcan_3r2.sql oraz w #zasoby na serwerze Discord.

Tworząc formularz, należy stworzyć folder o nazwie tabeli (ze względów organizacyjnych), w którym powinny znaleźć się następujące pliki:
- index.php - służy do wyświetlenia wszystkich rekordów z tabeli
- create.php - służy do dodawania danych do tabeli
- update.php - służy do aktualizacji danych rekordu

W rzadkich przypadkach dopuszcza się jeszcze stworzenie delete.php, ale tylko po konsultacji na #⁠konsultacje na serwerze Discord, w issues albo przez dobrze opisanego pull requesta.

### Plik connection.php
W pliku connection.php znajdują się:

- zmienna `$connection` - odpowiada za połączenie z bazą danych
- funkcja `fetchData` - służy do wykonania zapytania SQL
- typ enumeracyjny `DbError` - określa rodzaj błędu wyrzuconego przez funkcję `fetchData`

Funkcja `fetchData` przyjmuje jako argumenty `PDOStatement $statement` oraz array `$params` i zwraca jedno z poniższych:

- `array` - jeżeli zapytanie wykonało się prawidłowo, będzie to tablica ze wszystkimi danymi
- `string` - wiadomość błędu (jeżeli `$config['debug'] == true`)
- `DbError` - rodzaj błędu (jeżeli `$config['debug'] == false`)

W przypadku wszelkich pytań proszę pisać na kanale ⁠#konsultacje na serwerze Discord albo w issues.
