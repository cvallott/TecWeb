<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/dettagli-ordineTemplate.html');

$header = printHeader();
$footer = printFooter();

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