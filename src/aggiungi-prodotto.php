<?php

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/aggiungi-prodottoTemplate.html');

$header = printHeader();
$footer = printFooter();

if($message = isset($_GET['message']) ? urldecode($_GET['message']) : ""){
    $template = str_replace('[aggiunto-prodotto]', $message, $template);
}else{
    $template = str_replace('[aggiunto-prodotto]', '', $template);
}


$template = str_replace('[header]', $header, $template);

$template = str_replace('[footer]', $footer, $template);

echo $template;