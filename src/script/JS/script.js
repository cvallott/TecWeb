








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
        node = document.createElement("span");
        node.className = "errorSuggestion";
        node.setAttribute("role", "alert");
        node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
    }

    if(node.className === "errorSuggestion")
        input.parentNode.insertBefore(node, input.nextSibling);

    if(node.className === "default-text")
        label.parentNode.insertBefore(node, label.nextSibling);


}






