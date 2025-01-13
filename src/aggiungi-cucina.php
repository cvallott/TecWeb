<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';
include_once 'script/PHP/checkForm.php';
//require 'script/PHP/checkAdminLogin.php';
$template = file_get_contents('template/pageTemplate/aggiungi-cucinaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$messaggiPerForm = "";
$nomePiatto = "";
$prezzoPiatto = "";
$ingredientiPiatto[] = "";
$listaIngredienti = "";
if($conn){
    $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti());
    $connessione->closeConnection();
}

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $prezzo = $_POST['prezzo'];
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
    $nomePiatto = pulisciInput($nome);
    $prezzoPiatto = pulisciInput($prezzo);
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
        if (!preg_match("/^[A-Z][a-z\u00C0-\u024F]*(?: [a-z\u00C0-\u024F]+)*$/", $nomePiatto)) {
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

    if(trim($messaggiPerForm) == "<fieldset><ul></ul></fieldset>"){
        $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
        $conn = $connessione->openDBConnection();
        if($conn){
            $veget = $connessione->isVeget($ingredientiPiatto);
            $okCucina = $connessione->insertCucina($nomePiatto, $prezzoPiatto, $veget, $path);
            $okIngredienti = $connessione->insertProdottoIngrediente($nomePiatto, $ingredientiPiatto, 'cucina');
            $connessione->closeConnection();
            if($okCucina && $okIngredienti){
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
$template = str_replace('[messaggiForm]', $messaggiPerForm, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;