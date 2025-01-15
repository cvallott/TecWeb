<?php

include_once 'template/components/loadComponents.php';
//require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/gestisci-prodottiTemplate.html');

$header = printHeader();
$footer = printFooter();
$messaggio = '';

/*
if($message = isset($_GET['message']) ? urldecode($_GET['message']) : ""){
    $template = str_replace('[aggiunto-prodotto]', $message, $template);
}else{
    $template = str_replace('[aggiunto-prodotto]', '', $template);
}*/

if (isset($_SESSION['messaggio'])) {
    $messaggio = $_SESSION['messaggio'];
    unset($_SESSION['messaggio']);
}


$template = str_replace('[header]', $header, $template);
$template = str_replace('[aggiunto-prodotto]', $messaggio, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;