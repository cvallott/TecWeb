<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
//require 'script/PHP/checkAdminLogin.php';
include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/gestisci-utentiTemplate.html');

$header = printHeader();
$footer = printFooter();
$message = null;
$connessione = new DBConnection();
$listaUtenti = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'update') {
        $ruolo = $_POST['ruolo'];
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $okUpdate = $connessione->updateUtente($ruolo);
            $connessione->closeConnection();
            if($okUpdate){
                $message = "<p class=\"messaggio\">Ruolo modificato con successo</p>"; /*DA FARE CSS DI .messaggio*/
            } else {
                $message = "<p class=\"messaggio\">Oops..qualcosa è andato storto. Assicurati che il ruolo selezionato non fosse già quello giusto, altrimenti riprova!</p>";
            }
        }
    } else if ($action == 'delete') {
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $okDelete = $connessione->delete($connessione->queryDeleteUtente());
            $connessione->closeConnection();
            if($okDelete){
                $message = "<p class=\"messaggio\">Utente eliminato con successo</p>";
            } else {
                $message = "<p class=\"messaggio\">Oops..qualcosa è andato storto. Riprova!</p>";
            }
        }
    } else if ($action == 'filter') {
        $conn = $connessione->openDBConnection();
        if($conn){
            $listaUtenti = $connessione->getUtenti($connessione->queryUtenti(1));
        }
    }
}
$conn = $connessione->openDBConnection();

if($conn){
    if($listaUtenti == ""){
        $listaUtenti = $connessione->getUtenti($connessione->queryUtenti());
    }
    $connessione->closeConnection();
}
if(isset($message)){
    $template = str_replace('[operazione-successo]', $message, $template);
}else{
    $template = str_replace('[operazione-successo]', '', $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaUtenti]', $listaUtenti, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;
