<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/menu-prenotaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$pizzeClassiche = "";
if($conn){
    $pizzeClassiche = $connessione->getPizzeClassiche();
    $connessione->closeConnection();
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[pizzeClassiche]', $pizzeClassiche, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;