<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/indexTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$pizzeSpeciali = "";
if($conn){
    echo "connesso";
    $pizzeSpeciali = $connessione->getPizzeSpeciali();
    $connessione->closeConnection();
}


$template = str_replace('[header]', $header, $template);
$template = str_replace('[pizzeMese]', $pizzeSpeciali, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;