<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
//require 'script/PHP/checkAdminLogin.php';
include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/visualizza-ordiniTemplate.html');

$header = printHeader();
$footer = printFooter();
$message = null;
$connessione = new DBConnection();
$listaOrdini = "";
$action = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'update') {
        $stato = $_POST['stato'];
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $okUpdate = $connessione->updateOrdine($stato);
            $connessione->closeConnection();
            if($okUpdate){
                $message = "<p class=\"messaggio\">Stato modificato con successo</p>";
            } else {
                $message = "<p class=\"messaggio\">Oops..qualcosa è andato storto. Assicurati che lo stato selezionato non fosse già quello giusto, altrimenti riprova!</p>";
            }
        }
    } else if ($action == 'filter') {
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn) {
            $listaOrdini = $connessione->getOrdini($connessione->queryOrdini(1));
        }
    }
}

$conn = $connessione->openDBConnection();
if($conn){
    if($listaOrdini == "" && $action != 'filter') {
        $listaOrdini = $connessione->getOrdini($connessione->queryOrdini());
    }
    $connessione->closeConnection();
}

if(isset($message)){
    $template = str_replace('[operazione-successo]', $message, $template);
}else{
    $template = str_replace('[operazione-successo]', '', $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[visOrdini]', $listaOrdini, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;