var dettagli_form = {
    "nome": ['Es: Carciofi', /^[A-Z][a-z\u00C0-\u024F]*(?: [a-z\u00C0-\u024F]+)*$/, 'Inserire un nome con solo l\'iniziale maiuscola di lunghezza almeno 2. Non può contenere numeri o caratteri speciali.'],
    "prezzo": ['Es: 8.50', /^[1-9]\d*(\.\d+)?$/, 'Inserire un numero maggiore di zero.'],
    "login-user": ["Inserisci username", /^[A-Za-z0-9_]{3,20}$/, "Username deve contenere tra 3 e 20 caratteri alfanumerici"],
    "login-password": ["Inserisci password", /^.{4,}$/, "Password deve contenere almeno 4 caratteri"],
    "register-name": ["Es: Mario", /^[A-Za-z\u00C0-\u024F\s']{2,}$/, "Nome deve contenere almeno 2 caratteri, solo lettere"],
    "register-surname": ["Es: Rossi", /^[A-Za-z\u00C0-\u024F\s']{2,}$/, "Cognome deve contenere almeno 2 caratteri, solo lettere"],
    "register-user": ["Inserisci username", /^[A-Za-z0-9_]{3,20}$/, "Username deve contenere tra 3 e 20 caratteri alfanumerici"],
    "register-email": ["Es: mario.rossi@email.com", /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, "Formato email non valido"],
    "register-password": ["Inserisci password", /^.{4,}$/, "Password deve contenere almeno 4 caratteri"],
    "register-confirm-password": ["Conferma la password", /^.{4,}$/, "Le password non coincidono"]
};

function caricamento() {
    var dataFormValue = document.body.getAttribute('data-form');
    if (dataFormValue === 'pizze') {
        dettagli_form.nome[0] = 'Es: Capricciosa';
    } else if (dataFormValue === 'cucina') {
        dettagli_form.nome[0] = 'Es: Anelli di cipolla';
    }

    for (var key in dettagli_form) {
        var input = document.getElementById(key);
        if (input) {
            messaggio(input, 0);
            input.addEventListener('blur', function () {
                validazioneCampo(this);
            });
        }
    }

    var confirmPwd = document.getElementById('register-confirm-password');
    if (confirmPwd) {
        confirmPwd.onblur = function() {
            validazionePassword();
        };
    }
    document.getElementById('registerForm').onsubmit = validazioneRegistrazione;
    document.getElementById('loginForm').onsubmit = validazioneLogin;
}


function validazioneCampo(input) {
    var regex = dettagli_form[input.id][1];
    var text = input.value;
    var p = input.parentNode;

    var nodesToRemove = p.querySelectorAll('.mex');
    nodesToRemove.forEach(function (node) {
        p.removeChild(node);
    });

    // Validazione
    if (text.search(regex) !== 0) {
        messaggio(input, 1);
        input.classList.add('error');
        return false;
    } else {
        input.classList.remove('error');
    }
    return true;
}

function validazioneForm() {
    var primoErrore = null;
    var valido = true;

    for (var key in dettagli_form) {
        var input = document.getElementById(key);
        if (!validazioneCampo(input)) {
            valido = false;


            if (!primoErrore) {
                primoErrore = input;
            }
        }
    }

    if (primoErrore) {
        primoErrore.scrollIntoView({ behavior: 'smooth', block: 'center' });
        primoErrore.focus();
    }
    return valido;
}

function messaggio(input, mode) {
    var node;
    var label = input.previousElementSibling; // Seleziona la label immediatamente precedente all'input

    if (!mode) {
        // Messaggio di aiuto
        node = document.createElement('span');
        node.className = 'default-text';
        node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
        if (label) {
            label.insertAdjacentElement('afterend', node);
        }
    } else {
        // Messaggio di errore
        node = document.createElement('strong');
        node.className = 'errorSuggestion mex';
        node.setAttribute("role", "alert");
        node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
        input.insertAdjacentElement('afterend', node);
    }
}

function validazionePassword() {
    var pwd = document.getElementById('register-password');
    var confirm = document.getElementById('register-confirm-password');

    var p = confirm.parentNode;

    // Rimuovi eventuali messaggi esistenti
    var existing = p.querySelector('.default-text, .errorSuggestion');
    if (existing) {
        p.removeChild(existing);
    }

    // Controllo della corrispondenza tra password
    if (pwd.value !== confirm.value) {
        // Mostra il messaggio di errore
        messaggio(confirm, 1);
        return false;
    }

    // Mostra un messaggio di successo
    var successMessage = document.createElement("span");
    successMessage.className = "successMessage";
    successMessage.appendChild(document.createTextNode("Le password corrispondono"));
    p.appendChild(successMessage);
    return true;
}

function validazioneRegistrazione() {
    var fields = ['register-name', 'register-surname', 'register-user', 'register-email', 'register-password', 'register-confirm-password'];

    for (var i = 0; i < fields.length; i++) {
        var input = document.getElementById(fields[i]);
        if (!validazioneCampo(input)) {
            return false;
        }
    }

    return true;
}


function validazioneLogin() {
    var fields = ['login-user', 'login-password'];

    for(var i = 0; i < fields.length; i++) {
        var input = document.getElementById(fields[i]);
        if(!validazioneCampo(input)) {
            return false;
        }
    }
    return true;
}

// Per area riservata
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const showRegister = document.getElementById('showRegister');
    const showLogin = document.getElementById('showLogin');

    // Aggiungi la classe js-hidden ai form -> segnale che JS funziona->altrimenti viene visualizzato form di login e registrazione
    loginForm.classList.add('js-hidden');
    registerForm.classList.add('js-hidden');

    loginForm.classList.add('active');
    loginForm.classList.remove('js-hidden');

    showRegister.addEventListener('click', function (e) {
        e.preventDefault();
        loginForm.classList.remove('active');
        loginForm.classList.add('js-hidden');
        registerForm.classList.add('active');
        registerForm.classList.remove('js-hidden');
    });

    showLogin.addEventListener('click', function (e) {
        e.preventDefault();
        registerForm.classList.remove('active');
        registerForm.classList.add('js-hidden');
        loginForm.classList.add('active');
        loginForm.classList.remove('js-hidden');
    });
});

document.addEventListener('DOMContentLoaded', caricamento);
