<?php

include_once 'template/components/loadComponents.php';
include_once 'script/PHP/dbConnection.php';
//require 'script/PHP/checkAdminLogin.php';
use DB\DBConnection;

$template = file_get_contents('template/pageTemplate/aggiungi-ingredienteTemplate.html');

$header = printHeader();
$footer = printFooter();
$messaggiPerForm = "";
$nomeIngr = '';
$isVeget = '';

if (isset($_POST['submit'])) {
    if(isset($_SESSION['modificaAttiva'])){
        
    }

    $messaggiPerForm .= "<fieldset class=\"errore-form\"><legend><span lang=\"en\">Warning</span></legend><ul>";
    $nomeIngr = pulisciInput($_POST['nome']);
    $isVeget = pulisciInput($_POST['veget']);

    if (strlen($nomeIngr) == 0) {
        $messaggiPerForm .= "<li>Inserire il nome dell'ingrediente</li>";
    } else {
        $connessione = new DBConnection();
        $conn = $connessione->openDBConnection();
        if ($conn && $connessione->checkIngrediente($nomeIngr) > 0) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente inserito è già presente</li>";
        }
        $connessione->closeConnection();
        if (strlen($nomeIngr) < 2) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente deve contenere almeno 2 caratteri</li>";
        }
        if (preg_match("/\d/", $nomeIngr)) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente non può contenere numeri</li>";
        }
        if (!preg_match("/^[A-Z][a-z\u00C0-\u024F]*(?: [a-z\u00C0-\u024F]+)*$/", $nomeIngr)) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente deve iniziare con una lettera maiuscola e le altre lettere devono essere minuscole</li>";
        }
    }
    if (strlen($isVeget) == 0) {
        $messaggiPerForm .= "<li>Indicare se l'ingrediente è vegetariano o no</li>";
    }
    $messaggiPerForm .= "</ul></fieldset>";

    if (trim($messaggiPerForm) == "<fieldset><ul></ul></fieldset>") {
        $connessione = new DBConnection();
        $conn = $connessione->openDBConnection();
        if ($conn) {
            $okIngredienti = $connessione->insertIngrediente($nomeIngr, $isVeget);
            $connessione->closeConnection();
            if ($okIngredienti) {
                $_SESSION['messaggio'] = "Prodotto inserito con successo";
            } else {
                $_SESSION['messaggio'] = "Oops..qualcosa è andato storto..riprova!";
            }
            header("Location: aggiungi-prodotto.php");
        }
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[messaggiForm]', $messaggiPerForm, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;