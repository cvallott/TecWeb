<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' AND isset($_POST['azione'])) {

    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $prezzo = $_POST['prezzo'] ?? null;
    $quantita = intval($_POST['quantita'] ?? 1);

    $totQuantita = getTotaleQuantita();

    if ($_POST['azione'] === 'Aggiungi al carrello' && $id && $nome && $prezzo) {
        if($totQuantita+1<=10){
            aggiornaCarrello($id, $nome, $quantita,$prezzo);
            $_SESSION['aggiunta_carrello'] = "<div class=\"messaggio\"><p>Prodotto aggiunto al carrello! Continua a visitare il nostro menù e procedi all'ordine!</p></div>";
        }else {
            $_SESSION['aggiunta_carrello'] = "<div role=\"alert\" class=\"messaggio\"><p>Ci spiace ma hai raggiunto la quantità massima di 10 pizze. Contattaci telefonicamente per ordini maggiori</p></div>";
        }
    } elseif ($_POST['azione'] === 'incrementa' && $id) {
        if($totQuantita+1<=10){
            aggiornaCarrello($id, '', 1,'');
        }else {
            $_SESSION['aggiunta_carrello'] = "<div role=\"alert\" class=\"messaggio\"><p>Ci spiace ma hai raggiunto la quantità massima di 10 pizze. Contattaci telefonicamente per ordini maggiori</p></div>";
        }
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

function getTotaleQuantita(): int{
    $quantitaTot = 0;
    foreach ($_SESSION['carrello'] as $id => $q) {
        $quantitaTot += $q['quantita'];
    }
    return $quantitaTot;
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
                        <input type="hidden" name="id" value="'.$id.'" />
                        <input type="hidden" name="azione" value="decrementa" />
                        <button type="submit" aria-label="Rimuovi una '.$item['nome'].' al carrello" value="Rimuovi una '.$item['nome'].' al carrello"><img src="assets/icons/minus.png" alt="" /></button>
                    </form>';
        $rowsCarrello .= '<p>'. $item['quantita'] .'</p>';
        $rowsCarrello .= '<form method="POST" action="carrello.php" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'" />
                        <input type="hidden" name="azione" value="incrementa" />
                        <button type="submit" aria-label="Aggiungi una '.$item['nome'].' al carrello" value="Aggiungi una '.$item['nome'].' al carrello"><img src="assets/icons/plus.png" alt="" /></button>
                    </form>';
        $rowsCarrello .= '<form method="POST" action="carrello.php" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'" />
                        <input type="hidden" name="azione" value="rimuovi" />
                        <button type="submit" aria-label="Rimuovi '.$item['nome'].' dall\'ordine" value="Rimuovi '.$item['nome'].' dall\'ordine"><img src="assets/icons/cestino.png" alt="" /></button>
                    </form>';
        $rowsCarrello .= '</div></div></div>';
    }
    return $rowsCarrello;
}

function getTotale(): float {
    $tot = 0;
    foreach ($_SESSION['carrello'] as $id => $item) {
        $tot += $item['quantita']*$item['prezzo'];
    }
    return $tot;
}

