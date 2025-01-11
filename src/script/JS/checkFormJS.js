/**
 * chiave: nome dell'input che cerco
 * [0]: Prima indicazione per la compilazione dell'input
 * [1]: espressione regolare da controllare
 * [2]: Hint nel caso in cui input fornito sia sbagliato
 */

var dettagli_form = {
    "nome" : ['Ex: Carciofi', /^[A-Z][a-zA-Z\u00C0-\u024F\ '\-]*$/, 'Inserire un nome con lettera maiuscola di lunghezza almeno 2. Non può contenere numeri o caratteri speciali.'],
};

function caricamento() {
    for(var key in dettagli_form){
        var input = document.getElementById(key);
        messaggio(input, 0);
        input.onblur = function() {validazioneCampo(this);};
    }
}

function validazioneCampo(input) {
    var regex = dettagli_form[input.id][1];
    var text = input.value;

    // Tolgo suggerimento o errore precedente
    var p = input.parentNode;
    p.removeChild(p.children[2]);

    if(text.search(regex) !== 0){
        messaggio(input, 1);
        input.focus(); // Permette di rimanere sull'input errato
        input.select(); // Seleziona tutto l'input e consente di pulirlo; opzionale, solo se è stato scritto così poco che si fa prima a riscrivere da capo
        return false;
    }
    return true;
}

function validazioneForm() {
    for (var key in dettagli_form){
        var input = document.getElementById(key);
        if(!validazioneCampo(input)){
            return false;
        }
    }
    return true;
}

function messaggio(input, mode) {
    /*  mode = 0, modalità input
        mode = 1, modalità errore */

    var node; // tag con il messaggio, da aggiungere sotto il div
    var p = input.parentNode; // il padre, il div
    // Bisognerebbe anche introdurre aria-alert perche' venga letto dallo screen reader

    if (!mode) {
        // Creo messaggio di aiuto
        node = document.createElement('span');
        node.className = 'default-text';  // Nel CSS della prof questa classe fa mettere in azzurro
        node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
    } else {
        // Creo messaggio di errore
        node = document.createElement('strong');
        node.className = 'errorSuggestion';  // Nel CSS della prof questa classe fa mettere in rosso
        node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
    }

    p.appendChild(node);
}