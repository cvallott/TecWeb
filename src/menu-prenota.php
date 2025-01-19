<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';
require 'script/PHP/gestioneCarrello.php';
$template = file_get_contents('template/pageTemplate/menu-prenotaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$menuCategorie = "";
$pizze= '';
$cucina = '';
if($conn){
    $menuCategorie = $connessione->getMenuCategorie();
    $menuCategorie .= "<a href='#".str_replace(' ','', "cucina")."'>"."La nostra Cucina"."</a>";
}

if(empty($_POST['action'])){
    if($conn){
        $pizze = $connessione->getMenuPizze();
        $cucina = $connessione->getMenuCucina();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'filter') {
    if ($conn) {
        if ($_POST['tipo'] == ''){
            if(!empty($_POST['nome'])){
                $pizze = $connessione->getMenuPizze($_POST['nome']);
                $cucina = $connessione->getMenuCucina($_POST['nome']);
            } else{
                $pizze = $connessione->getMenuPizze();
                $cucina = $connessione->getMenuCucina();
            }
        } else if ($_POST['tipo'] == 0){
            if(!empty($_POST['nome'])){
                $pizze = $connessione->getMenuPizze($_POST['nome']);
            } else {
                $pizze = $connessione->getMenuPizze();
            }
            $cucina = '';
        } else {
            if(!empty($_POST['nome'])){
                $cucina = $connessione->getMenuCucina($_POST['nome']);
            } else {
                $cucina = $connessione->getMenuCucina();
            }
            $pizze = '';
        }
    }
}

if(isset($_SESSION['aggiunta_carrello'])){
    $template = str_replace('[messaggio-carrello]', $_SESSION['aggiunta_carrello'], $template);
} else{
    $template = str_replace('[messaggio-carrello]', "", $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[linkMenu]', $menuCategorie, $template);
$template = str_replace('[menu]', $pizze, $template);
$template = str_replace('[cucina]', $cucina, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;