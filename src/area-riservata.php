<?php

use DB\DBConnection;

include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/area-riservataTemplate.html');

if(!empty($_POST['login-user']) && !empty($_POST['login-password'])){
    $connessione = new DBConnection();
    $conn = $connessione->openDBConnection();
    if($conn){
        $tipoUtente = $connessione->userLogin($_POST['login-user'], $_POST['login-password']);
        if($tipoUtente == 0){
            echo "LOGGATO USER";
            $_SESSION['user'] = $_POST['login-user'];
            $_SESSION['tipo'] = $tipoUtente;
        }else if($tipoUtente == 1){
            echo "LOGGATO ADMIN";
            $_SESSION['user'] = $_POST['login-user'];
            $_SESSION['tipo'] = $tipoUtente;
        }
        else{
            echo "ERROR";
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
