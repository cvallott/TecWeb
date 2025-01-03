<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/aggiungi-pizzaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaIngredienti = "";
if($conn){
    $listaIngredienti = $connessione->getIngredienti();
    $connessione->closeConnection();
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;