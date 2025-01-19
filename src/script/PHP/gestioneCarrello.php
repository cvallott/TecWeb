<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' AND isset($_POST['azione'])) {

    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $prezzo = $_POST['prezzo'] ?? null;
    $quantita = intval($_POST['quantita'] ?? 1);

    if ($_POST['azione'] === 'aggiungi' && $id && $nome && $prezzo) {
        aggiornaCarrello($id, $nome, $quantita,$prezzo);
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

    if(!isset($_SESSION['tipo'])){
        unset($_SESSION['carrello']);
        header('Location: area-riservata.php');
    }else if(isset($_GET['scroll'])){
        header('Location:' . basename($_SERVER['PHP_SELF']) . '?' . $_GET['scroll']);
    }else{
        header('Location:' . basename($_SERVER['PHP_SELF']));
    }

}

if (!isset($_SESSION['carrello'])) {
    $_SESSION['carrello'] = [];

}

function aggiornaCarrello($id, $nome, $quantita, $prezzo): void {
    if (isset($_SESSION['carrello'][$id])) {
        $_SESSION['carrello'][$id]['quantita'] += $quantita;
    } else {
        $_SESSION['carrello'][$id] = ['nome' => $nome, 'quantita' => $quantita, 'prezzo' => $prezzo];
    }
}

function rimuoviDalCarrello($id): void {
    unset($_SESSION['carrello'][$id]);
}

function getCarrello() : string {
    $rowsCarrello = "";
    foreach ($_SESSION['carrello'] as $id => $item) {
        $rowsCarrello .= '<div class="row">';
        $rowsCarrello .= '<div class="dettaglioItem">';
        $rowsCarrello .= '<h4>'. $item['nome'] .'</h4>';
        $rowsCarrello .= '</div><div class="dettaglioItem">';
        $rowsCarrello .= '<p>&euro; '. $item['prezzo'] .'</p>';
        $rowsCarrello .= '</div><div class="dettaglioItem"><div class="controlloQuantita">';
        $rowsCarrello .= '<form method="POST" action="carrello.php" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="decrementa"><img src="assets/icons/minus.png" alt="Decrementa"/></button>
                    </form>';
        $rowsCarrello .= '<p>'. $item['quantita'] .'</p>';
        $rowsCarrello .= '<form method="POST" action="carrello.php" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="incrementa"><img src="assets/icons/plus.png" alt="Incrementa"/></button>
                    </form>';
        $rowsCarrello .= '<form method="POST" action="carrello.php" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="rimuovi"><img src="assets/icons/cestino.png" alt="Rimuovi"/></button>
                    </form>';
        $rowsCarrello .= '</div></div></div>';
    }
    return $rowsCarrello;
}

/*
function getQuantita($id){
    if(isset($_SESSION['carrello'][$id]['quantita'])){
        return $_SESSION['carrello'][$id]['quantita'];
    }
    return 0;
}*/

function getTotale(): float {
    $tot = 0;
    foreach ($_SESSION['carrello'] as $id => $item) {
        $tot += $item['quantita']*$item['prezzo'];
    }
    return $tot;
}

