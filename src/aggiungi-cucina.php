<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "aggiungi-cucina.php";

include_once 'script/PHP/checkForm.php';
require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/aggiungi-cucinaTemplate.html');

$header = printHeader();
$footer = printFooter();

if(isset($_GET['id'])){
    $breadcrumb= '<p>Sei in: <a lang="en" href="index.php">Home</a> / <a lang="en" href="dashboard.php">Dashboard</a> / <a href="prodotti.php">Prodotti</a> / Modifica piatto</p>';
    $titolo = "MODIFICA PIATTO";
}else{
    $breadcrumb= '<p>Sei in: <a lang="en" href="index.php">Home</a> / <a lang="en" href="dashboard.php">Dashboard</a> / Aggiungi piatto</p>';
    $titolo = "AGGIUNGI PIATTO";
}

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$messaggiPerForm = "";
$nomePiatto = "";
$prezzoPiatto = "";
$ingredientiPiatto = array();
$listaIngredienti = "";
$currentPath = "";
$valueInfo = array();
if($conn){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti(), $id, 1);
        $valueInfo = $connessione->getInfoCucina($id);
        if(!empty($valueInfo)){
            $template = str_replace('[valueNome]', 'value = "'.$valueInfo[0].'"', $template);
            $template = str_replace('[valuePrezzo]', 'value = "'.$valueInfo[1].'"', $template);
        }
        $template = str_replace('[percorsoFile]', '"aggiungi-cucina.php?id='.$id.'"', $template);
    } else {
        $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti());
    }
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['ingredienti'])) {
        $ingr = $_POST['ingredienti'];
    } else {
        $ingr = '';
    }
    if(!isset($_GET['id'])){
        if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
            $path = 'assets/icons/piatto_icon.png';
        }else{
            $path = 'assets/pizze/'. basename($_FILES["file"]["name"]);
        }
    } else {
        $currentPath = $connessione->getCurrentPath($_GET['id'],'cucina');
        if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
            $path = $currentPath;
        } else {
            $path = 'assets/pizze/'. basename($_FILES["file"]["name"]);
        }
    }

    $messaggiPerForm .= "<div role=\"alert\" class=\"errore-form\"><span lang=\"en\">Warning</span><ul>";
    $nomePiatto = pulisciInput($_POST['nome']);
    $prezzoPiatto = pulisciInput($_POST['prezzo']);
    $ingredientiPiatto = pulisciInput($ingr);

    if (strlen($nomePiatto) == 0) {
        $messaggiPerForm .= "<li>Inserire il nome del piatto</li>";
    } else {
        if(!isset($_GET['id'])){
            if ($conn && $connessione->checkCucina($nomePiatto) > 0) {
                $messaggiPerForm .= "<li role=\"alert\">Il nome del piatto inserito è già presente</li>";
            }
        }
        if (strlen($nomePiatto) < 2) {
            $messaggiPerForm .= "<li>Il nome del piatto deve contenere almeno 2 caratteri</li>";
        }
        if (preg_match("/\d/", $nomePiatto)) {
            $messaggiPerForm .= "<li>Il nome del piatto non può contenere numeri</li>";
        }
        if (!preg_match("/^[A-Z][a-zÀ-ÖØ-öø-ÿ]*(?: [a-zÀ-ÖØ-öø-ÿ]+)*$/", $nomePiatto)) {
            $messaggiPerForm .= "<li>Il nome del piatto deve iniziare con una lettera maiuscola e le altre lettere devono essere minuscole</li>";
        }
    }
    if (!is_numeric($prezzoPiatto) || $prezzoPiatto <= 0) {
        $messaggiPerForm .= "<li>Il prezzo deve essere un numero maggiore di 0</li>";
    }
    if ($ingredientiPiatto == '') {
        $messaggiPerForm .= "<li>Il piatto deve avere almeno un ingrediente</li>";
    }
    if(!isset($_GET['id'])){
        if($path != 'assets/icons/piatto_icon.png'){
            $imageUploadResult = checkImage();
            if ($imageUploadResult["success"]) {
                $path = $imageUploadResult["path"];
            } else {
                $messaggiPerForm .= "<li>" . $imageUploadResult["message"] . "</li>";
            }
        }
    } else {
        if($path != $currentPath){
            $imageUploadResult = checkImage();
            if ($imageUploadResult["success"]) {
                $path = $imageUploadResult["path"];
            } else {
                $messaggiPerForm .= "<li>" . $imageUploadResult["message"] . "</li>";
            }
        }
    }
    $messaggiPerForm .= "</ul></div>";

    if(trim($messaggiPerForm) == "<div role=\"alert\" class=\"errore-form\"><span lang=\"en\">Warning</span><ul></ul></div>"){
        if($conn){
            $veget = $connessione->isVeget($ingredientiPiatto);
            if(empty($_GET['id'])) {
                $okCucina = $connessione->insertCucina($nomePiatto, $prezzoPiatto, $veget, $path);
                $okIngredienti = $connessione->insertProdottoIngrediente($nomePiatto, $ingredientiPiatto, 'cucina');
            } else {
                $okCucina = $connessione->insertCucina($nomePiatto, $prezzoPiatto, $veget, $path, $_GET['id']);
                $okIngredienti = $connessione->insertProdottoIngrediente($nomePiatto, $ingredientiPiatto, 'cucina', $_GET['id']);
            }
            if(empty($_GET['id'])) {
                if($okCucina && $okIngredienti){
                    $_SESSION['messaggio'] = "<div class=\"messaggio\">Prodotto inserito con successo</div>";
                } else {
                    $_SESSION['messaggio'] = "<div class=\"messaggio\"  role=\"alert\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</div>";
                }
                header("Location: dashboard.php");
            } else {
                if($okCucina && $okIngredienti){
                    $_SESSION['messaggio'] = "<div class=\"messaggio\">Prodotto modificato con successo</div>";
                } else {
                    $_SESSION['messaggio'] = "<div class=\"messaggio\" role=\"alert\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</div>";
                }
                header("Location: prodotti.php");
            }
        }
    }
}

if (empty($_GET['id'])){
    $template = str_replace('[valueNome]', '', $template);
    $template = str_replace('[valuePrezzo]', '', $template);
    $template = str_replace('[percorsoFile]', '"aggiungi-cucina.php"', $template);
}
$template = str_replace('[header]', $header, $template);
$template = str_replace('[titolo]', $titolo, $template);
$template = str_replace('[breadcrumb]', $breadcrumb, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[messaggiForm]', $messaggiPerForm, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;