<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
require 'script/PHP/gestioneCarrello.php';
require 'script/PHP/checkUserLogin.php';

$template = file_get_contents('template/pageTemplate/carrelloTemplate.html');

$header = printHeader();
$footer = printFooter();

/*
$_SESSION['carrello'][1] = ['nome' => "provauno", 'quantita' => 20, 'prezzo' => 20];
$_SESSION['carrello'][2] = ['nome' => "provadue", 'quantita' => 60, 'prezzo' => 20];
$_SESSION['carrello'][3] = ['nome' => "provatre", 'quantita' => 1, 'prezzo' => 20];
$_SESSION['carrello'][4] = ['nome' => "provaquattro", 'quantita' => 50, 'prezzo' => 20]; */


$totale = getTotale();
$rowsCarrello = getCarrello();

$connessione = new DBConnection();

$connessioneOK = $connessione->openDBConnection();
$consiglioPizze = "";
if ($connessioneOK) {
    $consiglioPizze = $connessione->getFuoriMenuPerCarrello();
    $connessione->closeConnection();
}

$totQuant = 0;
foreach ($_SESSION['carrello'] as $prodotto) {
    $totQuant += $prodotto['quantita'];
}

$connessioneOK = $connessione->openDBConnection();
$primadisp = "";
$optionOrario = "";
if ($connessioneOK) {
    $primadisp = $connessione->getFasceOrarie($totQuant)[0];
    $optionOrario = $connessione->getFasceOrarie($totQuant)[1];
    $connessione->closeConnection();
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[nomeCognome]', $_SESSION['nome']. " " . $_SESSION['cognome'], $template);
$template = str_replace('[rowsCarrello]', $rowsCarrello, $template);
$template = str_replace('[tot]', $totale, $template);
$template = str_replace('[fasciaOraria]', $primadisp, $template);
$template = str_replace('[optionOrario]', $optionOrario, $template);
$template = str_replace('[pizzeMese]', $consiglioPizze, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;

