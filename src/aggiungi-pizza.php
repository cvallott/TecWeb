<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';
//require 'script/PHP/checkAdminLogin.php';
include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/aggiungi-pizzaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaIngredienti = "";
$categorie = "";
if($conn){
    $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti());
    $categorie = $connessione->getCategorie();
    $connessione->closeConnection();
}

/*MANCANO I CONTROLLI PHP E JS*/
if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $prezzo = $_POST['prezzo'];
    $cat = $_POST['cat'];
    $descrizione = $_POST['descr'];
    if (!empty($_POST['ingredienti'])) {
        // Popola un array con le scelte selezionate
        $ingr = $_POST['ingredienti'];
    } /*else {
        echo "Non hai selezionato nessuna opzione";
    }*/
    /*$image = $_FILES['file'];*/
    if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
        $path = '../../../assets/icons/pizza_icon.png';
    }else{
        $path = '../../../assets/pizze/'. basename($_FILES["file"]["name"]);
    }

    $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
    $conn = $connessione->openDBConnection();
    if($conn){
        $veget = $connessione->isVeget($ingr);
        if($path != '../../../assets/icons/pizza_icon.png'){
            $connessione->uploadImage();
        }
        $okPizza = $connessione->insertPizza($nome, $prezzo, $veget, $cat, $descrizione, $path);
        $okIngredienti = $connessione->insertProdottoIngrediente($nome, $ingr, 'pizza');
        $connessione->closeConnection();
        if($okPizza && $okIngredienti){
            $_SESSION['messaggio'] = "Prodotto inserito con successo";
        } else {
            $_SESSION['messaggio'] = "Oops..qualcosa è andato storto..riprova!";
        }
        header("Location: aggiungi-prodotto.php");
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[categorie]', $categorie, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;