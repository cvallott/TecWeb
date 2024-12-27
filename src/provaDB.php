<?php

include_once 'script/PHP/dbConnection.php';
use DB\DBConnection;

$connessione = new DBConnection();
$connessioneOK = $connessione->openDBConnection();

if($connessioneOK){
    echo "OK";
}else{
    echo "NO";
}