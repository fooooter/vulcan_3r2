-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 20, 2025 at 07:58 PM
-- Wersja serwera: 11.7.2-MariaDB
-- Wersja PHP: 8.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `vulcan_3r2`
--

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `kalendarz` (IN `dt_start` DATE, IN `dt_stop` DATE, IN `id_szkoly` INT)  MODIFIES SQL DATA BEGIN
    DECLARE currDate DATE DEFAULT dt_start;
    DECLARE typeDay  VARCHAR(15) DEFAULT 'Dzień roboczy';
    DECLARE dzTyg    VARCHAR(12) DEFAULT 'Niedziela';
    DECLARE stat     CHAR(3)     DEFAULT 'R';
    
    WHILE currDate <= dt_stop DO  
        SET typeDay  = 'Dzień roboczy';
        SET stat     = 'R';
        CASE DAYOFWEEK(currDate)
			WHEN 1 THEN SET dzTyg = 'Niedziela', typeDay = 'Święto', stat = 'W';
			WHEN 2 THEN SET dzTyg = 'Poniedziałek';
            WHEN 3 THEN SET dzTyg = 'Wtorek';
            WHEN 4 THEN SET dzTyg = 'Środa';
            WHEN 5 THEN SET dzTyg = 'Czwartek';
            WHEN 6 THEN SET dzTyg = 'Piątek';
            WHEN 7 THEN SET dzTyg = 'Sobota', typeDay = 'Święto', stat = 'W';
		END CASE;
            
        INSERT INTO kalendarz (data, szkola_id, nazwa, dzientyg, status) 
        VALUES (currDate, id_szkoly, typeDay, dzTyg, stat);
        SET currDate = DATE_ADD(currDate ,INTERVAL 1 day);
    
    END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `plan` (IN `dt_start` DATE, IN `dt_stop` DATE, IN `id_szkoly` INT)  MODIFIES SQL DATA BEGIN
    DECLARE currDate DATE DEFAULT dt_start;
  
    WHILE currDate <= dt_stop DO      
        IF EXISTS (SELECT * 
                     FROM kalendarz 
                    WHERE kalendarz.data = currDate
                      AND kalendarz.szkola_id = id_szkoly
                      AND kalendarz.status = "R")
        THEN 
            INSERT INTO plan (plan.szkola_id, plan.oddzial_id, plan.przedmiot_id, plan.pracownik_id, plan.godz_lek, plan.data, plan.sala)
    		SELECT  harmonogram.szkola_id,
    				harmonogram.oddzial_id,
            		harmonogram.przedmiot_id,
            		harmonogram.pracownik_id,
            		harmonogram.godz_lek,
            		currDate,
            		harmonogram.sala
       		  FROM  harmonogram   
             WHERE  harmonogram.szkola_id  = szkolaId
               AND  harmonogram.dzien_tyg  = DAYOFWEEK(currDate);
  		
       END IF;
       SET currDate = DATE_ADD(currDate, INTERVAL 1 day);  
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dict_przedmioty`
--

CREATE TABLE `dict_przedmioty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `dict_przedmioty`
--

INSERT INTO `dict_przedmioty` (`id`, `nazwa`) VALUES
(1, 'Język Polski'),
(2, 'Matematyka'),
(3, 'Historia'),
(4, 'Język Niemiecki'),
(5, 'Język Angielski'),
(6, 'Biologia'),
(7, 'Geografia'),
(8, 'Religia'),
(9, 'Etyka'),
(10, 'WDŻ'),
(11, 'Godzina Wychowawcza'),
(12, 'Chemia'),
(13, 'Fizyka'),
(14, 'WF'),
(15, 'Historia i teraźniejszość'),
(16, 'Informatyka'),
(17, 'Podstawy przedsiębiorczości');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dict_stanowiska`
--

CREATE TABLE `dict_stanowiska` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(64) NOT NULL,
  `min_zarobki` float NOT NULL,
  `max_zarobki` float NOT NULL,
  `szkola_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dict_typy_ocen`
--

CREATE TABLE `dict_typy_ocen` (
  `id` int(11) NOT NULL,
  `ocena` varchar(25) NOT NULL,
  `wartosc` float DEFAULT NULL,
  `szkola_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `egzaminy`
--

