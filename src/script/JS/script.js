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
    "login-password": ["Inserisci password", /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/, "Password deve contenere almeno 8 caratteri, una lettera e un numero"],
    "register-name": ["Es: Mario", /^[A-Za-z\u00C0-\u024F\s']{2,}$/, "Nome deve contenere almeno 2 caratteri, solo lettere"],
    "register-surname": ["Es: Rossi", /^[A-Za-z\u00C0-\u024F\s']{2,}$/, "Cognome deve contenere almeno 2 caratteri, solo lettere"],
    "register-email": ["Es: mario.rossi@email.com", /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/, "Formato email non valido"],
    "register-password": ["Inserisci password", /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/, "Password deve contenere almeno 8 caratteri, una lettera e un numero"],
    "register-confirm-password": ["Conferma la password", /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/, "Le password non coincidono"]
};

//1
// function caricamento() {
//     for(var key in dettagli_form) {
//         var input = document.getElementById(key);
//         if(input) {
//             messaggio(input, 0);
//             input.onblur = function() { validazioneCampo(this); };
//         }
//     }
//     var confirmPwd = document.getElementById('register-confirm-password');
//     if(confirmPwd) {
//         confirmPwd.onblur = function() { validazionePassword(); };
//     }
//     document.getElementById('registerForm').onsubmit = validazioneRegistrazione;
//     document.getElementById('loginForm').onsubmit = validazioneLogin;
// }

function caricamento() {
    for (var key in dettagli_form) {
        var input = document.getElementById(key);
        if (input) {
            messaggio(input, 0); // Messaggio di aiuto
            input.onblur = function() {
                validazioneCampo(this);
            };
        }
    }

    var pwd = document.getElementById('register-password');
    var confirmPwd = document.getElementById('register-confirm-password');

    // Validazione password in tempo reale
    if (pwd && confirmPwd) {
        pwd.oninput = function() {
            validazionePassword();
        };
        confirmPwd.oninput = function() {
            validazionePassword();
        };
    }

    // Associazione onsubmit ai form
    document.getElementById('registerForm').onsubmit = validazioneRegistrazione;
    document.getElementById('loginForm').onsubmit = validazioneLogin;
}



// function validazioneCampo(input) {
//     var regexp = dettagli_form[input.id][1];
//     var text = input.value;
//
//     var p = input.parentNode;
//     if(p.children.length > 2) {
//         p.removeChild(p.children[2]);
//     }
//
//     if(text.search(regexp) != 0) {
//         messaggio(input, 1);
//         input.focus();
//         input.select();
//         return false;
//     }
//
//     return true;
// }

//1
// function validazionePassword() {
//     var pwd = document.getElementById('register-password');
//     var confirm = document.getElementById('register-confirm-password');
//
//     if(pwd.value !== confirm.value) {
//         var p = confirm.parentNode;
//         if(p.children.length > 2) {
//             p.removeChild(p.children[2]);
//         }
//         messaggio(confirm, 1);
//         return false;
//     }
//     return true;
// }

// function validazioneRegistrazione() {
//     var fields = ['register-name', 'register-surname', 'register-email',
//         'register-password', 'register-confirm-password'];
//
//     for(var i = 0; i < fields.length; i++) {
//         var input = document.getElementById(fields[i]);
//         if(!validazioneCampo(input)) {
//             return false;
//         }
//     }
//
//     return validazionePassword();
// }

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

    return true; // La validazione della password avviene al blur
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

// function messaggio(input, mode) {
//     var node;
//     var p = input.parentNode;
//
//     if(!mode) {
//         node = document.createElement("span");
//         node.className = "default-text";
//         node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
//     } else {
//         node = document.createElement("strong");
//         node.className = "errorSuggestion";
//         node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
//     }
//     p.appendChild(node);
// }

function messaggio(input, mode) {
    var node;
    var p = input.parentNode;
    var label = p.querySelector('label');

    // Rimuovo eventuali messaggi esistenti
    var existing = p.querySelector('.default-text, .errorSuggestion');
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

    label.parentNode.insertBefore(node, label.nextSibling);
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
   // messaggio(input, 0);
    return true;
}

document.addEventListener('DOMContentLoaded', caricamento);




// //NASCONDERE LOGIN FORM O REGISTER FORM
// document.addEventListener('DOMContentLoaded', function() {
//     const loginForm = document.getElementById('loginForm');
//     const registerForm = document.getElementById('registerForm');
//     const showRegister = document.getElementById('showRegister');
//     const showLogin = document.getElementById('showLogin');
//
//     showRegister.addEventListener('click', function (e) {
//         e.preventDefault();
//         loginForm.classList.remove('active');
//         registerForm.classList.add('active');
//     });
//
//     showLogin.addEventListener('click', function (e) {
//         e.preventDefault();
//         registerForm.classList.remove('active');
//         loginForm.classList.add('active');
//     });
// }

    //     /*
    //     chiave: nome dell'input che cerco
    //     [0]: Prima indicazione per la compilazione dell'input ->AIUTO
    //     [1]: espressione regolare da controllare
    //     [2]: Hint nel caso in cui input fornito sia sbagliato
    //              */
    //     var dettagli_form_login = {
    //         "login-user":["Nome utente",/\w+/,"Il nome utente può contenere solo lettere, numeri o underscore, senza spazi."],
    //         "login-password":["Password",/^(?=.*[A-Za-z])(?=.*\d).{8,}$/,"La password deve essere di almeno 8 caratteri e deve contenere almeno una lettera ed un numero"]
    //     };
    //
    //     var dettagli_form_register = {
    //         "register-name":["Nome",/^[A-Za-z][A-Za-zÀ-ÿ' -]{1,}$/,"Formato nome non corretto. Attenzione non può iniziare con spazio,apostrofo o lettere accentate."],
    //         "register-surname":["Cognome",/^[A-Za-z][A-Za-zÀ-ÿ' -]{1,}$/,"Formato cognome non corretto. Attenzione non può iniziare con spazio,apostrofo o lettere accentate."],
    //         "register-user":["Nome utente",/\w+/,"Il nome utente può contenere solo lettere, numeri o underscore, senza spazi."],
    //         "register-password":["Password",/^(?=.*[A-Za-z])(?=.*\d).{8,}$/,"La password deve essere di almeno 8 caratteri e deve contenere almeno una lettera ed un numero"],
    //         "register-confirm-password":["Password",/^(?=.*[A-Za-z])(?=.*\d).{8,}$/,"La password deve essere di almeno 8 caratteri e deve contenere almeno una lettera ed un numero"]
    //     };
    //
    //
    // function caricamento() {
    //     for(var key in dettagli_form_login){
    //         var input=document.getElementById(key);
    //         messaggio(input,0);//scrivo tutti i messaggi
    //         //eventi onChange MEGLIO-> onblur
    //         input.onblur=function(){validazioneCampo(this);};
    //     }
    // }
    //
    //
    // function validazioneCampo(input) {
    //     var regexp=dettagli_form[input.id][1];
    //     var text= input.value;
    //
    //     //tolgo suggerimento o errore precedente
    //     var p=input.parentNode;
    //     p.removeChild(p.children[2]);
    //     //-1 se matching non viene trovato
    //     //0 se metching trovato dall'inizio
    //     //>=0 ha trovato matching in qualsiasi punto
    //     //!= 0 non l'ha trovato nella prima posizione ->se lo scrivo così non serve ^ per indicare l'inizio
    //     if(text.search(regexp) !=0) {
    //         messaggio(input,1);
    //         input.focus();
    //         input.select();//se input lungo, solitamente meglio select perchè le persone che utilizzano la digitazione vocale ripetono la parola
    //         return false;
    //     }
    //     return true;
    //
    // }
    //
    // function validazioneForm_login() {
    //     for(var key in dettagli_form_login){
    //         var input=document.getElementById(key);
    //         if (!validazioneCampo(input)) {
    //             return false;
    //         }
    //     }
    //     return true;
    // }
    //
    // function validazioneForm_register() {
    //     for(var key in dettagli_form_register){
    //         var input=document.getElementById(key);
    //         if (!validazioneCampo(input)) {
    //             return false;
    //         }
    //     }
    //     return true;
    // }
    // //importante inserire anche attributo aria alert
    //
    // function messaggio(input, mode) {
    //     /* mode = 0, modalità input
    //        mode = 1, modalità errore */
    //     var node;// tag con il messaggio
    //     var p=input.parentNode;
    //
    //     if(!mode) {
    //         //creo messaggio di aiuto
    //         node=document.createElement("span");
    //         node.className="default-text";
    //         node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
    //
    //     }
    //     else{
    //         //creo messaggio di errore
    //         node=document.createElement("strong");
    //         node.className="errorSuggestion";
    //         node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
    //
    //     }
    //     p.appendChild(node);
    //
    //
    //
    // }
    //
