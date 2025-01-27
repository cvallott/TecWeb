<?php

date_default_timezone_set('Europe/Rome');

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "carrello.php";

require 'script/PHP/checkUserLogin.php';
require 'script/PHP/gestioneCarrello.php';

$template = file_get_contents('template/pageTemplate/carrelloTemplate.html');

$header = printHeader();
$footer = printFooter();

$totale = getTotale();
$rowsCarrello = getCarrello();

$connessione = new DBConnection();

$connessioneOK = $connessione->openDBConnection();
$consiglioPizze = "";
$messaggio_errore = "";
$optionOrario = "";

if(isset($_SESSION['aggiunta_carrello'])){
    unset($_SESSION['aggiunta_carrello']);
}

if ($connessioneOK) {
    $consiglioPizze = $connessione->getFuoriMenuPerCarrello();
}

$totQuant = 0;
foreach ($_SESSION['carrello'] as $prodotto) {
    $totQuant += $prodotto['quantita'];
}
if(date("l") != 'Monday'){
    $primadisp = "<p id=\"primadisp\">La prima disponibilità è nella fascia oraria ";
    $optionOrario = "";
    if ($connessioneOK) {
        $primadisp .= $connessione->getFasceOrarie($totQuant)[0]."</p>";
        $option = $connessione->getFasceOrarie($totQuant)[1];
        if(!empty($option)){
            $optionOrario = "<label for=\"ora\">Seleziona ora di ritiro:</label>";
            $optionOrario .= "<select name=\"ora\" id=\"ora\" class=\"select\" required>";
            $optionOrario .= $option;
            $optionOrario .= "</select>";
        }else{
            $primadisp = "<div role=\"alert\"><p id=\"primadisp\">Ci spiace, al momento non abbiamo nessuna disponibilità. Alla prossima!</p></div>";
        }
    }
    if($totQuant == 0){
        $messaggio_errore= "<div role=\"alert\" class=\"messaggio\">Inserisci almeno un prodotto</div>";
    } else if(isset($_POST['ora'])){
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
                        unset($_SESSION['carrello']);
                        $oraScelta = $_POST['ora'];
                        $_SESSION['messaggio_ordine'] = "<div class=\"messaggio\">Ordine Confermato! Ti aspettiamo alle ore $oraScelta. Grazie per aver scelto Non Solo Pizza!</div>";
                        header("location: riepilogo-ordini.php");
                    }
                }
            }
        }
    }
} else {
    $primadisp = "<div role=\"alert\"><p id=\"primadisp\">Ci spiace, il lunedì siamo chiusi. Alla prossima!</p></div>";
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

