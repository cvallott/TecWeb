<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/riepilogo-ordiniTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$ordini = "";
if ($conn) {
    $ordini = $connessione->getOrdiniUtente($_SESSION['email']);
}
$connessione->closeConnection();

$template = str_replace('[header]', $header, $template);
$template = str_replace('[ordiniUtente]', $ordini, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;