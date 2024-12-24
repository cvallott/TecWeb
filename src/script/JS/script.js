document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const showRegister = document.getElementById('showRegister');
    const showLogin = document.getElementById('showLogin');

    showRegister.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
    });

    showLogin.addEventListener('click', function(e) {
        e.preventDefault();
        registerForm.classList.remove('active');
        loginForm.classList.add('active');
    });


});

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
