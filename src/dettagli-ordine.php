<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';
require 'script/PHP/checkAuth.php';
$template = file_get_contents('template/pageTemplate/dettagli-ordineTemplate.html');

$header = printHeader();
$footer = printFooter();

if($_SESSION['tipo']==1){
    $breadcrumb = "<p>Sei in: <a lang=\"en\" href=\"index.php\">Home</a> / <a lang=\"en\" href=\"dashboard.php\">Dashboard</a> / <a href=\"visualizza-ordini.php\">Gestisci ordini</a> / Dettaglio ordine </p>";
    $linkIndietro = "<a href=\"visualizza-ordini.php\" class=\"home-button\">Torna alla visualizzazione ordini</a>";
}else{
    $breadcrumb = "<p>Sei in: <a lang=\"en\" href=\"index.php\">Home</a> / <a href=\"riepilogo-ordini.php\">Riepilogo ordini</a> / Dettaglio ordine </p>";
    $linkIndietro = "<a href=\"riepilogo-ordini.php\" class=\"home-button\">Torna al riepilogo ordini</a>";
}

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$dettaglioOrdine = "";
$totOrdine = "";
$note = "";
$id = $_GET['idOrdine'] ?? "";
if($conn){
    if(!empty($id)){
        $note = $connessione->getNote($id);
        $dettaglioOrdine = $connessione->getDettagliOrdine($id);
        $totOrdine = $connessione->getTotalePrezzoOrdine($id);
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[breadcrumb]', $breadcrumb, $template);
$template = str_replace('[numOrdine]', $id, $template);
$template = str_replace('[dettaglioOrdine]', $dettaglioOrdine, $template);
$template = str_replace('[note]', $note, $template);
$template = str_replace('[linkIndietro]', $linkIndietro, $template);
$template = str_replace('[totOrdine]', $totOrdine, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;