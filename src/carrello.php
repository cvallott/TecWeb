<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
require 'script/PHP/gestioneCarrello.php';
require 'script/PHP/checkUserLogin.php';

$template = file_get_contents('template/pageTemplate/carrelloTemplate.html');

$header = printHeader();
$footer = printFooter();

$totale = getTotale();
$rowsCarrello = getCarrello();

$connessione = new DBConnection();

$connessioneOK = $connessione->openDBConnection();
$consiglioPizze = "";
$messaggio_errore = "";
if ($connessioneOK) {
    $consiglioPizze = $connessione->getFuoriMenuPerCarrello();
}

$totQuant = 0;
foreach ($_SESSION['carrello'] as $prodotto) {
    $totQuant += $prodotto['quantita'];
}

$primadisp = "";
$optionOrario = "";
if ($connessioneOK) {
    $primadisp = $connessione->getFasceOrarie($totQuant)[0];
    $optionOrario = $connessione->getFasceOrarie($totQuant)[1];
}

if(isset($_POST['ora'])){
    if($connessioneOK){
        $succ = "";
        if(isset($_POST['note'])){
            $succ = $connessione->insertOrder($_POST['ora'],$_POST['note']);
        }else{
            $succ = $connessione->insertOrder($_POST['ora']);
        }
        if($succ){
            if($connessioneOK){
                if($connessione->itemToOrdine($succ)) {

                    if ($totQuant == 0) {
                        $messaggio_errore= "<p role=\"alert\" class=\"messaggio\">Oops..qualcosa è andato storto. Devi inserire almeno un prodotto. Riprova!</p>";
                    } else {
                        unset($_SESSION['carrello']);
                        $oraScelta = $_POST['ora'];
                        $_SESSION['messaggio_ordine'] = "<p class='messaggio'>Ordine Confermato! Ti aspettiamo alle ore $oraScelta. Grazie per aver scelto Non Solo Pizza!</p>";
                        header("location: riepilogo-ordini.php");
                    }
                }

            }
        }
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[messaggio_errore]', $messaggio_errore, $template);
$template = str_replace('[nomeCognome]', $_SESSION['nome']. " " . $_SESSION['cognome'], $template);
$template = str_replace('[rowsCarrello]', $rowsCarrello, $template);
$template = str_replace('[tot]', $totale, $template);
$template = str_replace('[fasciaOraria]', $primadisp, $template);
$template = str_replace('[optionOrario]', $optionOrario, $template);
$template = str_replace('[pizzeMese]', $consiglioPizze, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;

