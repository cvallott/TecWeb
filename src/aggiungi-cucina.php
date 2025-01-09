<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/aggiungi-cucinaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaIngredienti = "";
if($conn){
    $listaIngredienti = $connessione->getIngredienti($connessione->queryIngredienti());
    $connessione->closeConnection();
}

/*MANCANO I CONTROLLI PHP E JS*/
if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $prezzo = $_POST['prezzo'];
    if (!empty($_POST['ingredienti'])) {
        // Popola un array con le scelte selezionate
        $ingr = $_POST['ingredienti'];
    } /*else {
        echo "Non hai selezionato nessuna opzione";
    }*/
    /*$image = $_FILES['file'];*/
    if(!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE){
        $path = '../../../assets/icons/piatto_icon.png';
    }else{
        $path = '../../../assets/pizze/'. basename($_FILES["file"]["name"]);
    }

    $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
    $conn = $connessione->openDBConnection();
    if($conn){
        $veget = $connessione->isVeget($ingr);
        if($path != '../../../assets/icons/piatto_icon.png'){
            $connessione->uploadImage();
        }
        $okCucina = $connessione->insertCucina($nome, $prezzo, $veget, $path);
        $okIngredienti = $connessione->insertProdottoIngrediente($nome, $ingr, 'cucina');
        $connessione->closeConnection();
        if($okCucina && $okIngredienti){
            $message = "Prodotto inserito con successo";
            header("Location: aggiungi-prodotto.php?message=$message"); /*NON VA*/
            exit;
        } else {
            /* BUUUU ERRORE */
        }
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;