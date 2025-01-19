<?php
use DB\DBConnection;

include_once 'script/PHP/dbConnection.php';
include_once 'template/components/loadComponents.php';
$paginaHTML = file_get_contents('template/pageTemplate/area-riservataTemplate.html');

$messaggiPerForm = "";
$messaggiRegForm = "";
$connessione = new DBConnection();
$connessioneOK = $connessione->openDBConnection();

// Initialize form variables
$loginUser = '';
$registerName = '';
$registerSurname = '';
$registerEmail = '';
$registerUser = '';

// Validation patterns matching JavaScript
$patterns = [
    'user' => '/^[A-Za-z0-9_]{3,20}$/',
    'password' => '/^.{4,}$/',
    'register-name' => '/^[A-Za-zÀ-ÿ\s\']{2,}$/',
    'register-surname' => '/^[A-Za-zÀ-ÿ\s\']{2,}$/',
    //'register-email' => '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/'
    'register-email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'

];

$errorMessages = [
    'user' => 'Username deve contenere tra 3 e 20 caratteri alfanumerici',
    'password' => 'Password deve contenere almeno 4 caratteri',
    'register-name' => 'Nome deve contenere almeno 2 caratteri, solo lettere',
    'register-surname' => 'Cognome deve contenere almeno 2 caratteri, solo lettere',
    'register-email' => 'Formato email non valido',
    'register-confirm-password' => 'Le password non coincidono',
    'user-exists' => 'Username già in uso',
    'email-exists' => 'Email già registrata',
    'db-error' => 'Errore di connessione al database'
];

function pulisciInput($value) {
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}

function validaCampo($value, $pattern) {
    return preg_match($pattern, $value);
}

// Handle Login Form
if (isset($_POST['login-user']) && isset($_POST['login-password'])) {
    $messaggiPerForm .= "<fieldset class=\"errore-form\"><legend><span role=\"alert\" lang=\"en\">Warning</span></legend><ul>";
    $hasErrors = false;

    $loginUser = pulisciInput($_POST['login-user']);
    $loginPassword = $_POST['login-password'];

    // Validate username
    if (!validaCampo($loginUser, $patterns['user'])) {
        $messaggiPerForm .= '<li role="alert">' . $errorMessages['user'] . '</li>';
        $hasErrors = true;
    }

    // Validate password
    if (!validaCampo($loginPassword, $patterns['password'])) {
        $messaggiPerForm .= '<li role="alert">' . $errorMessages['password'] . "</li>";
        $hasErrors = true;
    }

    if (!$hasErrors) {
        if ($connessioneOK) {
            $dettagliUtente = $connessione->userLogin($loginUser, $loginPassword);
            if ($dettagliUtente) {
                $_SESSION['nome'] = $dettagliUtente[0];
                $_SESSION['cognome'] = $dettagliUtente[1];
                $_SESSION['tipo'] = $dettagliUtente[2];
                $_SESSION['email'] = $dettagliUtente[3];
                header('location: index.php');
                exit();
            } else {
                $messaggiPerForm .= '<li role="alert">Credenziali non valide</li>';
            }
        } else {
            $messaggiPerForm .= '<li role="alert">' . $errorMessages['db-error'] . "</li>";
        }
        $connessione->closeConnection();
    }
    $messaggiPerForm .= "</ul></fieldset>";
}

// Handle Registration Form
if (isset($_POST['register-name']) && isset($_POST['register-password'])) {
    $messaggiRegForm .= "<fieldset class=\"errore-form\"><legend><span role=\"alert\" lang=\"en\">Warning</span></legend><ul>";
    $hasErrors = false;

    $registerName = pulisciInput($_POST['register-name']);
    $registerSurname = pulisciInput($_POST['register-surname']);
    $registerEmail = pulisciInput($_POST['register-email']);
    $registerUser = pulisciInput($_POST['register-user']);
    $registerPassword = $_POST['register-password'];
    $confirmPassword = $_POST['register-confirm_password'];

    // Validate all fields
    if (!validaCampo($registerName, $patterns['register-name'])) {
        $messaggiRegForm .= '<li role="alert">'. $errorMessages['register-name'] . "</li>";
        $hasErrors = true;
    }

    if (!validaCampo($registerSurname, $patterns['register-surname'])) {
        $messaggiRegForm .= '<li role="alert">' . $errorMessages['register-surname'] . "</li>";
        $hasErrors = true;
    }

    if (!validaCampo($registerUser, $patterns['user'])) {
        $messaggiRegForm .= '<li role="alert">' . $errorMessages['user'] . "</li>";
        $hasErrors = true;
    }

    if (!validaCampo($registerEmail, $patterns['register-email'])) {
        $messaggiRegForm .= '<li role="alert">' . $errorMessages['register-email'] . "</li>";
        $hasErrors = true;
    }

    if (!validaCampo($registerPassword, $patterns['password'])) {
        $messaggiRegForm .= '<li role="alert">' . $errorMessages['password'] . "</li>";
        $hasErrors = true;
    }

    if ($registerPassword !== $confirmPassword) {
        $messaggiRegForm .= '<li role="alert">' . $errorMessages['register-confirm-password'] . "</li>";
        $hasErrors = true;
    }

    if (!$hasErrors) {
        if ($connessioneOK) {
            // Check if username already exists
            if ($connessione->checkUserExists($registerUser)) {
                $messaggiRegForm .= '<li role="alert">' . $errorMessages['user-exists'] . "</li>";
            }
            // Check if email already exists
            else if ($connessione->checkEmailExists($registerEmail)) {
                $messaggiRegForm .= '<li role="alert">' . $errorMessages['email-exists'] . "</li>";
            }
            else {
                $hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);
                if ($connessione->registerUser($registerName, $registerSurname, $registerUser, $registerEmail, $hashedPassword)) {
                    $_SESSION['nome'] = $registerName;
                    $_SESSION['cognome'] = $registerSurname;
                    $_SESSION['tipo'] = 0;
                    header("Location: index.php?registration=success");
                    exit();
                } else {
                    $messaggiRegForm .= '<li role="alert">' . $errorMessages['db-error'] . "</li>";
                }
            }
        } else {
            $messaggiRegForm .= '<li role="alert">' . $errorMessages['db-error'] . "</li>";
        }
        $connessione->closeConnection();
    }
    $messaggiRegForm .= "</ul></fieldset>";
}

// Replace placeholders in HTML
$paginaHTML = str_replace('[header]', printHeader(), $paginaHTML);
$paginaHTML = str_replace('[footer]', printFooter(), $paginaHTML);
$paginaHTML = str_replace('[messaggiForm]', $messaggiPerForm, $paginaHTML);
$paginaHTML = str_replace('[messaggiRegForm]', $messaggiRegForm, $paginaHTML);
$paginaHTML = str_replace('[valLoginUser]', $loginUser, $paginaHTML);
$paginaHTML = str_replace('[valRegisterName]', $registerName, $paginaHTML);
$paginaHTML = str_replace('[valRegisterSurname]', $registerSurname, $paginaHTML);
$paginaHTML = str_replace('[valRegisterUser]', $registerUser, $paginaHTML);
$paginaHTML = str_replace('[valRegisterEmail]', $registerEmail, $paginaHTML);

echo $paginaHTML;
