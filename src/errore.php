<?php

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/error/genericErrorTemplate.html');

$header = printHeader();
$footer = printFooter();

$template = str_replace('[header]', $header, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;
