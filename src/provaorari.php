<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

$connessione = new DBConnection();

$connessioneOK = $connessione->openDBConnection();
$primaDisp = "";
$selectOrari = "";
if ($connessioneOK) {
    $primaDisp = $connessione->getFasceOrarie()[0];
    $selectOrari = $connessione->getFasceOrarie()[1];
    $connessione->closeConnection();
}

echo $primaDisp;
echo "<br>";
echo $selectOrari;
