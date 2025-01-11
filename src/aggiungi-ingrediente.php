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

// Validation patterns matching JavaScript
$patterns = [
    /*
    'user' => '/^[A-Za-z0-9_]{3,20}$/',
    'password' => '/^.{4,}$/',
    'register-name' => '/^[A-Za-zÀ-ÿ\s\']{2,}$/',
    'register-surname' => '/^[A-Za-zÀ-ÿ\s\']{2,}$/',
    //'register-email' => '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/'
    'register-email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
    */
];
$errorMessages = [
    /*
    'user' => 'Username deve contenere tra 3 e 20 caratteri alfanumerici',
    'password' => 'Password deve contenere almeno 4 caratteri',
    'register-name' => 'Nome deve contenere almeno 2 caratteri, solo lettere',
    'register-surname' => 'Cognome deve contenere almeno 2 caratteri, solo lettere',
    'register-email' => 'Formato email non valido',
    'register-confirm-password' => 'Le password non coincidono',
    'user-exists' => 'Username già in uso',
    'email-exists' => 'Email già registrata',
    'db-error' => 'Errore di connessione al database'*/
];

function pulisciInput($value) {
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}


if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $veget = $_POST['veget'];

    $messaggiPerForm .= "<ul>";
    $nomeIngr = pulisciInput($nome);
    $isVeget = pulisciInput($veget);
    $nomeIngr = ucfirst($nomeIngr);

    if (strlen($nomeIngr) == 0) {
        $messaggiPerForm .= "<li>Inserire il nome dell'ingrediente</li>";
    } else {
        if (strlen($nomeIngr) < 2) {
            $messaggiPerForm .= "<li>Il nome dell'ingrediente deve contenere almeno 2 caratteri</li>";
        }
        if (preg_match("/\d/", $nomeIngr)) {
            $messaggiPerForm .= "<li>Il nome del prodotto non può contenere numeri</li>";
        }
    }
    $messaggiPerForm .= "</ul>";

    if (trim($messaggiPerForm) == "<ul></ul>") {
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