-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Creato il: Gen 25, 2025 alle 15:05
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
  `nome` varchar(250) NOT NULL,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT 0,
  `path` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `cucina`
--

INSERT INTO `cucina` (`id`, `nome`, `prezzo`, `veget`, `path`) VALUES
(9, 'Frittura di calamari', 13, 0, 'assets/pizze/calamari.jpeg'),
(10, 'Frittura di calamari con verdure pastellate', 13, 0, 'assets/icons/piatto_icon.png'),
(11, 'Frittura mista di pesce', 13, 0, 'assets/pizze/fritturagamberi.jpeg'),
(12, 'Baccala alla vicentina', 13, 0, 'assets/icons/piatto_icon.png'),
(13, 'Trippa', 11, 0, 'assets/pizze/trippa.jpeg'),
(14, 'Cotoletta di pollo con patate', 9, 0, 'assets/icons/piatto_icon.png'),
(15, 'Patate fritte', 4, 1, 'assets/icons/piatto_icon.png'),
(16, 'Patate fritte con buccia', 4, 1, 'assets/icons/piatto_icon.png'),
(17, 'Verdure pastellate', 4, 1, 'assets/icons/piatto_icon.png'),
(18, 'Mozzarelline fritte', 4.5, 1, 'assets/pizze/mozzarelline.jpeg'),
(19, 'Anelli di cipolla fritti', 4, 1, 'assets/pizze/anellicipolla.jpeg'),
(20, 'Pepite di pollo fritte', 4, 0, 'assets/pizze/pepite.jpeg');

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
(9, 'Calamari'),
(10, 'Calamari'),
(10, 'Verdure pastellate'),
(11, 'Calamari'),
(11, 'Gamberetti fritti'),
(12, 'Baccalà (300 grammi)'),
(13, 'Trippa (400 grammi)'),
(14, 'Cotoletta di pollo'),
(14, 'Patate fritte'),
(15, 'Patate fritte'),
(16, 'Patate fritte con buccia'),
(17, 'Verdure pastellate'),
(18, 'Mozzarelline'),
(19, 'Anelli di cipolla'),
(20, 'Pepite di pollo');

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

