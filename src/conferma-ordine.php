<?php

include_once 'template/components/loadComponents.php';
use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

$template = file_get_contents('template/pageTemplate/conferma-ordine.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();

$connessioneOK = $connessione->openDBConnection();
$totQuant = 0;
foreach ($_SESSION['carrello'] as $prodotto) {
    $totQuant += $prodotto['quantita'];
}
$selectOrari = "";
if ($connessioneOK) {
    $selectOrari = $connessione->getFasceOrarie($totQuant)[1];
    $connessione->closeConnection();
}

$nameClient = $_SESSION['nome'];


$template = str_replace('[header]', $header, $template);
$template = str_replace('[oraridisp]', $selectOrari, $template);
$template = str_replace('[footer]', $footer, $template);
$template = str_replace('[nameClient]', $_SESSION['nome'], $template);
//$template = str_replace('[surnameClient]', $_SESSION['cognome'], $template);

echo $template;