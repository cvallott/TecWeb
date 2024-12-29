-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Creato il: Dic 27, 2024 alle 10:34
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
  `cat` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `categoria`
--

INSERT INTO `categoria` (`cat`) VALUES
('classica'),
('speciale');

-- --------------------------------------------------------

--
-- Struttura della tabella `cucina`
--

CREATE TABLE `cucina` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `cucina_ingrediente`
--

CREATE TABLE `cucina_ingrediente` (
  `piatto` int(11) NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente`
--

CREATE TABLE `ingrediente` (
  `nome` varchar(250) NOT NULL,
  `veget` tinyint(1) DEFAULT 0,
  `pagg` double NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `ingrediente`
--

INSERT INTO `ingrediente` (`nome`, `veget`, `pagg`) VALUES
('crudo', 0, 2),
('fior di latte', 0, 1.5),
('mozzarella di bufala', 0, 1),
('patate fritte', 0, 2),
('pomodoro', 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `id` int(11) NOT NULL,
  `utente` varchar(250) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `stato` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(1, 'Bufalina', 7, 0, 'classica', NULL, ''),
(2, 'Crudo e fior di latte', 11, 0, 'classica', NULL, ''),
(3, 'Zucca e gorgonzolaaaaa', 10.5, 1, 'speciale', 'Gusto unico e deciso, non fartela scappare!', 'pizze/FM-zuccagorgo.jpeg'),
(4, 'Pesto e mozzarella di bufala', 11, 1, 'speciale', 'Sapore fresco e delicato nato dall\'unione della tradizione italiana', 'pizze/FM-pestobufala.jpeg'),
(5, 'Calzone alla nutella', 6.9, 0, 'speciale', 'C\'è sempre spazio per il dolce, no?', 'pizze/FM-calzonenutella.jpeg');

-- --------------------------------------------------------

--
-- Struttura della tabella `pizza_ingredente`
--

CREATE TABLE `pizza_ingredente` (
  `pizza` int(11) NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `pizza_ingredente`
--

INSERT INTO `pizza_ingredente` (`pizza`, `ingrediente`) VALUES
(1, 'mozzarella di bufala'),
(1, 'pomodoro'),
(2, 'crudo'),
(2, 'fior di latte');

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
('admin@prova.com', 'admin', 'admin', 'Admin', 'Adminone', 1),
('prova@prova.com', 'prova', 'prova', 'Prova', 'Provona', 0);

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
  ADD PRIMARY KEY (`piatto`,`ingrediente`),
  ADD KEY `fk_cucina_ingrediente` (`ingrediente`);

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
  ADD KEY `fk_ordine_cucina` (`utente`);

--
-- Indici per le tabelle `pizza`
--
ALTER TABLE `pizza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pizza_categoria` (`categoria`);

--
-- Indici per le tabelle `pizza_ingredente`
--
ALTER TABLE `pizza_ingredente`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pizza`
--
ALTER TABLE `pizza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `prodotti_ordine`
--
ALTER TABLE `prodotti_ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `cucina_ingrediente`
--
ALTER TABLE `cucina_ingrediente`
  ADD CONSTRAINT `fk_cucina_ingrediente` FOREIGN KEY (`ingrediente`) REFERENCES `ingrediente` (`nome`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cucina_ingrediente_cucina` FOREIGN KEY (`piatto`) REFERENCES `cucina` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `fk_ordine_cucina` FOREIGN KEY (`utente`) REFERENCES `utente` (`email`) ON DELETE CASCADE;

--
-- Limiti per la tabella `pizza`
--
ALTER TABLE `pizza`
  ADD CONSTRAINT `fk_pizza_categoria` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`cat`) ON DELETE CASCADE;

--
-- Limiti per la tabella `pizza_ingredente`
--
ALTER TABLE `pizza_ingredente`
  ADD CONSTRAINT `fk_pizza_ingredenti` FOREIGN KEY (`ingrediente`) REFERENCES `ingrediente` (`nome`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pizza_ingredenti_pizza` FOREIGN KEY (`pizza`) REFERENCES `pizza` (`id`) ON DELETE CASCADE;

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
