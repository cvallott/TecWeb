<?php

include_once 'template/components/loadComponents.php';
require 'script/PHP/gestioneCarrello.php';
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


$template = str_replace('[header]', $header, $template);
$template = str_replace('[rowsCarrello]', $rowsCarrello, $template);
$template = str_replace('[tot]', $totale, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;

