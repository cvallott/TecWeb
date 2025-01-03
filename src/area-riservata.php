<?php

use DB\DBConnection;

include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/area-riservataTemplate.html');

if(!empty($_POST['login-user']) && !empty($_POST['login-password'])){
    $connessione = new DBConnection();
    $conn = $connessione->openDBConnection();
    if($conn){
        $dettagliutente = $connessione->userLogin($_POST['login-user'], $_POST['login-password']);
        if($dettagliutente){
            $_SESSION['nome']=$dettagliutente[0];
            $_SESSION['cognome']=$dettagliutente[1];
            $_SESSION['tipo']=$dettagliutente[2];
        }
        else{
            echo "errore autenticazione";
        }
    }else{
        echo "Connection error";
    }
    $connessione->closeConnection();
}

$header = printHeader();
$footer = printFooter();

$template = str_replace('[header]', $header, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;
