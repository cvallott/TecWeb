<?php

include_once 'template/components/loadComponents.php';
include_once 'script/PHP/dbConnection.php';
use DB\DBConnection;

$template = file_get_contents('template/pageTemplate/aggiungi-ingredienteTemplate.html');

$header = printHeader();
$footer = printFooter();

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $veget = $_POST['veget'];
    $pagg = $_POST['pagg'];
    $connessione = new DBConnection();
    $conn = $connessione->openDBConnection();
    if($conn){
        $pizzeSpeciali = $connessione->insertIngredient($nome, $veget, $pagg);
        $connessione->closeConnection();
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;