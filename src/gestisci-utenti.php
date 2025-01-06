<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/gestisci-utentiTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaUtenti = "";
if($conn){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $listaUtenti = $connessione->getUtenti($connessione->queryUtenti(1));
    }else{
        $listaUtenti = $connessione->getUtenti($connessione->queryUtenti());
    }
    $connessione->closeConnection();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'update') {
        $ruolo = $_POST['ruolo'];
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $okUpdate = $connessione->updateUtente($ruolo);
            $connessione->closeConnection();
            if($okUpdate){
                $message = "Ruolo modificato con successo";
                header("Location: gestisci-utenti.php?message=$message"); /*NON VA*/
                exit;
            } else {
                $message = "Oops..qualcosa è andato storto. Assicurati che il ruolo selezionato non fosse già quello giusto, altrimenti riprova!";
                header("Location: gestisci-utenti.php?message=$message"); /*NON VA*/
                exit;
            }
        }
    } else if ($action == 'delete') {
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $okDelete = $connessione->deleteUtente();
            $connessione->closeConnection();
            if($okDelete){
                $message = "Utente eliminato con successo";
                header("Location: gestisci-utenti.php?message=$message"); /*NON VA*/
                exit;
            } else {
                $message = "Oops..qualcosa è andato storto. Riprova!";
                header("Location: gestisci-utenti.php?message=$message"); /*NON VA*/
                exit;
            }
        }
    }
}

if($message = isset($_GET['message']) ? urldecode($_GET['message']) : ""){
    $template = str_replace('[operazione-successo]', $message, $template);
}else{
    $template = str_replace('[operazione-successo]', '', $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaUtenti]', $listaUtenti, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;