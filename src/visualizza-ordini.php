<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "visualizza-ordini.php";

require 'script/PHP/checkAdminLogin.php';

$template = file_get_contents('template/pageTemplate/visualizza-ordiniTemplate.html');

$header = printHeader();
$footer = printFooter();
$message = null;
$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaOrdini = "";
$action = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'update') {
        $stato = $_POST['stato'];
        if($conn){
            $okUpdate = $connessione->updateOrdine($stato);
            if($okUpdate){
                $message = "<div class=\"messaggio\">Stato modificato con successo</div>";
            } else {
                $message = "<div class=\"messaggio\">Assicurati che lo stato selezionato non fosse già quello giusto, altrimenti riprova!</div>";
            }
        }
    } else if ($action == 'filter') {
        if($conn) {
            $listaOrdini = $connessione->getOrdini($connessione->queryOrdini(1));
        }
    }
}

if($conn){
    if($listaOrdini == "" && $action != 'filter') {
        $listaOrdini = $connessione->getOrdini($connessione->queryOrdini());
    }
}

if(isset($message)){
    $template = str_replace('[operazione-successo]', $message, $template);
}else{
    $template = str_replace('[operazione-successo]', '', $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[visOrdini]', $listaOrdini, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;