INSERT INTO `ingrediente` (`nome`, `peso`, `veget`, `pagg`) VALUES
('Acciughe', 3, 0, 1),
('Aglio', 3, 1, 1),
('Anelli di cipolla', 3, 1, 1),
('Asiago', 3, 1, 1),
('Asparagi', 3, 1, 1),
('Baccalà (300 grammi)', 3, 0, 1),
('Basilico', 3, 1, 1),
('Bresaola', 3, 0, 1),
('Brie', 3, 1, 1),
('Burrata', 3, 1, 1),
('Calamari', 3, 0, 1),
('Capperi', 3, 1, 1),
('Carciofi', 3, 1, 1),
('Cipolla', 3, 1, 1),
('Cotoletta di pollo', 3, 0, 1),
('Crudo', 3, 0, 1),
('Funghi', 3, 1, 1),
('Funghi chiodini', 3, 1, 1),
('Funghi misti', 3, 1, 1),
('Funghi porcini', 3, 1, 1),
('Gamberetti', 3, 0, 1),
('Gamberetti fritti', 3, 0, 1),
('Gorgonzola', 3, 1, 1),
('Grana', 3, 1, 1),
('Mais', 3, 1, 1),
('Melanzane', 3, 1, 1),
('Mozzarella', 2, 1, 1),
('Mozzarella di bufala', 3, 1, 1),
('Mozzarelline', 3, 1, 1),
('Noci', 3, 1, 1),
('Nutella', 3, 0, 1),
('Olio al tartufo', 3, 1, 1),
('Olive', 3, 1, 1),
('Pancetta stufata', 3, 0, 1),
('Patate fritte', 3, 1, 1),
('Patate fritte con buccia', 3, 1, 1),
('Peperoni', 3, 1, 1),
('Pepite di pollo', 3, 0, 1),
('Pesto', 3, 0, 1),
('Philadelphia', 3, 1, 1),
('Piselli', 3, 1, 1),
('Pistacchio', 3, 1, 1),
('Pomodorini', 3, 1, 1),
('Pomodoro', 1, 1, 1),
('Pomodoro a fette', 3, 1, 1),
('Porchetta', 3, 0, 1),
('Prosciutto', 3, 0, 1),
('Radicchio', 3, 1, 1),
('Ricotta', 3, 1, 1),
('Rucola', 3, 1, 1),
('Salamino', 3, 0, 1),
('Salmone', 3, 0, 1),
('Salsiccia', 3, 0, 1),
('Sopressa', 3, 0, 1),
('Speck', 3, 0, 1),
('Spinaci', 3, 1, 1),
('Tonno', 3, 0, 1),
('Trippa (400 grammi)', 3, 0, 1),
('Uova', 3, 1, 1),
('Verdure pastellate', 3, 1, 1),
('Wurstel', 3, 0, 1),
('Zucca', 3, 0, 1),
('Zucchine', 3, 1, 1),
('Zucchine fritte', 3, 1, 1);

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
(56, 'Asparagi', 7, 1, 'Classica', '', 'assets/pizze/asparagi.jpeg'),
(57, 'Bufalina', 7.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(59, 'Bresaola', 7.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(60, 'Briosa', 8, 1, 'Classica', '', 'assets/pizze/briosa.jpeg'),
(61, 'Bresaola rucola e grana', 9, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(62, 'Carciofi', 6.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(63, 'Caprese', 6.5, 1, 'Classica', '', 'assets/pizze/caprese.jpeg'),
(64, 'Capricciosa', 7.5, 0, 'Classica', '', 'assets/pizze/capricciosa.jpeg'),
(65, 'Crudo', 7.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(66, 'Diavola', 6.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(67, 'Funghi', 6.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(68, 'Gamberetti', 8.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(69, 'Greca', 6.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(70, 'Margherita', 5.5, 1, 'Classica', '', 'assets/pizze/margherita.jpeg'),
(71, 'Melanzane', 7, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(72, 'Misto bosco', 8.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(73, 'Mais', 6.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(74, 'Napoletana', 7, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(75, 'Pugliese', 6, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(76, 'Prosciutto', 6.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(77, 'Prosciutto e funghi', 7, 0, 'Classica', '', 'assets/pizze/proscfunghi.jpeg'),
(78, 'Porcini', 8, 1, 'Classica', '', 'assets/pizze/porcini.jpeg'),
(79, 'Patatosa', 7, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(80, 'Porchetta', 7, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(81, 'Pomodorini', 6.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(82, 'Ricotta e spinaci', 7, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(83, 'Romana', 6.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(84, 'Salsiccia', 7, 0, 'Classica', '', 'assets/pizze/salsiccia.jpeg'),
(85, 'Speck', 7.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(86, 'Tonno e cipolla', 8, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(87, 'Tonno', 7.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(88, 'Viennese', 6.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(89, 'Verdure', 8, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(90, 'Vegetariana', 8.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(91, 'Quattro formaggi', 7.5, 1, 'Classica', '', 'assets/icons/pizza_icon.png'),
(92, 'Quattro stagioni', 7.5, 0, 'Classica', '', 'assets/icons/pizza_icon.png'),
(93, 'Asparagi e uova', 8, 1, 'Speciale', '', 'assets/pizze/asparagiuova.jpeg'),
(94, 'Amalfi', 8, 1, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(95, 'Bella napoli', 8, 1, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(97, 'Burrata e crudo', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(98, 'Burrata acciughe e pomodorini', 9, 0, 'Speciale', '', 'assets/pizze/burrataaccpomod.jpeg'),
(99, 'Burrata salmone e zucchine', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(100, 'Campania', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(101, 'Carbonara', 8.5, 0, 'Speciale', '', 'assets/pizze/carbonara.jpeg'),
(102, 'Contadina', 7.5, 1, 'Speciale', '', 'assets/pizze/asparagimaispiselli.jpeg'),
(103, 'Dinamite', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(104, 'Estate', 8, 1, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(105, 'Genny', 8, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(106, 'Gorgonzola e noci', 8, 1, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(107, 'Gorgonzola e speck', 8, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(108, 'Marinara', 4.5, 1, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(109, 'Mediterranea', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(110, 'Mare e monti', 9, 0, 'Speciale', '', 'assets/pizze/maremonti.jpeg'),
(111, 'Parmigiana', 8, 1, 'Speciale', '', 'assets/pizze/parmigiana.jpeg'),
(112, 'Philadelphia salmone e pomodorini', 9, 0, 'Speciale', '', 'assets/pizze/salmonephilapomo.jpeg'),
(113, 'Philadelphia speck e pistacchio', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(114, 'Porchetta burrata e tartufo', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(115, 'Porchetta e tonno', 8.5, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(116, 'Rustica', 8, 0, 'Speciale', '', 'assets/pizze/zingara-wurstelpepercipo.jpeg'),
(117, 'Salmone', 8.5, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(118, 'Salmone pomodorini e rucola', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(119, 'Siciliana', 7.5, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(120, 'Simpatia', 8.5, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(121, 'Sopressa e chiodini', 8.5, 0, 'Speciale', '', 'assets/pizze/sopressachiodini.jpeg'),
(122, 'Speck e zucchine fritte', 8, 0, 'Speciale', '', 'assets/pizze/spackzucchfritte.jpeg'),
(123, 'Su e giu', 9, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(124, 'Tirolese', 8.5, 0, 'Speciale', '', 'assets/pizze/tirolese.jpeg'),
(125, 'Wurstel e patate', 7.5, 0, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(126, 'Zingara', 7.5, 1, 'Speciale', '', 'assets/icons/pizza_icon.png'),
(127, 'Padana', 9, 0, 'Stagionale', '', 'assets/icons/pizza_icon.png'),
(128, 'Radicchio', 8, 1, 'Stagionale', '', 'assets/pizze/radicchio.jpeg'),
(129, 'Radicchio e salsiccia', 9, 0, 'Stagionale', '', 'assets/icons/pizza_icon.png'),
(130, 'Treviso', 8.5, 1, 'Stagionale', '', 'assets/pizze/radicchiograna.jpeg'),
(131, 'Zucca e gorgonzola', 8.5, 1, 'Fuori menu', 'Il sapore forte del gorgonzola unito alla dolcezza della zucca', 'assets/pizze/FM-zuccagorgo.jpeg'),
(132, 'Pesto e bufala', 8.5, 1, 'Fuori menu', 'Scopri la delicatezza della nostra Pesto e bufala!', 'assets/pizze/FM-pestobufala.jpeg'),
(133, 'Calzone alla nutella', 4.5, 1, 'Fuori menu', 'C&#039;&egrave; sempre spazio per il dolce, no?', 'assets/pizze/FM-calzonenutella.jpeg'),
(134, 'Brie e speck', 8, 0, 'Classica', '', 'assets/pizze/briespeck.jpeg');

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
(56, 'Asparagi'),
(56, 'Mozzarella'),
(56, 'Pomodoro'),
(57, 'Mozzarella'),
(57, 'Mozzarella di bufala'),
(57, 'Pomodoro'),
(59, 'Bresaola'),
(59, 'Mozzarella'),
(59, 'Pomodoro'),
(60, 'Brie'),
(60, 'Mozzarella'),
(60, 'Pomodoro'),
(60, 'Pomodoro a fette'),
(61, 'Bresaola'),
(61, 'Grana'),
(61, 'Mozzarella'),
(61, 'Pomodoro'),
(61, 'Rucola'),
(62, 'Carciofi'),
(62, 'Mozzarella'),
(62, 'Pomodoro'),
(63, 'Basilico'),
(63, 'Mozzarella'),
(63, 'Pomodoro'),
(63, 'Pomodoro a fette'),
(64, 'Carciofi'),
(64, 'Funghi'),
(64, 'Mozzarella'),
(64, 'Pomodoro'),
(64, 'Prosciutto'),
(65, 'Crudo'),
(65, 'Mozzarella'),
(65, 'Pomodoro'),
(66, 'Mozzarella'),
(66, 'Pomodoro'),
(66, 'Salamino'),
(67, 'Funghi'),
(67, 'Mozzarella'),
(67, 'Pomodoro'),
(68, 'Gamberetti'),
(68, 'Mozzarella'),
(68, 'Pomodoro'),
(69, 'Mozzarella'),
(69, 'Olive'),
(69, 'Pomodoro'),
(70, 'Mozzarella'),
(70, 'Pomodoro'),
(71, 'Melanzane'),
(71, 'Mozzarella'),
(71, 'Pomodoro'),
(72, 'Funghi misti'),
(72, 'Mozzarella'),
(72, 'Pomodoro'),
(73, 'Mais'),
(73, 'Mozzarella'),
(73, 'Pomodoro'),
(74, 'Acciughe'),
(74, 'Capperi'),
(74, 'Mozzarella'),
(74, 'Pomodoro'),
(75, 'Cipolla'),
(75, 'Mozzarella'),
(75, 'Pomodoro'),
(76, 'Mozzarella'),
(76, 'Pomodoro'),
(76, 'Prosciutto'),
(77, 'Funghi'),
(77, 'Mozzarella'),
(77, 'Pomodoro'),
(77, 'Prosciutto'),
(78, 'Funghi porcini'),
(78, 'Mozzarella'),
(78, 'Pomodoro'),
(79, 'Mozzarella'),
(79, 'Patate fritte'),
(79, 'Pomodoro'),
(80, 'Mozzarella'),
(80, 'Pomodoro'),
(80, 'Porchetta'),
(81, 'Mozzarella'),
(81, 'Pomodorini'),
(81, 'Pomodoro'),
(82, 'Mozzarella'),
(82, 'Pomodoro'),
(82, 'Ricotta'),
(82, 'Spinaci'),
(83, 'Acciughe'),
(83, 'Mozzarella'),
(83, 'Pomodoro'),
(84, 'Mozzarella'),
(84, 'Pomodoro'),
(84, 'Salsiccia'),
(85, 'Mozzarella'),
(85, 'Pomodoro'),
(85, 'Speck'),
(86, 'Cipolla'),
(86, 'Mozzarella'),
(86, 'Pomodoro'),
(86, 'Tonno'),
(87, 'Mozzarella'),
(87, 'Pomodoro'),
(87, 'Tonno'),
(88, 'Mozzarella'),
(88, 'Pomodoro'),
(88, 'Wurstel'),
(89, 'Melanzane'),
(89, 'Mozzarella'),
(89, 'Peperoni'),
(89, 'Pomodoro'),
(89, 'Zucchine'),
(90, 'Mais'),
(90, 'Mozzarella'),
(90, 'Peperoni'),
(90, 'Piselli'),
(90, 'Pomodoro'),
(90, 'Pomodoro a fette'),
(91, 'Asiago'),
(91, 'Brie'),
(91, 'Gorgonzola'),
(91, 'Grana'),
(91, 'Mozzarella'),
(91, 'Pomodoro'),
(92, 'Carciofi'),
(92, 'Funghi'),
(92, 'Mozzarella'),
(92, 'Pomodoro'),
(92, 'Prosciutto'),
(92, 'Salamino'),
(93, 'Asparagi'),
(93, 'Mozzarella'),
(93, 'Pomodoro'),
(93, 'Uova'),
(94, 'Basilico'),
(94, 'Mozzarella'),
(94, 'Pomodoro'),
(94, 'Pomodoro a fette'),
(94, 'Rucola'),
(95, 'Mozzarella'),
(95, 'Mozzarella di bufala'),
(95, 'Pomodoro'),
(95, 'Pomodoro a fette'),
(95, 'Rucola'),
(97, 'Burrata'),
(97, 'Crudo'),
(97, 'Mozzarella'),
(97, 'Pomodoro'),
(98, 'Acciughe'),
(98, 'Burrata'),
(98, 'Mozzarella'),
(98, 'Pomodorini'),
(98, 'Pomodoro'),
(99, 'Burrata'),
(99, 'Mozzarella'),
(99, 'Pomodoro'),
(99, 'Salmone'),
(99, 'Zucchine'),
(100, 'Mozzarella'),
(100, 'Mozzarella di bufala'),
(100, 'Olive'),
(100, 'Pomodoro'),
(100, 'Salamino'),
(101, 'Mozzarella'),
(101, 'Pancetta stufata'),
(101, 'Pomodoro'),
(101, 'Uova'),
(102, 'Asparagi'),
(102, 'Mais'),
(102, 'Mozzarella'),
(102, 'Piselli'),
(102, 'Pomodoro'),
(103, 'Acciughe'),
(103, 'Capperi'),
(103, 'Cipolla'),
(103, 'Mozzarella'),
(103, 'Olive'),
(103, 'Pomodoro'),
(103, 'Salamino'),
(104, 'Grana'),
(104, 'Mozzarella'),
(104, 'Pomodoro'),
(104, 'Pomodoro a fette'),
(104, 'Rucola'),
(105, 'Crudo'),
(105, 'Mozzarella'),
(105, 'Patate fritte'),
(105, 'Pomodoro'),
(106, 'Gorgonzola'),
(106, 'Mozzarella'),
(106, 'Noci'),
(106, 'Pomodoro'),
(107, 'Gorgonzola'),
(107, 'Mozzarella'),
(107, 'Pomodoro'),
(107, 'Speck'),
(108, 'Aglio'),
(108, 'Pomodoro'),
(109, 'Crudo'),
(109, 'Grana'),
(109, 'Mozzarella'),
(109, 'Pomodorini'),
(109, 'Pomodoro'),
(109, 'Rucola'),
(110, 'Funghi'),
(110, 'Gamberetti'),
(110, 'Mozzarella'),
(110, 'Pomodoro'),
(111, 'Grana'),
(111, 'Melanzane'),
(111, 'Mozzarella'),
(111, 'Pomodoro'),
(112, 'Mozzarella'),
(112, 'Philadelphia'),
(112, 'Pomodorini'),
(112, 'Pomodoro'),
(112, 'Salmone'),
(113, 'Mozzarella'),
(113, 'Philadelphia'),
(113, 'Pistacchio'),
(113, 'Pomodoro'),
(113, 'Speck'),
(114, 'Burrata'),
(114, 'Mozzarella'),
(114, 'Olio al tartufo'),
(114, 'Pomodoro'),
(114, 'Porchetta'),
(115, 'Mozzarella'),
(115, 'Pomodoro'),
(115, 'Porchetta'),
(115, 'Tonno'),
(116, 'Cipolla'),
(116, 'Mozzarella'),
(116, 'Peperoni'),
(116, 'Pomodoro'),
(116, 'Wurstel'),
(117, 'Mozzarella'),
(117, 'Pomodoro'),
(117, 'Salmone'),
(118, 'Mozzarella'),
(118, 'Pomodorini'),
(118, 'Pomodoro'),
(118, 'Rucola'),
(118, 'Salmone'),
(119, 'Acciughe'),
(119, 'Capperi'),
(119, 'Mozzarella'),
(119, 'Olive'),
(119, 'Pomodoro'),
(120, 'Cipolla'),
(120, 'Mais'),
(120, 'Mozzarella'),
(120, 'Piselli'),
(120, 'Pomodoro'),
(120, 'Tonno'),
(121, 'Funghi chiodini'),
(121, 'Mozzarella'),
(121, 'Pomodoro'),
(121, 'Sopressa'),
(122, 'Mozzarella'),
(122, 'Pomodoro'),
(122, 'Speck'),
(122, 'Zucchine fritte'),
(123, 'Brie'),
(123, 'Grana'),
(123, 'Mozzarella'),
(123, 'Pomodorini'),
(123, 'Pomodoro'),
(123, 'Rucola'),
(123, 'Salamino'),
(124, 'Funghi'),
(124, 'Mozzarella'),
(124, 'Pomodoro'),
(124, 'Speck'),
(125, 'Mozzarella'),
(125, 'Patate fritte'),
(125, 'Pomodoro'),
(125, 'Wurstel'),
(126, 'Funghi'),
(126, 'Mozzarella'),
(126, 'Peperoni'),
(126, 'Pomodoro'),
(127, 'Grana'),
(127, 'Mozzarella'),
(127, 'Pomodoro'),
(127, 'Porchetta'),
(127, 'Radicchio'),
(128, 'Mozzarella'),
(128, 'Pomodoro'),
(128, 'Radicchio'),
(129, 'Mozzarella'),
(129, 'Peperoni'),
(129, 'Radicchio'),
(129, 'Salsiccia'),
(130, 'Grana'),
(130, 'Mozzarella'),
(130, 'Pomodoro'),
(130, 'Radicchio'),
(131, 'Gorgonzola'),
(131, 'Mozzarella'),
(131, 'Pomodoro'),
(131, 'Zucca'),
(132, 'Mozzarella di bufala'),
(132, 'Pesto'),
(133, 'Nutella'),
(133, 'Pistacchio'),
(134, 'Brie'),
(134, 'Mozzarella'),
(134, 'Pomodoro'),
(134, 'Speck');

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT per la tabella `pizza`
--
ALTER TABLE `pizza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

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
