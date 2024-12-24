
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Host: localhost:8889
-- Creato il: Dic 24, 2024 alle 15:32
-- Versione del server: 8.0.35
-- Versione PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `categoria` (
  `cat` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `cucina` (
  `id` int NOT NULL,
  `nome` varchar(250) NOT NULL,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT (0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `cucina_ingrediente` (
  `piatto` int NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `ingrediente` (
  `nome` varchar(250) NOT NULL,
  `veget` tinyint(1) DEFAULT (0),
  `pagg` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `ordine` (
  `id` int NOT NULL,
  `utente` varchar(250) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `stato` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `pizza` (
  `id` int NOT NULL,
  `nome` varchar(250) NOT NULL,
  `prezzo` double NOT NULL,
  `veget` tinyint(1) NOT NULL DEFAULT (0),
  `categoria` varchar(250) NOT NULL,
  `descrizione` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `pizza_ingredente` (
  `pizza` int NOT NULL,
  `ingrediente` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `prodotti_ordine` (
  `id` int NOT NULL,
  `ordine` int NOT NULL,
  `pizza` int DEFAULT NULL,
  `cucina` int DEFAULT NULL,
  `quantita` int NOT NULL DEFAULT (1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `utente` (
  `email` varchar(250) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `cognome` varchar(250) NOT NULL,
  `ruolo` tinyint(1) NOT NULL DEFAULT (0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Indici e vincoli

ALTER TABLE `categoria`
  ADD PRIMARY KEY (`cat`);

ALTER TABLE `cucina`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cucina_ingrediente`
  ADD PRIMARY KEY (`piatto`,`ingrediente`),
  ADD KEY `fk_cucina_ingrediente` (`ingrediente`);

ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`nome`);

ALTER TABLE `ordine`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pizza`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pizza_ingredente`
  ADD PRIMARY KEY (`pizza`,`ingrediente`);

ALTER TABLE `prodotti_ordine`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `utente`
  ADD PRIMARY KEY (`email`);

-- AUTO_INCREMENT

ALTER TABLE `cucina`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `ordine`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `pizza`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `prodotti_ordine`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Foreign Key Constraints

ALTER TABLE `cucina_ingrediente`
  ADD CONSTRAINT `fk_cucina_ingrediente` FOREIGN KEY (`ingrediente`) REFERENCES `ingrediente` (`nome`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cucina_ingrediente_cucina` FOREIGN KEY (`piatto`) REFERENCES `cucina` (`id`) ON DELETE CASCADE;

ALTER TABLE `ordine`
  ADD CONSTRAINT `fk_ordine_cucina` FOREIGN KEY (`utente`) REFERENCES `utente` (`email`) ON DELETE CASCADE;

ALTER TABLE `pizza`
  ADD CONSTRAINT `fk_pizza_categoria` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`cat`) ON DELETE CASCADE;

ALTER TABLE `pizza_ingredente`
  ADD CONSTRAINT `fk_pizza_ingredenti` FOREIGN KEY (`ingrediente`) REFERENCES `ingrediente` (`nome`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pizza_ingredenti_pizza` FOREIGN KEY (`pizza`) REFERENCES `pizza` (`id`) ON DELETE CASCADE;

ALTER TABLE `prodotti_ordine`
  ADD CONSTRAINT `fk_prodotti_ordine_cucina` FOREIGN KEY (`cucina`) REFERENCES `cucina` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prodotti_ordine_ordine` FOREIGN KEY (`ordine`) REFERENCES `ordine` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prodotti_ordine_pizza` FOREIGN KEY (`pizza`) REFERENCES `pizza` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
