<?php

//session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $quantita = intval($_POST['quantita'] ?? 1);

    if ($_POST['azione'] === 'aggiungi' && $id && $nome) {
        aggiornaCarrello($id, $nome, $quantita,20);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif ($_POST['azione'] === 'incrementa' && $id) {
        aggiornaCarrello($id, '', 1,'');

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif ($_POST['azione'] === 'decrementa' && $id) {
        if (isset($_SESSION['carrello'][$id]) && $_SESSION['carrello'][$id]['quantita'] > 1) {
            $_SESSION['carrello'][$id]['quantita']--;
        } else {
            rimuoviDalCarrello($id);
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif ($_POST['azione'] === 'rimuovi' && $id) {
        rimuoviDalCarrello($id);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

if (!isset($_SESSION['carrello'])) {
    $_SESSION['carrello'] = [];

}

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

function getCarrello(){
    $rowsCarrello = "";
    foreach ($_SESSION['carrello'] as $id => $item) {
        $rowsCarrello .= '<div class="row">';
        $rowsCarrello .= '<div class="dettaglioItem">';
        $rowsCarrello .= '<h4>'. $item['nome'] .'</h4>';
        $rowsCarrello .= '<p>variazioni</p>';
        $rowsCarrello .= '</div><div class="dettaglioItem">';
        $rowsCarrello .= '<h4>'. $item['prezzo'] .' €</h4>';
        $rowsCarrello .= '</div><div class="dettaglioItem"><div class="controlloQuantita">';
        $rowsCarrello .= '<form method="POST" action="" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="decrementa"><i class="fa fa-minus"></i></button>
                    </form>';
        $rowsCarrello .= '<h4>'. $item['quantita'] .'</h4>';
        $rowsCarrello .= '<form method="POST" action="" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="incrementa"><i class="fa fa-plus"></i></button>
                    </form>';
        $rowsCarrello .= '<form method="POST" action="" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="rimuovi"><i class="fa fa-trash"></i></button>
                    </form>';
        $rowsCarrello .= '</div></div></div>';
    }
    return $rowsCarrello;
}

function getTotale(){
    $tot = 0;
    foreach ($_SESSION['carrello'] as $id => $item) {
        $tot += $item['quantita']*$item['prezzo'];
    }
    return $tot;
}

