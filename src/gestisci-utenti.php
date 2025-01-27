<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "gestisci-utenti.php";

require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/gestisci-utentiTemplate.html');

$header = printHeader();
$footer = printFooter();
$message = null;
$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaUtenti = "";
$action = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'update') {
        $ruolo = $_POST['ruolo'];
        if($conn){
            $okUpdate = $connessione->updateUtente($ruolo);
            if($okUpdate){
                $message = "<div class=\"messaggio\">Ruolo modificato con successo</div>";
            } else {
                $message = "<div role=\"alert\" class=\"messaggio\">Assicurati che il ruolo selezionato non fosse già quello giusto, altrimenti riprova!</div>";
            }
        }
    } else if ($action == 'delete') {
        if($conn){
            $okDelete = $connessione->delete($connessione->queryDeleteUtente());
            if($okDelete){
                $message = "<div class=\"messaggio\">Utente eliminato con successo</div>";
            } else {
                $message = "<div role=\"alert\" class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</div>";
            }
        }
    } else if ($action == 'filter') {
        if($conn){
            $listaUtenti = $connessione->getUtenti($connessione->queryUtenti(1));
        }
    }
}

if($conn){
    if($listaUtenti == ""  && $action != 'filter'){
        $listaUtenti = $connessione->getUtenti($connessione->queryUtenti());
    }
}


if(isset($message)){
    $template = str_replace('[operazione-successo]', $message, $template);
}else{
    $template = str_replace('[operazione-successo]', '', $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaUtenti]', $listaUtenti, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;