CREATE TABLE `egzaminy` (
  `id` int(10) NOT NULL,
  `szkola_id` int(10) NOT NULL,
  `oddzial_id` int(10) NOT NULL,
  `nr_zdaj` int(15) NOT NULL,
  `dopuszczony` enum('TAK','NIE') NOT NULL,
  `kwalifikacja` varchar(15) DEFAULT NULL,
  `typ_egzaminu` enum('praktyczny','teoretyczny','matura') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `egz_spec`
--

CREATE TABLE `egz_spec` (
  `id` int(10) NOT NULL,
  `uczen_id` int(10) NOT NULL,
  `etykieta_egz_id` int(10) NOT NULL,
  `ilosc_pkt` int(3) NOT NULL,
  `termin` enum('0','1','2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `harmonogram`
--

CREATE TABLE `harmonogram` (
  `szkola_id` int(11) NOT NULL,
  `oddzial_id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `pracownik_id` int(11) NOT NULL,
  `godz_lek` tinyint(4) NOT NULL,
  `sala` varchar(11) NOT NULL,
  `dzien_tyg` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `jt_prac_stan`
--

CREATE TABLE `jt_prac_stan` (
  `pracownicy_id` int(11) NOT NULL COMMENT 'id pracownika',
  `stanowisko_id` int(11) NOT NULL COMMENT 'id przypisanego mu stanowiska'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kalendarz`
--

CREATE TABLE `kalendarz` (
  `data` date NOT NULL,
  `szkola_id` int(11) NOT NULL,
  `nazwa` varchar(64) NOT NULL,
  `dzien_tyg` varchar(12) NOT NULL,
  `status` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'id oceny',
  `typ_oceny` int(11) NOT NULL COMMENT 'połączenie z tabelką typy_ocen (6/6+/6- ...)',
  `tytul_oceny` varchar(255) NOT NULL COMMENT 'tytuł oceny (np. Sprawdzian wiedzy ...)',
  `opis_oceny` varchar(255) DEFAULT NULL COMMENT 'opis słowny oceny',
  `przedmiot_id` int(11) NOT NULL,
  `pracownik_id` int(11) NOT NULL,
  `uczen_id` int(11) NOT NULL,
  `data_wystawienia` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'data dodania oceny w dzienniku'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oddzialy`
--

CREATE TABLE `oddzialy` (
  `id` int(10) NOT NULL,
  `nazwa_oddzialu` varchar(75) DEFAULT NULL COMMENT 'np."Technik Programista", "Biol-Chem"',
  `oddzial` varchar(10) NOT NULL COMMENT 'np"1A", ''8C'', "3R"',
  `grupa` int(10) UNSIGNED DEFAULT NULL,
  `szkola_id` int(10) NOT NULL,
  `pracownik_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `plan`
--

CREATE TABLE `plan` (
  `szkola_id` int(11) NOT NULL,
  `oddzial_id` int(11) NOT NULL,
  `przedmiot_id` int(11) NOT NULL,
  `pracownik_id` int(11) NOT NULL,
  `godz_lek` tinyint(4) NOT NULL,
  `data` date NOT NULL,
  `sala` varchar(11) NOT NULL,
  `temat` varchar(255) DEFAULT NULL,
  `status` enum('odwolane','zastepstwo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci COMMENT='Tabela plan, status gdy jest null, oznacza to, że lekcja odbywa się normalnie';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `id` int(11) NOT NULL,
  `szkola_id` int(11) NOT NULL,
  `nazwisko` varchar(30) NOT NULL,
  `imie` varchar(30) NOT NULL,
  `imie2` varchar(30) DEFAULT NULL,
  `pesel` char(11) NOT NULL,
  `miasto` varchar(64) NOT NULL,
  `ulica` varchar(40) NOT NULL,
  `nr_domu` varchar(40) NOT NULL,
  `kod_pocztowy` varchar(5) NOT NULL,
  `kraj` varchar(40) NOT NULL,
  `narodowosc` varchar(40) NOT NULL,
  `zarobki` decimal(10,0) NOT NULL,
  `plec` enum('K','M') NOT NULL,
  `wyksztalcenie` varchar(128) NOT NULL,
  `data_zatr` date NOT NULL,
  `data_zwo` date DEFAULT NULL,
  `nr_tel` varchar(9) NOT NULL,
  `email` varchar(40) NOT NULL,
  `uzytk` varchar(64) NOT NULL,
  `hash` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `szkoly`
--

CREATE TABLE `szkoly` (
  `id` int(11) NOT NULL,
  `miasto` varchar(64) NOT NULL,
  `ulica` varchar(64) NOT NULL,
  `nr_budynku` varchar(9) NOT NULL,
  `kod_poczt` varchar(5) NOT NULL,
  `nazwa` varchar(128) NOT NULL,
  `rodzaj` varchar(32) NOT NULL,
  `nip` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uczniowie`
--

CREATE TABLE `uczniowie` (
  `id` int(11) NOT NULL,
  `nazwisko` varchar(32) NOT NULL,
  `imie` varchar(32) NOT NULL,
  `imie2` varchar(32) DEFAULT NULL,
  `data_ur` date NOT NULL,
  `pesel` varchar(11) NOT NULL,
  `kraj` varchar(64) NOT NULL,
  `miasto` varchar(64) NOT NULL,
  `ulica` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `nr_tel` varchar(9) NOT NULL,
  `plec` enum('M','K') NOT NULL,
  `narodowosc` varchar(64) NOT NULL,
  `kod_pocztowy` varchar(5) NOT NULL,
  `nr_domu` varchar(40) NOT NULL,
  `uzytk` varchar(64) NOT NULL,
  `hash` varchar(60) NOT NULL,
  `oddzial_id` int(11) NOT NULL,
  `szkola_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uwagi`
--

CREATE TABLE `uwagi` (
  `id` int(11) NOT NULL,
  `uczen_id` int(11) NOT NULL,
  `typ_uwagi` enum('pozytywna','negatywna') NOT NULL,
  `data` date NOT NULL,
  `godzina` time NOT NULL,
  `tresc` text NOT NULL,
  `pracownik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `dict_przedmioty`
--
ALTER TABLE `dict_przedmioty`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `dict_stanowiska`
--
ALTER TABLE `dict_stanowiska`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dictstan_szkolaid_fk` (`szkola_id`);

--
-- Indeksy dla tabeli `dict_typy_ocen`
--
ALTER TABLE `dict_typy_ocen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ix_ocena_szkola` (`ocena`,`szkola_id`),
  ADD KEY `dicttypyocen_szkolaid_fk` (`szkola_id`);

--
-- Indeksy dla tabeli `egzaminy`
--
ALTER TABLE `egzaminy`
  ADD PRIMARY KEY (`id`,`szkola_id`,`oddzial_id`) USING BTREE,
  ADD KEY `egzaminy_szkolaid_fk` (`szkola_id`),
  ADD KEY `egzaminy_oddzialid_fk` (`oddzial_id`);

--
-- Indeksy dla tabeli `egz_spec`
--
ALTER TABLE `egz_spec`
  ADD PRIMARY KEY (`id`,`uczen_id`,`etykieta_egz_id`);

--
-- Indeksy dla tabeli `harmonogram`
--
ALTER TABLE `harmonogram`
  ADD PRIMARY KEY (`szkola_id`,`oddzial_id`,`godz_lek`,`dzien_tyg`) USING BTREE,
  ADD KEY `harmonogram_pracownikid_fk` (`pracownik_id`),
  ADD KEY `harmonogram_oddzialid_fk` (`oddzial_id`),
  ADD KEY `harmonogram_przedmiotid_fk` (`przedmiot_id`);

--
-- Indeksy dla tabeli `jt_prac_stan`
--
ALTER TABLE `jt_prac_stan`
  ADD PRIMARY KEY (`pracownicy_id`,`stanowisko_id`),
  ADD KEY `pracstan_stanowiskoid_fk` (`stanowisko_id`);

--
-- Indeksy dla tabeli `kalendarz`
--
ALTER TABLE `kalendarz`
  ADD PRIMARY KEY (`data`,`szkola_id`),
  ADD KEY `kalendarz_szkolaid_fk` (`szkola_id`);

--
-- Indeksy dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_data` (`data_wystawienia`),
  ADD KEY `ix_tytul` (`tytul_oceny`),
  ADD KEY `oceny_pracownikid_fk` (`pracownik_id`),
  ADD KEY `oceny_przedmiotid_fk` (`przedmiot_id`),
  ADD KEY `oceny_uczenid_fk` (`uczen_id`),
  ADD KEY `oceny_typocenyid_fk` (`typ_oceny`);

--
-- Indeksy dla tabeli `oddzialy`
--
ALTER TABLE `oddzialy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oddzialy_szkolaid_fk` (`szkola_id`) USING BTREE,
  ADD KEY `oddzialy_pracownikid_fk` (`pracownik_id`) USING BTREE;

--
-- Indeksy dla tabeli `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`szkola_id`,`oddzial_id`,`godz_lek`,`data`),
  ADD KEY `plan_oddzialid_fk` (`oddzial_id`),
  ADD KEY `plan_pracownikid_fk` (`pracownik_id`),
  ADD KEY `plan_przedmiotid_fk` (`przedmiot_id`);

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uzytk` (`uzytk`) USING BTREE,
  ADD KEY `pracownicy_szkolaid_fk` (`szkola_id`);

--
-- Indeksy dla tabeli `szkoly`
--
ALTER TABLE `szkoly`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uzytk` (`uzytk`) USING BTREE,
  ADD KEY `uczniowie_szkolaid_fk` (`szkola_id`),
  ADD KEY `uczniowie_oddzialid_fk` (`oddzial_id`) USING BTREE;

--
-- Indeksy dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uwagi_uczenid_fk` (`uczen_id`),
  ADD KEY `uwagi_pracownikid_fk` (`pracownik_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `dict_przedmioty`
--
ALTER TABLE `dict_przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `dict_stanowiska`
--
ALTER TABLE `dict_stanowiska`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `dict_typy_ocen`
--
ALTER TABLE `dict_typy_ocen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id oceny';

--
-- AUTO_INCREMENT dla tabeli `oddzialy`
--
ALTER TABLE `oddzialy`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `szkoly`
--
ALTER TABLE `szkoly`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `dict_stanowiska`
--
ALTER TABLE `dict_stanowiska`
  ADD CONSTRAINT `dictstan_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `dict_typy_ocen`
--
ALTER TABLE `dict_typy_ocen`
  ADD CONSTRAINT `dicttypyocen_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `egzaminy`
--
ALTER TABLE `egzaminy`
  ADD CONSTRAINT `egzaminy_oddzialid_fk` FOREIGN KEY (`oddzial_id`) REFERENCES `oddzialy` (`id`),
  ADD CONSTRAINT `egzaminy_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `harmonogram`
--
ALTER TABLE `harmonogram`
  ADD CONSTRAINT `harmonogram_oddzialid_fk` FOREIGN KEY (`oddzial_id`) REFERENCES `oddzialy` (`id`),
  ADD CONSTRAINT `harmonogram_pracownikid_fk` FOREIGN KEY (`pracownik_id`) REFERENCES `pracownicy` (`id`),
  ADD CONSTRAINT `harmonogram_przedmiotid_fk` FOREIGN KEY (`przedmiot_id`) REFERENCES `dict_przedmioty` (`id`),
  ADD CONSTRAINT `harmonogram_szkolyid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `jt_prac_stan`
--
ALTER TABLE `jt_prac_stan`
  ADD CONSTRAINT `pracstan_pracownicyid_fk` FOREIGN KEY (`pracownicy_id`) REFERENCES `pracownicy` (`id`),
  ADD CONSTRAINT `pracstan_stanowiskoid_fk` FOREIGN KEY (`stanowisko_id`) REFERENCES `dict_stanowiska` (`id`);

--
-- Ograniczenia dla tabeli `kalendarz`
--
ALTER TABLE `kalendarz`
  ADD CONSTRAINT `kalendarz_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD CONSTRAINT `oceny_pracownikid_fk` FOREIGN KEY (`pracownik_id`) REFERENCES `pracownicy` (`id`),
  ADD CONSTRAINT `oceny_przedmiotid_fk` FOREIGN KEY (`przedmiot_id`) REFERENCES `dict_przedmioty` (`id`),
  ADD CONSTRAINT `oceny_typocenyid_fk` FOREIGN KEY (`typ_oceny`) REFERENCES `dict_typy_ocen` (`id`),
  ADD CONSTRAINT `oceny_uczenid_fk` FOREIGN KEY (`uczen_id`) REFERENCES `uczniowie` (`id`);

--
-- Ograniczenia dla tabeli `oddzialy`
--
ALTER TABLE `oddzialy`
  ADD CONSTRAINT `oddzialy_pracownikid_fk` FOREIGN KEY (`pracownik_id`) REFERENCES `pracownicy` (`id`),
  ADD CONSTRAINT `oddzialy_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `plan_oddzialid_fk` FOREIGN KEY (`oddzial_id`) REFERENCES `oddzialy` (`id`),
  ADD CONSTRAINT `plan_pracownikid_fk` FOREIGN KEY (`pracownik_id`) REFERENCES `pracownicy` (`id`),
  ADD CONSTRAINT `plan_przedmiotid_fk` FOREIGN KEY (`przedmiot_id`) REFERENCES `dict_przedmioty` (`id`),
  ADD CONSTRAINT `plan_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD CONSTRAINT `pracownicy_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  ADD CONSTRAINT `uczniowie_oddzialid_fk` FOREIGN KEY (`oddzial_id`) REFERENCES `oddzialy` (`id`),
  ADD CONSTRAINT `uczniowie_szkolaid_fk` FOREIGN KEY (`szkola_id`) REFERENCES `szkoly` (`id`);

--
-- Ograniczenia dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  ADD CONSTRAINT `uwagi_pracownikid_fk` FOREIGN KEY (`pracownik_id`) REFERENCES `pracownicy` (`id`),
  ADD CONSTRAINT `uwagi_uczenid_fk` FOREIGN KEY (`uczen_id`) REFERENCES `uczniowie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
