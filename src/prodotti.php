<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
//require 'script/PHP/checkAdminLogin.php';
include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/prodottiTemplate.html');

$header = printHeader();
$footer = printFooter();
$message = null;
$connessione = new DBConnection();
$listaProdotti = "";
$action = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'update') {
        /*$ruolo = $_POST['ruolo'];
        $connessione = new DBConnection(); // HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA?
        $conn = $connessione->openDBConnection();
        if($conn){
            $okUpdate = $connessione->updateUtente($ruolo);
            $connessione->closeConnection();
            if($okUpdate){
                $message = "<p class=\"messaggio\">Ruolo modificato con successo</p>";
            } else {
                $message = "<p class=\"messaggio\">Oops..qualcosa è andato storto. Assicurati che il ruolo selezionato non fosse già quello giusto, altrimenti riprova!</p>";
            }
        }*/
    } else if ($action == 'deletePizza') {
        $connessione = new DBConnection(); // HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA?
        $conn = $connessione->openDBConnection();
        if($conn){
            $okDelete = $connessione->delete($connessione->queryDeletePizza());
            $connessione->closeConnection();
            if($okDelete){
                $message = "<p class=\"messaggio\">Pizza eliminata con successo</p>";
            } else {
                $message = "<p class=\"messaggio\">Oops..qualcosa è andato storto. Riprova!</p>";
            }
        }
    } else if ($action == 'deleteCucina') {
        $connessione = new DBConnection(); // HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA?
        $conn = $connessione->openDBConnection();
        if($conn){
            $okDelete = $connessione->delete($connessione->queryDeleteCucina());
            $connessione->closeConnection();
            if($okDelete){
                $message = "<p class=\"messaggio\">Piatto eliminato con successo</p>";
            } else {
                $message = "<p class=\"messaggio\">Oops..qualcosa è andato storto. Riprova!</p>";
            }
        }
    } else if ($action == 'deleteIngrediente') {
        $connessione = new DBConnection(); // HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA?
        $conn = $connessione->openDBConnection();
        if ($conn) {
            $okDelete = $connessione->delete($connessione->queryDeleteIngrediente());
            $connessione->closeConnection();
            if ($okDelete) {
                $message = "<p class=\"messaggio\">Ingrediente eliminato con successo</p>";
            } else {
                $message = "<p class=\"messaggio\">Oops..qualcosa è andato storto. Riprova!</p>";
            }
        }
    } else if ($action == 'filter') {
        $conn = $connessione->openDBConnection();
        if ($conn) {
            if($_POST['tipo'] == ''){
                for($conta = 0; $conta < 3; $conta++) {
                    $listaProdotti .= $connessione->filtraProdotti($conta);
                }
            } else {
                $listaProdotti = $connessione->filtraProdotti();
            }
        }
    }
}
$conn = $connessione->openDBConnection();

if($conn){
    if($listaProdotti == '' && $action != 'filter'){
        for($conta = 0; $conta < 3; $conta++) {
            $listaProdotti .= $connessione->filtraProdotti($conta, 0);
        }
    }
    $connessione->closeConnection();
}

if(isset($_SESSION['messaggio'])){
    $template = str_replace('[operazione-successo]', $_SESSION['messaggio'], $template);
    unset($_SESSION['messaggio']);
}
if(isset($message)){
    $template = str_replace('[operazione-successo]', $message, $template);
}else{
    $template = str_replace('[operazione-successo]', '', $template);
}


$template = str_replace('[header]', $header, $template);
$template = str_replace('[visProdotti]', $listaProdotti, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;