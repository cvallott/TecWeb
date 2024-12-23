<?php

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/dettagli-ordineTemplate.html');

$header = printHeader();
$footer = printFooter();

$template = str_replace('[header]', $header, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;