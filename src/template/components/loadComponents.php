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
    $nome = "";
    $navHelp = "";

    if($currentpage == 'index.php') {
        $home = "<li id=\"currentLink\" lang=\"en\">Home</li>";
    }else{
        $home = "<li><a href=\"index.php\"><span lang=\"en\">Home</span></a></li>";
        $home .= "<li class=\"menuMobile-version\"><a href=\"index.php\"><img src=\"assets/icons/home.png\" alt=\"Home\" /></a></li>";
    }
    if($currentpage == 'chi-siamo.php') {
        $chisiamo = "<li id=\"currentLink\">Chi siamo</li>";
    }else{
        $chisiamo = "<li><a href=\"chi-siamo.php\">Chi siamo</a></li>";
        $chisiamo .= "<li class=\"menuMobile-version\"><a href=\"chi-siamo.php\"><img src=\"assets/icons/chi-siamo.png\" alt=\"Chi siamo\" /></a></li>";

    }
    if($currentpage == 'menu-prenota.php') {
        if(isset($_SESSION['tipo']) AND $_SESSION['tipo'] == 1) {
            $menu = "<li id=\"currentLink\">Menù</li>";
        }else{
            $menu = "<li id=\"currentLink\">Menù-Prenota</li>";
        }
        $navHelp .= '<li><a href="#Classica" class="navigationHelp">Vai alle pizze Classiche</a></li>';
        $navHelp .= '<li><a href="#Fuorimenu" class="navigationHelp">Vai alle pizze Fuori Menù</a></li>';
        $navHelp .= '<li><a href="#Speciale" class="navigationHelp">Vai alle pizze Speciali</a></li>';
        $navHelp .= '<li><a href="#Stagionale" class="navigationHelp">Vai alle pizze Stagionali</a></li>';
        $navHelp .= '<li><a href="#cucina" class="navigationHelp">Vai alle nostra cucina</a></li>';
    }else{
        if(isset($_SESSION['tipo']) AND $_SESSION['tipo'] == 1) {
            $menu = "<li><a href=\"menu-prenota.php\">Menù</a></li>";
            $menu .= "<li class=\"menuMobile-version\"><a href=\"menu-prenota.php\"><img src=\"assets/icons/menu.png\" alt=\"Menù\" /></a></li>";
        }else{
            $menu = "<li><a href=\"menu-prenota.php\">Menù-Prenota</a></li>";
            $menu .= "<li class=\"menuMobile-version\"><a href=\"menu-prenota.php\"><img src=\"assets/icons/menu.png\" alt=\"Menù-Prenota\" /></a></li>";
        }

    }
    if($currentpage == 'carrello.php') {
        $carrello = "<li id=\"currentLink\">Carrello</li>";
    }else{
        $carrello = "<li><a href=\"carrello.php\">Carrello</a></li>";
        $carrello .= "<li class=\"menuMobile-version\"><a href=\"carrello.php\"><img src=\"assets/icons/carrello.png\" alt=\"Carrello\" /></a></li>";
    }

    if(isset($_SESSION['tipo'])){
        $nome .= "<p>Ciao ".$_SESSION['nome']. " " .$_SESSION['cognome']."!</p>";
        if($_SESSION['tipo'] == '1') {
            if($currentpage == 'dashboard.php') {
                $areaRis = "<li id=\"currentLink\"><span lang=\"en\">Dashboard</span></li>";
            }else{
                $areaRis = "<li><a href=\"dashboard.php\"><span lang=\"en\">Dashboard</span></a></li>";
                $areaRis .= "<li class=\"menuMobile-version\"><a href=\"dashboard.php\"><img src=\"assets/icons/dashboard.png\" alt=\"Dashboard\" /></a></li>";
            }
            $areaRis .= "<li><a href=\"script/PHP/logout.php\"><span lang=\"en\">Logout</span></a></li>";
            $areaRis .= "<li class=\"menuMobile-version\"><a href=\"script/PHP/logout.php\"><img src=\"assets/icons/logout.png\" alt=\"Logout\" /></a></li>";
        }else{
            if($currentpage == 'riepilogo-ordini.php') {
                $areaRis = "<li id=\"currentLink\">Storico ordini</li>";
            }else{
                $areaRis = "<li><a href=\"riepilogo-ordini.php\">Storico ordini</a></li>";
                $areaRis .= "<li class=\"menuMobile-version\"><a href=\"riepilogo-ordini.php\"><img src=\"assets/icons/ordini.png\" alt=\"Riepilogo ordini\" /></a></li>";
            }
            $areaRis .= "<li><a href=\"script/PHP/logout.php\"><span lang=\"en\">Logout</span></a></li>";
            $areaRis .= "<li class=\"menuMobile-version\"><a href=\"script/PHP/logout.php\"><img src=\"assets/icons/logout.png\" alt=\"Logout\" /></a></li>";
        }
    }else{
        if($currentpage == 'area-riservata.php') {
            $areaRis = "<li id=\"currentLink\">Area riservata</li>";
        }else{
            $areaRis = "<li><a href=\"area-riservata.php\">Area riservata</a></li>";
            $areaRis .= "<li class=\"menuMobile-version\"><a href=\"area-riservata.php\"><img src=\"assets/icons/area-riservata.png\" alt=\"Area riservata\" /></a></li>";

        }
    }

    if($currentpage == 'menu-prenota.php') {
        $headerHtml = str_replace('[menuPrenota-page]', $navHelp, $headerHtml);
    } else {
        $headerHtml = str_replace('[menuPrenota-page]', "", $headerHtml);
    }
    $headerHtml = str_replace('[homePlaceholder]', $home, $headerHtml);
    $headerHtml = str_replace('[chiSiamoPlaceholder]', $chisiamo, $headerHtml);
    $headerHtml = str_replace('[menuOrdiniPlaceholder]', $menu, $headerHtml);
    $headerHtml = str_replace('[nomeUtente]', $nome, $headerHtml);
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
