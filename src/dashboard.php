<?php

include_once 'template/components/loadComponents.php';
require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/dashboardTemplate.html');

$header = printHeader();
$footer = printFooter();
$messaggio = '';

if (isset($_SESSION['messaggio'])) {
    $messaggio = $_SESSION['messaggio'];
    unset($_SESSION['messaggio']);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[aggiunto-prodotto]', $messaggio, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;