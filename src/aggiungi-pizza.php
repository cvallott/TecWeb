<?php

use DB\DBConnection;
include_once 'script/PHP/dbConnection.php';

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/aggiungi-pizzaTemplate.html');

$header = printHeader();
$footer = printFooter();

$connessione = new DBConnection();
$conn = $connessione->openDBConnection();
$listaIngredienti = "";
$categorie = "";
if($conn){
    $listaIngredienti = $connessione->getIngredienti();
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
    $path = 'da fare';
    $connessione = new DBConnection(); /* HA SENSO USARE UN'ALTRA CONNESSIONE OPPURE USO QUELLA DI PRIMA? */
    $conn = $connessione->openDBConnection();
    $veget = $connessione->isVeget($ingr);
    if($conn){
        $connessione->insertPizza($nome, $prezzo, $veget, $cat, $descrizione, $path);
        $connessione->insertPizzaIngrediente($nome, $ingr);
        $connessione->closeConnection();
    }
}

$template = str_replace('[header]', $header, $template);
$template = str_replace('[listaIngredienti]', $listaIngredienti, $template);
$template = str_replace('[categorie]', $categorie, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;