<?php

include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "aggiungi-ingrediente.php";

include_once 'script/PHP/dbConnection.php';
include_once 'script/PHP/checkForm.php';
require 'script/PHP/checkAdminLogin.php';
use DB\DBConnection;

$template = file_get_contents('template/pageTemplate/aggiungi-ingredienteTemplate.html');

$header = printHeader();
$footer = printFooter();
$messaggiPerForm = "";
$nomeIngr = '';
$isVeget = '';

if(isset($_GET['id'])){
    $breadcrumb= '<p>Sei in: <a lang="en" href="index.php">Home</a> / <a lang="en" href="dashboard.php">Dashboard</a> / <a href="prodotti.php">Prodotti</a> / Modifica ingrediente</p>';
    $titolo = "MODIFICA INGREDIENTE";
}else{
    $breadcrumb= '<p>Sei in: <a lang="en" href="index.php">Home</a> / <a lang="en" href="dashboard.php">Dashboard</a> / Aggiungi ingrediente</p>';
    $titolo = "AGGIUNGI INGREDIENTE";
}

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
if($conn) {
    if (isset($_GET['nome'])) {
        $nome = str_replace("_", " ", $_GET['nome']);
        $ingrInfo = $connessione->infoIngredienti($connessione->queryIngredienti($nome));
        if(!empty($ingrInfo)) {
            $template = str_replace('[valueNome]', 'value = "'.$ingrInfo[0].'"', $template);
            if($ingrInfo[1] == 0){
                $template = str_replace('[valueSi]', '', $template);
                $template = str_replace('[valueNo]', 'checked', $template);
            } else {
                $template = str_replace('[valueNo]', '', $template);
                $template = str_replace('[valueSi]', 'checked', $template);
            }
        }
        $template = str_replace('[percorsoFile]', '"aggiungi-ingrediente.php?nome='.$nome.'"', $template);
    }
}

if (isset($_POST['submit'])) {
    $messaggiPerForm .= "<div role=\"alert\" class=\"errore-form\"><span lang=\"en\">Warning</span><ul>";
    $nomeIngr = pulisciInput($_POST['nome']);
    $isVeget = pulisciInput($_POST['veget']);

    if (strlen($nomeIngr) == 0) {
        $messaggiPerForm .= "<li>Inserire il nome dell'ingrediente</li>";
    } else {
        if ($conn && $connessione->checkIngrediente($nomeIngr) > 0) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente inserito è già presente</li>";
        }
        if (strlen($nomeIngr) < 2) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente deve contenere almeno 2 caratteri</li>";
        }
        if (preg_match("/\d/", $nomeIngr)) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente non può contenere numeri</li>";
        }
        if (!preg_match("/^[A-Z][a-zÀ-ÖØ-öø-ÿ]*(?: [a-zÀ-ÖØ-öø-ÿ]+)*$/", $nomeIngr)) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente deve iniziare con una lettera maiuscola e le altre lettere devono essere minuscole</li>";
        }
    }
    if (strlen($isVeget) == 0) {
        $messaggiPerForm .= "<li>Indicare se l'ingrediente è vegetariano o no</li>";
    }
    $messaggiPerForm .= "</ul></div>";

    if(trim($messaggiPerForm) == "<div role=\"alert\" class=\"errore-form\"><span lang=\"en\">Warning</span><ul></ul></div>"){
        if ($conn) {
            if(empty($_GET['nome'])) {
                $okIngredienti = $connessione->insertIngrediente($nomeIngr, $isVeget);
            } else {
                $okIngredienti = $connessione->insertIngrediente($nomeIngr, $isVeget, $_GET['nome']);
            }
            if(empty($_GET['nome'])) {
                if ($okIngredienti) {
                    $_SESSION['messaggio'] = "<div class=\"messaggio\">Prodotto inserito con successo</div>";
                } else {
                    $_SESSION['messaggio'] = "<div class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</div>";
                }
                header("Location: dashboard.php");
            }else{
                if($okIngredienti){
                    $_SESSION['messaggio'] = "<div class=\"messaggio\">Prodotto modificato con successo</div>";
                } else {
                    $_SESSION['messaggio'] = "<div class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</div>";
                }
                header("Location: prodotti.php");
            }
        }
    }
}

if (empty($_GET['id'])){
    $template = str_replace('[valueNome]', '', $template);
    $template = str_replace('[valueSi]', '', $template);
    $template = str_replace('[valueNo]', 'checked', $template);
    $template = str_replace('[percorsoFile]', '"aggiungi-ingrediente.php"', $template);
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[titolo]', $titolo, $template);
$template = str_replace('[breadcrumb]', $breadcrumb, $template);
$template = str_replace('[messaggiForm]', $messaggiPerForm, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;