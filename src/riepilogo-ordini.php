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

$messaggioOrdine = "";
if (isset($_SESSION['messaggio_ordine'])) {
    $messaggioOrdine = $_SESSION['messaggio_ordine'];
    unset($_SESSION['messaggio_ordine']);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[ordiniUtente]', $ordini, $template);
$template = str_replace('[footer]', $footer, $template);
$template = str_replace('[messaggioOrdine]', $messaggioOrdine, $template);

echo $template;