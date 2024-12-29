<?php

include_once 'template/components/loadComponents.php';
require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/chiSiamoTemplate.html');

$header = printHeader();
$footer = printFooter();

$template = str_replace('[header]', $header, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;