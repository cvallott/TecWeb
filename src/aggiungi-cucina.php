<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';
include_once 'script/PHP/checkForm.php';
//require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/aggiungi-cucinaTemplate.html');

$header = printHeader();
$footer = printFooter();

if(isset($_GET['id'])){
    $breadcrumb= "<p>Sei in: <a lang='en' href='index.php'>Home</a> / <a href='gestisci-prodotti.php'>Gestisci prodotti</a> / <a href='prodotti.php'>Prodotti</a> / Modifica Piatto</p>";
    $titolo = "MODIFICA PIATTO";
}else{
    $breadcrumb= "<p>Sei in: <a lang='en' href='index.php'>Home</a> / <a href='gestisci-prodotti.php'>Gestisci prodotti</a> / Aggiungi Piatto</p>";
    $titolo = "AGGIUNGI PIATTO";
}

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$messaggiPerForm = "";
$nomePiatto = "";
$prezzoPiatto = "";
$ingredientiPiatto[] = "";
$listaIngredienti = "";
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
        $template = str_replace('[percorsoFile]', '"../../aggiungi-cucina.php?id='.$id.'"', $template);
        $connessione->closeConnection();
    } else {
        $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti());
        $connessione->closeConnection();
    }
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['ingredienti'])) {
        $ingr = $_POST['ingredienti'];
    } else {
        $ingr = '';
    }
    if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
        $path = '../../../assets/icons/piatto_icon.png';
    }else{
        $path = '../../../assets/pizze/'. basename($_FILES["file"]["name"]);
    }

    $messaggiPerForm .= "<fieldset class=\"errore-form\"><legend><span lang=\"en\">Warning</span></legend><ul>";
    $nomePiatto = pulisciInput($_POST['nome']);
    $prezzoPiatto = pulisciInput($_POST['prezzo']);
    $ingredientiPiatto = pulisciInput($ingr);

    if (strlen($nomePiatto) == 0) {
        $messaggiPerForm .= "<li>Inserire il nome del piatto</li>";
    } else {
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
    if($path != '../../../assets/icons/piatto_icon.png'){
        $imageUploadResult = checkImage();
        if ($imageUploadResult["success"]) {
            $path = $imageUploadResult["path"];
        } else {
            $messaggiPerForm .= "<li>" . $imageUploadResult["message"] . "</li>";
        }
    }
    $messaggiPerForm .= "</ul></fieldset>";

    if(trim($messaggiPerForm) == "<fieldset class=\"errore-form\"><legend><span lang=\"en\">Warning</span></legend><ul></ul></fieldset>"){
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $veget = $connessione->isVeget($ingredientiPiatto);
            if(empty($_GET['id'])) {
                $okCucina = $connessione->insertCucina($nomePiatto, $prezzoPiatto, $veget, $path);
                $okIngredienti = $connessione->insertProdottoIngrediente($nomePiatto, $ingredientiPiatto, 'cucina');
            } else {
                $okCucina = $connessione->insertCucina($nomePiatto, $prezzoPiatto, $veget, $path, $_GET['id']);
                $okIngredienti = $connessione->insertProdottoIngrediente($nomePiatto, $ingredientiPiatto, 'cucina', $_GET['id']);
            }
            $connessione->closeConnection();
            if(empty($_GET['id'])) {
                if($okCucina && $okIngredienti){
                    $_SESSION['messaggio'] = "<p class=\"messaggio\">Prodotto inserito con successo</p>";
                } else {
                    $_SESSION['messaggio'] = "<p class=\"messaggio\">Oops..qualcosa è andato storto..riprova!</p>";
                }
                header("Location: gestisci-prodotti.php");
            } else {
                if($okCucina && $okIngredienti){
                    $_SESSION['messaggio'] = "<p class=\"messaggio\">Prodotto modificato con successo</p>";
                } else {
                    $_SESSION['messaggio'] = "<p class=\"messaggio\">Oops..qualcosa è andato storto..riprova!</p>";
                }
                header("Location: prodotti.php");
            }
        }
    }
}

if (empty($_GET['id'])){
    $template = str_replace('[valueNome]', '', $template);
    $template = str_replace('[valuePrezzo]', '', $template);
    $template = str_replace('[percorsoFile]', '"../../aggiungi-cucina.php"', $template);
}
$template = str_replace('[header]', $header, $template);
$template = str_replace('[titolo]', $titolo, $template);
$template = str_replace('[breadcrumb]', $breadcrumb, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[messaggiForm]', $messaggiPerForm, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;