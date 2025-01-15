-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Creato il: Gen 13, 2025 alle 14:44
-- Versione del server: 10.6.7-MariaDB-1:10.6.7+maria~focal
-- Versione PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tecweb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `categoria`
--

CREATE TABLE `categoria` (
  `cat` varchar(250) NOT NULL,
  `nomeEsteso` varchar(250) NOT NULL,
  `descrizione` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `categoria`
--

INSERT INTO `categoria` (`cat`, `nomeEsteso`, `descrizione`) VALUES
('Classica', 'Le nostre pizze classiche', 'I gusti classici non tramontano mai: questi sapori sono la prova che le cose migliori non hanno bisogno di complicazioni.'),
('Fuori menu', 'I nostri Fuori menu', 'Descrizione Fuori menu'),
('Speciale', 'Le nostre pizze speciali', 'Non è solo una pizza, è un’esperienza: ogni sapore speciale ha una storia da raccontare.'),
('Stagionale', 'Le nostre pizze stagionali', 'La nostra passione ci spinge a scoprire sempre cose nuove: proponiamo qualcosa di diverso, i sapori stagionali!');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `checkOrari`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `checkOrari` (
`orario` varchar(250)
,`pizze` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `cucina`
--

CREATE TABLE `cucina` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT 0,
  `path` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `cucina`
--

INSERT INTO `cucina` (`id`, `nome`, `prezzo`, `veget`, `path`) VALUES
(1, 'frittura', 11, 1, '../../../assets/icons/pizza_icon.png'),
(2, 'frittura2', 11, 1, '../../../assets/icons/pizza_icon.png'),
(3, 'prova', 23, 0, '../../../assets/icons/piatto_icon.png'),
(4, 'prova5', 3, 0, '../../../assets/icons/piatto_icon.png'),
(5, 'prova456', 5, 0, '../../../assets/icons/piatto_icon.png'),
(6, 'prova47', 25, 0, '../../../assets/icons/piatto_icon.png'),
(7, 'grhgdjh', 15, 0, '../../../assets/pizze/'),
(8, 'jtdfhmhfmhykf', 0, 0, '../../../assets/pizze/');

-- --------------------------------------------------------

--
-- Struttura della tabella `cucina_ingrediente`
--

CREATE TABLE `cucina_ingrediente` (
  `cucina` int(11) NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `cucina_ingrediente`
--

INSERT INTO `cucina_ingrediente` (`cucina`, `ingrediente`) VALUES
(2, 'cesco'),
(2, 'crudo'),
(3, 'fior di latte'),
(4, 'Mozzarella'),
(5, 'patate fritte'),
(6, 'fior di latte'),
(6, 'mozzarella di bufala'),
(7, 'Mozzarella'),
(7, 'mozzarella di bufala'),
(7, 'patate fritte'),
(8, 'mozzarella di bufala');

-- --------------------------------------------------------

--
-- Struttura della tabella `disponiblitaorarie`
--

CREATE TABLE `disponiblitaorarie` (
  `fascia` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `disponiblitaorarie`
--

INSERT INTO `disponiblitaorarie` (`fascia`) VALUES
('16:00-16:10'),
('16:10-16:20'),
('16:20-16:30'),
('17:10-17:20'),
('18.00-18.10'),
('18.10-18.20'),
('18.20-18.30'),
('18.30-18.40'),
('18.40-18.50'),
('18.50-19.00'),
('19.00-19.10'),
('19.02-19.30'),
('19.10-19.20'),
('19.30-19.40'),
('19.40-19.50'),
('19.50-20.00'),
('20.00-20.10'),
('20.10-20.20'),
('20.20-20.30'),
('20.30-20.40'),
('20.40-20.50'),
('20.50-21.00'),
('21.00-21.10'),
('21.10-21.20');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente`
--

CREATE TABLE `ingrediente` (
  `nome` varchar(250) NOT NULL,
  `peso` int(11) NOT NULL DEFAULT 3,
  `veget` tinyint(1) DEFAULT 0,
  `pagg` double NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `ingrediente`
--

INSERT INTO `ingrediente` (`nome`, `peso`, `veget`, `pagg`) VALUES
('carciofi', 3, 0, 0.5),
('cesco', 3, 1, 0.5),
('crudo', 3, 0, 2),
('fior di latte', 3, 0, 1.5),
('funghi', 3, 0, 0.5),
('mascarpone', 3, 0, 2.5),
('Mozzarella', 2, 0, 1.5),
('mozzarella di bufala', 3, 0, 1),
('patate fritte', 3, 0, 2),
('pomodoro', 1, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `id` int(11) NOT NULL,
  `utente` varchar(250) NOT NULL,
  `data` date NOT NULL DEFAULT current_timestamp(),
  `ora` varchar(250) NOT NULL,
  `stato` tinyint(1) NOT NULL DEFAULT 0,
  `nota` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `ordine`
--

INSERT INTO `ordine` (`id`, `utente`, `data`, `ora`, `stato`, `nota`) VALUES
(1, 'utente@utente.it', '2025-01-09', '19.40-19.50', 0, NULL),
(2, 'utente@utente.it', '2025-01-09', '19.50-20.00', 0, NULL),
(3, 'utente@utente.it', '2025-01-10', '19.50-20.00', 0, NULL),
(4, 'utente@utente.it', '2025-01-09', '20.10-20.20', 0, NULL),
(5, 'admin@admin.com', '2025-01-13', '18.20-18.30', 0, NULL),
(6, 'admin@admin.com', '2025-01-13', '19.10-19.20', 0, NULL),
(7, 'admin@admin.com', '2025-01-13', '19.40-19.50', 0, NULL),
(8, 'admin@admin.com', '2025-01-13', '19.30-19.40', 0, NULL),
(9, 'admin@admin.com', '2025-01-13', '18.00-18.10', 0, NULL),
(10, 'admin@admin.com', '2025-01-13', '18.00-18.10', 0, NULL),
(11, 'admin@admin.com', '2025-01-13', '18.30-18.40', 0, NULL),
(33, 'admin@admin.com', '2025-01-13', '18.50-19.00', 0, '18.50 - 2zuccGorgo, provath, pesto buf');

-- --------------------------------------------------------

--
-- Struttura della tabella `pizza`
--

CREATE TABLE `pizza` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT 0,
  `categoria` varchar(250) NOT NULL,
  `descrizione` mediumtext DEFAULT NULL,
  `path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `pizza`
--

INSERT INTO `pizza` (`id`, `nome`, `prezzo`, `veget`, `categoria`, `descrizione`, `path`) VALUES
(21, 'Zucca e Gorgonzola', 10, 0, 'Fuori menu', 'aaaaaaaaaaaa', '../../../assets/pizze/FM-zuccagorgo.jpeg'),
(22, 'Pesto e Bufala', 9, 0, 'Fuori menu', 'aaaaaaaaaaaaaaaaaaaa', '../../../assets/pizze/FM-pestobufala.jpeg'),
(36, 'provapath', 8, 0, 'Fuori menu', 'kihilhj', '../../../assets/pizze/FM-calzonenutella.jpeg'),
(41, 'provafm3', 6, 0, 'Fuori menu', 'jdjy', '../../../assets/icons/pizza_icon.png'),
(42, 'yjjyjy', 56, 0, 'Speciale', 'ytjy', '../../../assets/icons/pizza_icon.png'),
(43, 'hmgmh', 3, 0, 'Classica', 'fjhfh', '../../../assets/icons/pizza_icon.png'),
(44, 'pgrkjfbefbett', 5, 0, 'Classica', 'i0+k00ih', '../../../assets/icons/pizza_icon.png'),
(45, 'fbfbgnrtg', 7, 0, 'Classica', 'jtuujt', '../../../assets/icons/pizza_icon.png'),
(46, 'gfgggg3', 3, 0, 'Speciale', 'gvg', '../../../assets/pizze/FM-calzonenutella.jpeg'),
(47, 'prova', 1515, 0, 'Classica', 'grgr', '../../../assets/icons/pizza_icon.png'),
(48, 'francesco', 5, 1, 'Classica', 'ge', '../../../assets/icons/pizza_icon.png'),
(49, 'prova3343', 5, 0, 'Classica', 'ttr', '../../../assets/icons/pizza_icon.png'),
(50, 'jiewijvi jifewier', 44, 1, 'Classica', 'hh', '../../../assets/icons/pizza_icon.png'),
(51, 'prova', 4, 0, 'Classica', 'yyy', '../../../assets/icons/pizza_icon.png'),
(52, 'prova4535', 56, 1, 'Classica', 'jhyj', '../../../assets/icons/pizza_icon.png'),
(53, 'prova567', 564, 0, 'Classica', 'jj', '../../../assets/icons/pizza_icon.png'),
(54, 'rgrggrt', 4, 0, 'Classica', 'tt', '../../../assets/icons/pizza_icon.png'),
(55, '009', 3, 0, 'Classica', 'mnj,b', '../../../assets/icons/pizza_icon.png');

-- --------------------------------------------------------

--
-- Struttura della tabella `pizza_ingrediente`
--

CREATE TABLE `pizza_ingrediente` (
  `pizza` int(11) NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `pizza_ingrediente`
--

INSERT INTO `pizza_ingrediente` (`pizza`, `ingrediente`) VALUES
(22, 'Mozzarella'),
(22, 'mozzarella di bufala'),
(22, 'patate fritte'),
(22, 'pomodoro'),
(36, 'mascarpone'),
(36, 'mozzarella di bufala'),
(36, 'patate fritte'),
(41, 'mascarpone'),
(41, 'mozzarella di bufala'),
(41, 'patate fritte'),
(42, 'crudo'),
(43, 'crudo'),
(43, 'mascarpone'),
(43, 'Mozzarella'),
(43, 'patate fritte'),
(43, 'pomodoro'),
(45, 'carciofi'),
(45, 'crudo'),
(45, 'funghi'),
(45, 'Mozzarella'),
(45, 'patate fritte'),
(45, 'pomodoro'),
(46, 'fior di latte'),
(48, 'cesco'),
(48, 'fior di latte'),
(48, 'funghi'),
(49, 'fior di latte'),
(49, 'pomodoro'),
(50, 'cesco'),
(50, 'funghi'),
(50, 'pomodoro'),
(52, 'cesco'),
(52, 'fior di latte'),
(52, 'pomodoro'),
(53, 'carciofi'),
(54, 'fior di latte'),
(55, 'Mozzarella');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `pizzePerFascia`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `pizzePerFascia` (
`fascia` varchar(250)
,`pizze` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotti_ordine`
--

CREATE TABLE `prodotti_ordine` (
  `id` int(11) NOT NULL,
  `ordine` int(11) NOT NULL,
  `pizza` int(11) DEFAULT NULL,
  `cucina` int(11) DEFAULT NULL,
  `quantita` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `prodotti_ordine`
--

INSERT INTO `prodotti_ordine` (`id`, `ordine`, `pizza`, `cucina`, `quantita`) VALUES
(1, 1, 46, NULL, 10),
(2, 2, 46, NULL, 10),
(3, 3, 46, NULL, 20),
(4, 4, 46, NULL, 6),
(33, 33, 21, NULL, 2),
(34, 33, 36, NULL, 1),
(35, 33, 22, NULL, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `email` varchar(250) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `cognome` varchar(250) NOT NULL,
  `ruolo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`email`, `username`, `password`, `nome`, `cognome`, `ruolo`) VALUES
('admin@admin.com', 'admin', '$2y$10$w65KHkkqwPxnZ/3ntng6puxHt/YCh4uK0jn54iNyw3KclXQRiTqhO', 'Admin', 'Admin', 1),
('utente@utente.it', 'utente', '$2y$10$wxE2jEUpUH7FV25sdaImhOeuodxzE5VH7WEmEOD6L5eIffnK1dYgW', 'Utente', 'Utente', 0);

-- --------------------------------------------------------

--
-- Struttura per vista `checkOrari`
--
DROP TABLE IF EXISTS `checkOrari`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecweb`@`%` SQL SECURITY DEFINER VIEW `checkOrari`  AS SELECT `ordine`.`ora` AS `orario`, sum(`prodotti_ordine`.`quantita`) AS `pizze` FROM (`ordine` join `prodotti_ordine` on(`ordine`.`id` = `prodotti_ordine`.`ordine`)) WHERE `ordine`.`data` = curdate() AND `prodotti_ordine`.`pizza` > 0 GROUP BY `ordine`.`ora` HAVING `pizze` >= 10 ;

-- --------------------------------------------------------

--
-- Struttura per vista `pizzePerFascia`
--
DROP TABLE IF EXISTS `pizzePerFascia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tecweb`@`%` SQL SECURITY DEFINER VIEW `pizzePerFascia`  AS SELECT `disponiblitaorarie`.`fascia` AS `fascia`, sum(`prodotti_ordine`.`quantita`) AS `pizze` FROM ((`disponiblitaorarie` join `ordine` on(`disponiblitaorarie`.`fascia` = `ordine`.`ora`)) join `prodotti_ordine` on(`ordine`.`id` = `prodotti_ordine`.`ordine`)) WHERE `ordine`.`data` = curdate() GROUP BY `disponiblitaorarie`.`fascia` ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`cat`);

--
-- Indici per le tabelle `cucina`
--
ALTER TABLE `cucina`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `cucina_ingrediente`
--
ALTER TABLE `cucina_ingrediente`
  ADD PRIMARY KEY (`cucina`,`ingrediente`),
  ADD KEY `fk_cucina_ingrediente` (`ingrediente`);

--
-- Indici per le tabelle `disponiblitaorarie`
--
ALTER TABLE `disponiblitaorarie`
  ADD PRIMARY KEY (`fascia`);

--
-- Indici per le tabelle `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ordine_cucina` (`utente`),
  ADD KEY `fk_ordine_disponiblitaorarie` (`ora`);

--
-- Indici per le tabelle `pizza`
--
ALTER TABLE `pizza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pizza_categoria` (`categoria`);

--
-- Indici per le tabelle `pizza_ingrediente`
--
ALTER TABLE `pizza_ingrediente`
  ADD PRIMARY KEY (`pizza`,`ingrediente`),
  ADD KEY `fk_pizza_ingredenti` (`ingrediente`);

--
-- Indici per le tabelle `prodotti_ordine`
--
ALTER TABLE `prodotti_ordine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prodotti_ordine_cucina` (`cucina`),
  ADD KEY `fk_prodotti_ordine_ordine` (`ordine`),
  ADD KEY `fk_prodotti_ordine_pizza` (`pizza`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `cucina`
--
ALTER TABLE `cucina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT per la tabella `pizza`
--
ALTER TABLE `pizza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT per la tabella `prodotti_ordine`
--
ALTER TABLE `prodotti_ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `cucina_ingrediente`
--
ALTER TABLE `cucina_ingrediente`
  ADD CONSTRAINT `fk_cucina_ingrediente` FOREIGN KEY (`ingrediente`) REFERENCES `ingrediente` (`nome`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cucina_ingrediente_cucina` FOREIGN KEY (`cucina`) REFERENCES `cucina` (`id`);

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `fk_ordine_cucina` FOREIGN KEY (`utente`) REFERENCES `utente` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ordine_disponiblitaorarie` FOREIGN KEY (`ora`) REFERENCES `disponiblitaorarie` (`fascia`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `pizza`
--
ALTER TABLE `pizza`
  ADD CONSTRAINT `fk_pizza_categoria` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`cat`) ON DELETE CASCADE;

--
-- Limiti per la tabella `pizza_ingrediente`
--
ALTER TABLE `pizza_ingrediente`
  ADD CONSTRAINT `fk_pizza_ingredenti` FOREIGN KEY (`ingrediente`) REFERENCES `ingrediente` (`nome`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pizza_ingredenti_pizza` FOREIGN KEY (`pizza`) REFERENCES `pizza` (`id`);

--
-- Limiti per la tabella `prodotti_ordine`
--
ALTER TABLE `prodotti_ordine`
  ADD CONSTRAINT `fk_prodotti_ordine_cucina` FOREIGN KEY (`cucina`) REFERENCES `cucina` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prodotti_ordine_ordine` FOREIGN KEY (`ordine`) REFERENCES `ordine` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prodotti_ordine_pizza` FOREIGN KEY (`pizza`) REFERENCES `pizza` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
