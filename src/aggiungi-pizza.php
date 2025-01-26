<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';

$_SESSION['redirect']= "aggiungi-pizza.php";

require 'script/PHP/checkAdminLogin.php';
include_once 'script/PHP/checkForm.php';
$template = file_get_contents('template/pageTemplate/aggiungi-pizzaTemplate.html');

$header = printHeader();
$footer = printFooter();

if(isset($_GET['id'])){
    $breadcrumb= '<p>Sei in: <a lang="en" href="index.php">Home</a> / <a lang="en" href="dashboard.php">Dashboard</a> / <a href="prodotti.php">Prodotti</a> / Modifica pizza</p>';
    $titolo = "MODIFICA PIZZA";
}else{
    $breadcrumb= '<p>Sei in: <a lang="en" href="index.php">Home</a> / <a lang="en" href="dashboard.php">Dashboard</a> / Aggiungi pizza</p>';
    $titolo = "AGGIUNGI PIZZA";
}

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$messaggiPerForm = "";
$nomePizza= "";
$prezzoPizza = "";
$ingredientiPizza = array();
$descrizionePizza = "";
$listaIngredienti = "";
$categorie = "";
$currentPath = "";
$valueInfo = array();
if($conn){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti(), $id);
        $categorie = $connessione->getCategorie($id);
        $valueInfo = $connessione->getInfoPizza($id);
        if(!empty($valueInfo)){
            $template = str_replace('[valueNome]', 'value = "'.$valueInfo[0].'"', $template);
            $template = str_replace('[valuePrezzo]', 'value = "'.$valueInfo[1].'"', $template);
            if(!empty($valueInfo[2])){
                $template = str_replace('[valueDescr]', $valueInfo[2], $template);
            }else{
                $template = str_replace('[valueDescr]', '', $template);
            }
        }
        $template = str_replace('[percorsoFile]', '"aggiungi-pizza.php?id='.$id.'"', $template);
    } else {
        $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti());
        $categorie = $connessione->getCategorie();
    }
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['ingredienti'])) {
        $ingredientiPizza = pulisciInput($_POST['ingredienti']);
    } else {
        $ingredientiPizza = '';
    }

    if(!isset($_GET['id'])){
        if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
            $path = 'assets/icons/pizza_icon.png';
        }else{
            $path = 'assets/pizze/'. basename($_FILES["file"]["name"]);
        }
    } else {
        $currentPath = $connessione->getCurrentPath($_GET['id'],'pizza');
        if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
            $path = $currentPath;
        } else {
            $path = 'assets/pizze/'. basename($_FILES["file"]["name"]);
        }
    }

    $messaggiPerForm .= "<fieldset class=\"errore-form\"><legend><span role=\"alert\" lang=\"en\">Warning</span></legend><ul>";
    $nomePizza = pulisciInput($_POST['nome']);
    $prezzoPizza = pulisciInput($_POST['prezzo']);
    $categoriaPizza = pulisciInput($_POST['cat']);
    $descrizionePizza = pulisciDescrizione($_POST['descr']);

    if (strlen($nomePizza) == 0) {
        $messaggiPerForm .= "<li>Inserire il nome della pizza</li>";
    } else {
        if(!isset($_GET['id'])){
            if ($conn && $connessione->checkPizza($nomePizza) > 0) {
                $messaggiPerForm .= "<li role=\"alert\">Il nome della pizza inserito è già presente</li>";
            }
        }
        if (strlen($nomePizza) < 2) {
            $messaggiPerForm .= "<li role=\"alert\">Il nome della pizza deve contenere almeno 2 caratteri</li>";
        }
        if (preg_match("/\d/", $nomePizza)) {
            $messaggiPerForm .= "<li role=\"alert\">Il nome della pizza non può contenere numeri</li>";
        }
        if (!preg_match("/^[A-Z][a-zÀ-ÖØ-öø-ÿ]*(?: [a-zÀ-ÖØ-öø-ÿ]+)*$/", $nomePizza)) {
            $messaggiPerForm .= "<li role=\"alert\">Il nome della pizza deve iniziare con una lettera maiuscola e le altre lettere devono essere minuscole</li>";
        }
    }
    if (strlen($prezzoPizza) == 0) {
        $messaggiPerForm .= "<li>Inserire il prezzo della pizza</li>";
        if (!is_numeric($prezzoPizza) || $prezzoPizza <= 0) {
            $messaggiPerForm .= "<li role=\"alert\">Il prezzo deve essere un numero maggiore di 0</li>";
        }
        if (!preg_match("/^[1-9]\d*(\.\d+)?$/", $prezzoPizza)) {
            $messaggiPerForm .= "<li role=\"alert\">Il prezzo deve essere un numero intero o decimale (ex. 8.50)</li>";
        }
    }
    if ($ingredientiPizza == '') {
        $messaggiPerForm .= "<li role=\"alert\">La pizza deve avere almeno un ingrediente</li>";
    }
    if (strlen($categoriaPizza) == 0) {
        $messaggiPerForm .= "<li>Inserire la categoria della pizza</li>";
    }
    if(!isset($_GET['id'])){
        if($path != 'assets/icons/pizza_icon.png'){
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

    $messaggiPerForm .= "</ul></fieldset>";

    if(trim($messaggiPerForm) == "<fieldset class=\"errore-form\"><legend><span role=\"alert\" lang=\"en\">Warning</span></legend><ul></ul></fieldset>"){
        if($conn){
            $veget = $connessione->isVeget($ingredientiPizza);
            if(empty($_GET['id'])) {
                $okPizza = $connessione->insertPizza($nomePizza, $prezzoPizza, $veget, $categoriaPizza, $descrizionePizza, $path);
                $okIngredienti = $connessione->insertProdottoIngrediente($nomePizza, $ingredientiPizza, 'pizza');
            }else {
                $okPizza = $connessione->insertPizza($nomePizza, $prezzoPizza, $veget, $categoriaPizza, $descrizionePizza, $path, $_GET['id']);
                $okIngredienti = $connessione->insertProdottoIngrediente($nomePizza, $ingredientiPizza, 'pizza', $_GET['id']);
            }
            if(empty($_GET['id'])) {
                if($okPizza && $okIngredienti){
                    $_SESSION['messaggio'] = "<p class=\"messaggio\">Prodotto inserito con successo</p>";
                } else {
                    $_SESSION['messaggio'] = "<p role=\"alert\" class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</p>";
                }
                header("Location: dashboard.php");
            }else{
                if($okPizza && $okIngredienti){
                    $_SESSION['messaggio'] = "<p class=\"messaggio\">Prodotto modificato con successo</p>";
                } else {
                    $_SESSION['messaggio'] = "<p role=\"alert\" class=\"messaggio\">Non siamo riusciti a gestire la tua richiesta, riprova altrimenti contattaci!</p>";
                }
                header("Location: prodotti.php");
            }
        }
    }
}

if (empty($_GET['id'])){
    $template = str_replace('[valueNome]', '', $template);
    $template = str_replace('[valuePrezzo]', '', $template);
    $template = str_replace('[valueDescr]', '', $template);
    $template = str_replace('[percorsoFile]', '"aggiungi-pizza.php"', $template);
}
$template = str_replace('[header]', $header, $template);
$template = str_replace('[titolo]', $titolo, $template);
$template = str_replace('[breadcrumb]', $breadcrumb, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[categorie]', $categorie, $template);
$template = str_replace('[messaggiForm]', $messaggiPerForm, $template);
$template = str_replace('[footer]', $footer, $template);

$connessione->closeConnection();

echo $template;