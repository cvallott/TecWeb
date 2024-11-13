# Usa l'immagine PHP di base con Apache
FROM php:8.1-apache

# Installa le estensioni MySQLi e PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Abilita moduli Apache necessari (opzionale, ma consigliato)
RUN a2enmod rewrite

