<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "prodotti.php";

require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/prodottiTemplate.html');

$header = printHeader();
$footer = printFooter();
$message = null;
$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaProdotti = "";
$action = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'deletePizza') {
        if($conn){
            $okDelete = $connessione->delete($connessione->queryDeletePizza());
            if($okDelete){
                $message = "<p class=\"messaggio\">Pizza eliminata con successo</p>";
            } else {
                $message = "<p role=\"alert\" class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</p>";
            }
        }
    } else if ($action == 'deleteCucina') {
        if($conn){
            $okDelete = $connessione->delete($connessione->queryDeleteCucina());
            if($okDelete){
                $message = "<p class=\"messaggio\">Piatto eliminato con successo</p>";
            } else {
                $message = "<p role=\"alert\" class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</p>";
            }
        }
    } else if ($action == 'deleteIngrediente') {
        if ($conn) {
            $nome = str_replace("_", " ", $_POST['nome']);
            $connessione->removeAssocProdIngr($nome);
            $okDelete = $connessione->delete($connessione->queryDeleteIngrediente());
            if ($okDelete) {
                $message = "<p class=\"messaggio\">Ingrediente eliminato con successo</p>";
            } else {
                $message = "<p role=\"alert\" class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</p>";
            }
        }
    } else if ($action == 'filter') {
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

if($conn){
    if($listaProdotti == '' && $action != 'filter'){
        for($conta = 0; $conta < 3; $conta++) {
            $listaProdotti .= $connessione->filtraProdotti($conta, 0);
        }
    }
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

$connessione->closeConnection();

echo $template;