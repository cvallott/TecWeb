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

var dettagli_form = {
    "login-user": ["Inserisci username", /^[A-Za-z0-9_]{3,20}$/, "Username deve contenere tra 3 e 20 caratteri alfanumerici"],
    "login-password": ["Inserisci password", /^.{4,}$/, "Password deve contenere almeno 4 caratteri"],
    "register-name": ["Es: Mario", /^[A-Za-z\u00C0-\u024F\s']{2,}$/, "Nome deve contenere almeno 2 caratteri, solo lettere"],
    "register-surname": ["Es: Rossi", /^[A-Za-z\u00C0-\u024F\s']{2,}$/, "Cognome deve contenere almeno 2 caratteri, solo lettere"],
    "register-email": ["Es: mario.rossi@email.com", /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/, "Formato email non valido"],
    "register-password": ["Inserisci password", /^.{4,}$/, "Password deve contenere almeno 4 caratteri"],
    "register-confirm-password": ["Conferma la password", /^.{4,}$/, "Le password non coincidono"]
};



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
    var fields = ['register-name', 'register-surname', 'register-email', 'register-password', 'register-confirm-password'];

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



function validazioneCampo(input) {
    var regexp = dettagli_form[input.id][1];
    var text = input.value;

    if(text.search(regexp) != 0) {
        messaggio(input, 1);
        input.focus();
        input.select();
        return false;
    }

    // Se la validazione passa, rimettiamo il messaggio di help
    messaggio(input, 0);
    return true;
}

function caricamento() {
    for (var key in dettagli_form) {
        var input = document.getElementById(key);
        if (input) {
            messaggio(input, 0);
            input.onblur = function() {
                validazioneCampo(this);
            };
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

function messaggio(input, mode) {
    var node;
    var p = input.parentNode;
    var label = p.querySelector('label');

    // Rimuovo eventuali messaggi esistenti
    var existing = p.querySelector('.default-text, .errorSuggestion, .successMessage');
    if(existing) {
        p.removeChild(existing);
    }

    if(!mode) {
        node = document.createElement("span");
        node.className = "default-text";
        node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
    } else {
        node = document.createElement("strong");
        node.className = "errorSuggestion";
        node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
    }

    if(node.className === "errorSuggestion")
        input.parentNode.insertBefore(node, input.nextSibling);

    if(node.className === "default-text")
        label.parentNode.insertBefore(node, label.nextSibling);


}

document.addEventListener('DOMContentLoaded', caricamento);




