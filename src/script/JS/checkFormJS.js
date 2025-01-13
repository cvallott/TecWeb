var dettagli_form = {
    "nome": ['Ex: Carciofi', /^[A-Z][a-z\u00C0-\u024F]*(?: [a-z\u00C0-\u024F]+)*$/, 'Inserire un nome con solo l\'iniziale maiuscola di lunghezza almeno 2. Non può contenere numeri o caratteri speciali.'],
    "prezzo": ['Ex: 8.50', /^[1-9]\d*(\.\d+)?$/, 'Inserire un numero maggiore di zero.'],
};

function caricamento() {
    var dataFormValue = document.body.getAttribute('data-form'); // O il nodo pertinente
    if (dataFormValue === 'pizze') {
        dettagli_form.nome[0] = 'Ex: Capricciosa';
    } else if (dataFormValue === 'cucina') {
        dettagli_form.nome[0] = 'Ex: Anelli di cipolla';
    }

    for (var key in dettagli_form) {
        var input = document.getElementById(key);
        messaggio(input, 0); // Mostra il messaggio di aiuto iniziale

        input.addEventListener('blur', function () {
            validazioneCampo(this); // Controlla il campo, ma senza bloccare l'utente
        });
    }
}

function validazioneCampo(input) {
    var regex = dettagli_form[input.id][1];
    var text = input.value;
    var p = input.parentNode;

    // Rimuovo eventuali messaggi di errore precedenti
    var nodesToRemove = p.querySelectorAll('.mex');
    nodesToRemove.forEach(function (node) {
        p.removeChild(node);
    });

    // Validazione
    if (text.search(regex) !== 0) {
        messaggio(input, 1); // Mostra il messaggio di errore
        input.classList.add('error'); // Aggiunge classe per stile (es: bordo rosso)
        return false;
    } else {
        input.classList.remove('error'); // Rimuove eventuali segnalazioni di errore
    }
    return true;
}

function validazioneForm() {
    var primoErrore = null; // Per memorizzare il primo campo con errore
    var valido = true; // Flag per la validazione complessiva

    for (var key in dettagli_form) {
        var input = document.getElementById(key);
        if (!validazioneCampo(input)) {
            valido = false; // Se almeno un campo non è valido, il form non è valido

            // Memorizza il primo errore se non è già stato trovato
            if (!primoErrore) {
                primoErrore = input;
            }
        }
    }
    // Se c'è un errore, scrolla al primo campo non valido e metti il focus
    if (primoErrore) {
        primoErrore.scrollIntoView({ behavior: 'smooth', block: 'center' });
        primoErrore.focus(); // Sposta il focus sul primo errore
    }
    return valido; // Restituisce true solo se tutti i campi sono validi
}

function messaggio(input, mode) {
    var node;
    var p = input;

    if (!mode) {
        // Messaggio di aiuto
        node = document.createElement('span');
        node.className = 'default-text';
        node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
        p.parentNode.insertBefore(node, p);
    } else {
        // Messaggio di errore
        node = document.createElement('strong');
        node.className = 'errorSuggestion mex';
        node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
        p.parentNode.appendChild(node);
    }
}


/*
var dettagli_form = {
    "nome": ['Ex: Carciofi', /^[A-Z][a-z\u00C0-\u024F]*(?: [a-z\u00C0-\u024F]+)*$/, 'Inserire un nome con solo l\'iniziale maiuscola di lunghezza almeno 2. Non può contenere numeri o caratteri speciali.'],
    "prezzo": ['Ex: 8.50', /^[1-9]\d*(\.\d+)?$/, 'Inserire un numero maggiore di zero.'],
};

function caricamento() {
    var dataFormValue = document.body.getAttribute('data-form'); // O il nodo pertinente
    if (dataFormValue === 'pizze') {
        dettagli_form.nome[0] = 'Ex: Capricciosa';
    } else if (dataFormValue === 'cucina') {
        dettagli_form.nome[0] = 'Ex: Anelli di cipolla';
    }

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
    var nodesToRemove = p.querySelectorAll('.mex'); // Seleziona tutti gli elementi con la classe .mex
    nodesToRemove.forEach(function(node) {
        p.removeChild(node); // Rimuove ogni nodo trovato
    });

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
    //  mode = 0, modalità input
    //   mode = 1, modalità errore

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
*/