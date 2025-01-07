<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/visualizza-ordiniTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaOrdini = "";
if($conn){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $listaOrdini = $connessione->getOrdini($connessione->queryOrdini(1));
    }else{
        $listaOrdini = $connessione->getOrdini($connessione->queryOrdini());
    }
    $connessione->closeConnection();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'update') {
        $stato = $_POST['stato'];
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $okUpdate = $connessione->updateOrdine($stato);
            $connessione->closeConnection();
            if($okUpdate){
                $message = "<p>Stato modificato con successo</p>";
                header("Location: visualizza-ordini.php?message=$message"); /*NON VA*/
                exit;
            } else {
                $message = "<p>Oops..qualcosa è andato storto. Assicurati che lo stato selezionato non fosse già quello giusto, altrimenti riprova!</p>";
                header("Location: visualizza-ordini.php?message=$message"); /*NON VA*/
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
$template = str_replace('[visOrdini]', $listaOrdini, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;