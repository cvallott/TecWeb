<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/dettagli-ordineTemplate.html');

$header = printHeader();
$footer = printFooter();

/*
 * ATTENZIONE: per evitare di vedere l'id nell'url o trasformo i link di dettagli in dei form e passo
 * l'id in modo hidden
 * oppure creo una pagina intermedia che mi prende il valore di get e lo mette dentro una var di sessione
 * a quel punto ho la variabile di sessione per l'ordine di cui voglio i dettagli e genero la pagina di dettagli in modo dinamico
 */

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$dettaglioOrdine = "";
$totOrdine = "";
$id = $_GET['idOrdine'] ?? "";
echo $id;
if($conn){
    if(!empty($id)){
        $dettaglioOrdine = $connessione->getDettagliOrdine($id);
        $totOrdine = $connessione->getTotalePrezzoOrdine($id);
    }
    $connessione->closeConnection();
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[dettaglioOrdine]', $dettaglioOrdine, $template);
$template = str_replace('[totOrdine]', $totOrdine, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;