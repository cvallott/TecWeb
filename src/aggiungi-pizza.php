<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
//require 'script/PHP/checkAdminLogin.php';
include_once 'template/components/loadComponents.php';
include_once 'script/PHP/checkForm.php';
$template = file_get_contents('template/pageTemplate/aggiungi-pizzaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$messaggiPerForm = "";
$nomePizza= "";
$prezzoPizza = "";
$ingredientiPizza[] = "";
$descrizionePizza = "";
$listaIngredienti = "";
$categorie = "";
if($conn){
    $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti());
    $categorie = $connessione->getCategorie();
    $connessione->closeConnection();
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['ingredienti'])) {
        $ingredientiPizza = pulisciInput($_POST['ingredienti']);
    } else {
        $ingredientiPizza = '';
    }

    if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
        $path = '../../../assets/icons/pizza_icon.png';
    }else{
        $path = '../../../assets/pizze/'. basename($_FILES["file"]["name"]);
    }

    $messaggiPerForm .= "<fieldset class=\"errore-form\"><legend><span lang=\"en\">Warning</span></legend><ul>";
    $nomePizza = pulisciInput($_POST['nome']);
    $prezzoPizza = pulisciInput($_POST['prezzo']);
    if (!empty($ingr)) {
        $ingredientiPizza = pulisciInput($ingr);
    } else {
        $ingredientiPizza = '';
    }
    $categoriaPizza = pulisciInput($_POST['cat']);
    $descrizionePizza = pulisciDescrizione($_POST['descr']);

    if (strlen($nomePizza) == 0) {
        $messaggiPerForm .= "<li>Inserire il nome della pizza</li>";
    } else {
        if (strlen($nomePizza) < 2) {
            $messaggiPerForm .= "<li>Il nome della pizza deve contenere almeno 2 caratteri</li>";
        }
        if (preg_match("/\d/", $nomePizza)) {
            $messaggiPerForm .= "<li>Il nome della pizza non può contenere numeri</li>";
        }
        if (!preg_match("/^[1-9]\d*(\.\d+)?$/", $nomePizza)) {
            $messaggiPerForm .= "<li>Il nome della pizza deve iniziare con una lettera maiuscola e le altre lettere devono essere minuscole</li>";
        }
    }
    if (strlen($nomePizza) == 0) {
        $messaggiPerForm .= "<li>Inserire il prezzo della pizza</li>";
        if (!is_numeric($prezzoPizza) || $prezzoPizza <= 0) {
            $messaggiPerForm .= "<li>Il prezzo deve essere un numero maggiore di 0</li>";
        }
    }
    if ($ingredientiPizza == '') {
        $messaggiPerForm .= "<li>La pizza deve avere almeno un ingrediente</li>";
    }
    if (strlen($categoriaPizza) == 0) {
        $messaggiPerForm .= "<li>Inserire la categoria della pizza</li>";
    }
    if($path != '../../../assets/icons/pizza_icon.png'){
        $imageUploadResult = checkImage();
        if ($imageUploadResult["success"]) {
            $path = $imageUploadResult["path"];
        } else {
            $messaggiPerForm .= "<li>" . $imageUploadResult["message"] . "</li>";
        }
    }
    $messaggiPerForm .= "</ul></fieldset>";

    if(trim($messaggiPerForm) == "<fieldset><ul></ul></fieldset>"){
        if($conn){
            $veget = $connessione->isVeget($ingredientiPizza);
            $okPizza = $connessione->insertPizza($nomePizza, $prezzoPizza, $veget, $categoriaPizza, $descrizionePizza, $path);
            $okIngredienti = $connessione->insertProdottoIngrediente($nomePizza, $ingredientiPizza, 'pizza');
            $connessione->closeConnection();
            if($okPizza && $okIngredienti){
                $_SESSION['messaggio'] = "Prodotto inserito con successo";
            } else {
                $_SESSION['messaggio'] = "Oops..qualcosa è andato storto..riprova!";
            }
            header("Location: aggiungi-prodotto.php");
        }
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[categorie]', $categorie, $template);
$template = str_replace('[messaggiForm]', $messaggiPerForm, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;