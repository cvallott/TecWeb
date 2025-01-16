<?php

session_start();
function printHeader()
{
    $headerHtml = file_get_contents(__DIR__ . "/headerTemplate.html");
    $currentpage = basename($_SERVER['PHP_SELF']);
    $_SESSION['currentpage'] = $currentpage;
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
        $chisiamo = "<li id='currentLink'>Chi siamo</li>";
    }else{
        $chisiamo = "<li><a href='chi-siamo.php'>Chi siamo</a></li>";
    }
    if($currentpage == 'menu-prenota.php') {
        if(isset($_SESSION['tipo']) AND $_SESSION['tipo'] == 1) {
            $menu = "<li id='currentLink'>Menù</li>";
        }else{
            $menu = "<li id='currentLink'>Menù-Prenota</li>";
        }

    }else{
        if(isset($_SESSION['tipo']) AND $_SESSION['tipo'] == 1) {
            $menu = "<li><a href='menu-prenota.php'>Menù</a></li>";
        }else{
            $menu = "<li><a href='menu-prenota.php'>Menù-Prenota</a></li>";
        }

    }
    if($currentpage == 'carrello.php') {
        $carrello = "<li id='currentLink'><i class='fa fa-shopping-cart'></i></li>";
    }else{
        $carrello = "<li><a href='carrello.php'><i class='fa fa-shopping-cart'></i></a></li>";
    }
    if(isset($_SESSION['tipo'])){
        if($_SESSION['tipo'] == '1') {
            $areaRis .= "<div id='dropdown'>";
            $areaRis .= "<li><button id='droplink'>".$_SESSION['nome']. " " .$_SESSION['cognome']." </button><i class='fa fa-caret-down'></i></li>";
            $areaRis .= "<div id='dropdown-content'>";
            $areaRis .= "<li><a href='visualizza-ordini.php'>Ordini</span></a></li>";
            $areaRis .= "<li><a href='gestisci-prodotti.php'>Gestione menù</span></a></li>";
            $areaRis .= "<li><a href='gestisci-utenti.php'>Utenti</span></a></li>";
            $areaRis .= "<li><a href='script/PHP/logout.php'>Logout</span></a></li>";
            $areaRis .= "</div></div>";
        }else{
            $areaRis .= "<div id='dropdown'>";
            $areaRis .= "<li><button id='droplink'>".$_SESSION['nome']. " " .$_SESSION['cognome']."</button><i class='fa fa-caret-down'></i></li>";
            $areaRis .= "<div id='dropdown-content'>";
            $areaRis .= "<li><a href='riepilogo-ordini.php'>Storico ordini</span></a></li>";
            $areaRis .= "<li><a href='script/PHP/logout.php'>Logout</span></a></li>";
            $areaRis .= "</div></div>";
        }
    }else{
        $areaRis = "<li><a href='area-riservata.php'>Area riservata</a></li>";
    }

    $headerHtml = str_replace('[homePlaceholder]', $home, $headerHtml);
    $headerHtml = str_replace('[chiSiamoPlaceholder]', $chisiamo, $headerHtml);
    $headerHtml = str_replace('[menuOrdiniPlaceholder]', $menu, $headerHtml);
    if(isset($_SESSION['tipo']) AND $_SESSION['tipo'] != '1') {
        $headerHtml = str_replace('[carrelloPlaceholder]', $carrello, $headerHtml);
    }else{
        $headerHtml = str_replace('[carrelloPlaceholder]', "", $headerHtml);
    }
    $headerHtml = str_replace('[areaRiservataPlaceholder]', $areaRis, $headerHtml);

    return $headerHtml;
}

function printFooter(){
    $footerHtml = file_get_contents(__DIR__ . "/footerTemplate.html");
    return $footerHtml;
}
