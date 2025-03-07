<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/indexTemplate.html');

$header = printHeader();
$footer = printFooter();

$_SESSION['redirect']= "index.php";

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$pizzeFM = "";
if($conn){
    $pizzeFM = $connessione->getFuoriMenu();
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[pizzeMese]', $pizzeFM, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;