<?php

function printHeader()
{
    $headerHtml = file_get_contents(__DIR__ . "/headerTemplate.html");
    $currentpage = basename($_SERVER['PHP_SELF']);
    $home = "";
    $chisiamo = "";
    $menu = "";
    $areaRis = "";

    if($currentpage == 'index.php') {
        $home = "<li id='currentLink' lang='en'>Home</li>";
    }else{
        $home = "<li><a href='index.php'><span lang='en'>Home</span></a></li>";
    }
    if($currentpage == 'chi-siamo.php') {
        $chisiamo = "<li id='currentLink'>Chi siamo</a></li>";
    }else{
        $chisiamo = "<li><a href='chi-siamo.php'>Chi siamo</a></li>";
    }
    if($currentpage == 'menu-prenota.php') {
        $menu = "<li id='currentLink'>Menù-Prenota</a></li>";
    }else{
        $menu = "<li><a href='menu-prenota.php'>Menù-Prenota</a></li>";
    }

    /*
    <div id="dropdown">
                    <li><button id="droplink">Area riservata</button></li>
                    <div id="dropdown-content">
                        <li><a href="login.html"><span lang=en>Login</span></a></li>
                        <li><a href="registrati.html">Registrati</a></li>
                    </div>
                </div>
    */

    $areaRis = "<li><a href='../login.html'>Area riservata</a></li>";

    $headerHtml = str_replace('[homePlaceholder]', $home, $headerHtml);
    $headerHtml = str_replace('[chiSiamoPlaceholder]', $chisiamo, $headerHtml);
    $headerHtml = str_replace('[menuOrdiniPlaceholder]', $menu, $headerHtml);
    $headerHtml = str_replace('[areaRiservataPlaceholder]', $areaRis, $headerHtml);

    return $headerHtml;
}

function printFooter(){
    $footerHtml = file_get_contents(__DIR__ . "/footerTemplate.html");
    return $footerHtml;
}
