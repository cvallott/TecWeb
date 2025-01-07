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
$pizzeClassiche = "";
if($conn){
    $pizzeClassiche = $connessione->getPizzeClassiche();
    $connessione->closeConnection();
}

$conn = $connessione->openDBConnection();
$menuCategorie = "";
if($conn){
    $menuCategorie = $connessione->getMenuCategorie();
    $connessione->closeConnection();
}

$scroll = $_GET['scroll'] ?? null;
if (isset($_GET['scroll'])) {
    echo "entrato";
    $script = "<script>
            document.addEventListener('DOMContentLoaded', function() {
            var element = document.getElementById('" . htmlspecialchars($_GET['scroll']) . "');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
             });
            </script>";
    $template = str_replace("[script]", $script, $template);
}else{
    $template = str_replace("[script]", "", $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[linkMenu]', $menuCategorie, $template);
$template = str_replace('[menu]', $pizzeClassiche, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;