<?php

session_start();

include_once 'template/components/loadComponents.php';
$template = file_get_contents('template/pageTemplate/carrelloTemplate.html');

$header = printHeader();
$footer = printFooter();



if (!isset($_SESSION['carrello'])) {
    $_SESSION['carrello'] = [];

}

/*
$_SESSION['carrello'][1] = ['nome' => "provauno", 'quantita' => 20, 'prezzo' => 20];
$_SESSION['carrello'][2] = ['nome' => "provadue", 'quantita' => 60, 'prezzo' => 20];
$_SESSION['carrello'][3] = ['nome' => "provatre", 'quantita' => 1, 'prezzo' => 20];
$_SESSION['carrello'][4] = ['nome' => "provaquattro", 'quantita' => 50, 'prezzo' => 20]; */

function aggiornaCarrello($id, $nome, $quantita, $prezzo) {
    if (isset($_SESSION['carrello'][$id])) {
        $_SESSION['carrello'][$id]['quantita'] += $quantita;
    } else {
        $_SESSION['carrello'][$id] = ['nome' => $nome, 'quantita' => $quantita, 'prezzo' => $prezzo];
    }
}

function rimuoviDalCarrello($id) {
    unset($_SESSION['carrello'][$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $quantita = intval($_POST['quantita'] ?? 1);

    if ($_POST['azione'] === 'aggiungi' && $id && $nome) {
        aggiornaCarrello($id, $nome, $quantita,20);
    } elseif ($_POST['azione'] === 'incrementa' && $id) {
        aggiornaCarrello($id, '', 1,'');
    } elseif ($_POST['azione'] === 'decrementa' && $id) {
        if (isset($_SESSION['carrello'][$id]) && $_SESSION['carrello'][$id]['quantita'] > 1) {
            $_SESSION['carrello'][$id]['quantita']--;
        } else {
            rimuoviDalCarrello($id);
        }
    } elseif ($_POST['azione'] === 'rimuovi' && $id) {
        rimuoviDalCarrello($id);
    }
}

$totale = 0;

$rowsCarrello = "";
foreach ($_SESSION['carrello'] as $id => $item) {
    $rowsCarrello .= '<div class="row">';
    $rowsCarrello .= '<div class="dettaglioItem">';
    $rowsCarrello .= '<h4>'. $item['nome'] .'</h4>';
    $rowsCarrello .= '<p>variazioni</p>';
    $rowsCarrello .= '</div><div class="dettaglioItem">';
    $rowsCarrello .= '<h4>'. $item['prezzo'] .' €</h4>';
    $totale += $item['quantita']*$item['prezzo'];
    $rowsCarrello .= '</div><div class="dettaglioItem"><div class="controlloQuantita">';
    $rowsCarrello .= '<form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="decrementa"><i class="fa fa-minus"></i></button>
                    </form>';
    $rowsCarrello .= '<h4>'. $item['quantita'] .'</h4>';
    $rowsCarrello .= '<form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="incrementa"><i class="fa fa-plus"></i></button>
                    </form>';
    $rowsCarrello .= '<form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="rimuovi"><i class="fa fa-trash"></i></button>
                    </form>';
    $rowsCarrello .= '</div></div></div>';

}


$template = str_replace('[header]', $header, $template);
$template = str_replace('[rowsCarrello]', $rowsCarrello, $template);
$template = str_replace('[tot]', $totale, $template);
$template = str_replace('[footer]', $footer, $template);

echo $template;

