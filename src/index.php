<?php
$host = 'db';
$dbname = 'tecweb';
$username = 'tecweb';
$password = 'tecweb';

$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Errore di connessione: " . $connection->connect_error);
} else {
    echo "Connessoooooo!";
}
