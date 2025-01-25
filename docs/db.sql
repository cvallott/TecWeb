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
('Fuori menu', 'I nostri Fuori menu', 'Lasciati deliziare dalle nostre proposte, non fartele scappare!'),
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
  `nome` varchar(250) NOT NULL UNIQUE,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT 0,
  `path` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `cucina_ingrediente`
--

CREATE TABLE `cucina_ingrediente` (
  `cucina` int(11) NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


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

INSERT INTO `ingrediente` (`nome`, `peso`, `veget`) VALUES
('Pomodoro', 1, 1),
('Mozzarella', 2, 1),
('Asparagi', 3, 1),
('Pomodoro a fette', 3, 1),
('Rucola', 3, 1),
('Carciofi', 3, 1),
('Basilico', 3, 1),
('Olive', 3, 1),
('Melanzane', 3, 1),
('Mais', 3, 1),
('Capperi', 3, 1),
('Cipolla', 3, 1),
('Pomodorini', 3, 1),
('Spinaci', 3, 1),
('Zucchine', 3, 1),
('Peperoni', 3, 1),
('Piselli', 3, 1),
('Aglio', 3, 1),
('Zucchine fritte', 3, 1),
('Radicchio', 3, 1),
('Mozzarella di bufala', 3, 1),
('Brie', 3, 1),
('Grana', 3, 1),
('Ricotta', 3, 1),
('Gorgonzola', 3, 1),
('Asiago', 3, 1),
('Burrata', 3, 1),
('Philadelphia', 3, 1),
('Olio al tartufo', 3, 1),
('Pistacchio', 3, 1),
('Noci', 3, 1),
('Uova', 3, 1),
('Speck', 3, 0),
('Bresaola', 3, 0),
('Prosciutto', 3, 0),
('Crudo', 3, 0),
('Salamino', 3, 0),
('Porchetta', 3, 0),
('Salsiccia', 3, 0),
('Wurstel', 3, 0),
('Pancetta stufata', 3, 0),
('Sopressa', 3, 0),
('Funghi', 3, 1),
('Funghi misti', 3, 1),
('Funghi porcini', 3, 1),
('Funghi chiodini', 3, 1),
('Gamberetti', 3, 0),
('Acciughe', 3, 0),
('Tonno', 3, 0),
('Salmone', 3, 0),
('Patate fritte', 3, 1),
('Calamari', 3, 0),
('Verdure pastellate', 3, 1),
('Gamberetti fritti', 3, 0),
('Baccalà (300 grammi)', 3, 0),
('Trippa (400 grammi)', 3, 0),
('Cotoletta di pollo', 3, 0),
('Patate fritte con buccia', 3, 1),
('Mozzarelline', 3, 1),
('Anelli di cipolla', 3, 1),
('Pepite di pollo', 3, 0);

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

-- --------------------------------------------------------

--
-- Struttura della tabella `pizza`
--

CREATE TABLE `pizza` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL UNIQUE,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT 0,
  `categoria` varchar(250) NOT NULL,
  `descrizione` mediumtext DEFAULT NULL,
  `path` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `pizza_ingrediente`
--

CREATE TABLE `pizza_ingrediente` (
  `pizza` int(11) NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
