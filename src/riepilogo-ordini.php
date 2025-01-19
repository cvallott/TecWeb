<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "riepilogo-ordini.php";

require 'script/PHP/checkUserLogin.php';
$template = file_get_contents('template/pageTemplate/riepilogo-ordiniTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$ordini = "";
if ($conn) {
    $ordini = $connessione->getOrdiniUtente($_SESSION['email']);
}

$messaggioOrdine = "";
if (isset($_SESSION['messaggio_ordine'])) {
    $messaggioOrdine = $_SESSION['messaggio_ordine'];
    unset($_SESSION['messaggio_ordine']);
    $bread = "<p>Sei in: <a lang='en' href='index.php'>Home</a> / <a lang='en' href='menu-prenota.php'>Menù-Prenota</a> / <a lang='en' href='carrello.php'>Carrello</a> / Riepilogo Ordini</p>";
}else{
    $bread = "<p>Sei in: <a lang='en' href='index.php'>Home</a> / Riepilogo Ordini</p>";
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[breadcrumb]', $bread, $template);
$template = str_replace('[ordiniUtente]', $ordini, $template);
$template = str_replace('[footer]', $footer, $template);
$template = str_replace('[messaggioOrdine]', $messaggioOrdine, $template);

$connessione->closeConnection();

echo $template;