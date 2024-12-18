<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Chi siamo-Non solo pizza</title>
    <meta name="description" content=""><!--TODO-->
    <meta name="keywords" content="">
    <meta name="author" content="Ferraro Sara,Pozzobon Francesco,Santi Andrea,Vallotto Caterina">
    <link rel="stylesheet" href="style/style.css" media="screen">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body id="chi-siamoPage">
    <header>
        <img src="../assets/logo/logo.jpg" alt="Logo Non solo pizza">
        <nav id="menu">
            <ul>
                <li><a href="index.html" lang="en">Home</a></li>
                <li id=currentLink>Chi siamo</li>
                <li><a href="menu.html">Menù-Prenota</a></li>
                <div id="dropdown">
                    <li><button id="droplink">Area riservata</button></li>
                    <div id="dropdown-content">
                        <li><a href="login.html"><span lang=en>Login</span></a></li>
                        <li><a href="registrati.html">Registrati</a></li>
                    </div>
                </div>
            </ul>
        </nav>
    </header>

    <nav id="breadcrumb">
        <p>Sei in: <a lang="en" href="index.html">Home</a> / Chi siamo</p>
    </nav>

    <main>
        <section id="chi-siamo-intro">
                <h1>Chi siamo</h1>
        </section>

        <div id="chi-siamo-contenitore">
            <section  class="chi-siamo-items">
                <div class="chi-siamo-titoli">
                    <img alt="icona storia" src="../assets/altre/chi-siamo/storia.png" >
                    <h2><a href=#chi-siamo-storia>Storia</a></h2>
                </div>
                <p>Non Solo Pizza: la passione di Simone, Alessio e Marisa per la tradizione, servita con amore nel tuo quartiere.</p>
            </section>
            <section  class="chi-siamo-items">
                <div class="chi-siamo-titoli">
                    <img alt="icona storia" src="../assets/altre/chi-siamo/filosofia.png" >
                    <h2><a href=#chi-siamo-filosofia>Filosofia</a></h2>
                </div>
                <p>Dove passione, famiglia e tradizione si uniscono per offrirti un’esperienza autentica e irresistibile.</p>
               
            </section>
            
            <section  class="chi-siamo-items">
                <div class="chi-siamo-titoli">
                    <img alt="icona storia" src="../assets/altre/chi-siamo/qualita.png" >
                    <h2><a href=#chi-siamo-qualita>Qualità</a></h2>
                </div>
                <p>I nostri chef scelgono personalemnte esclusivimente prodotti di alta qualità.</p>
            </section>
        </div>
        <!--TODO : 1.DA INSERIRE TITOLO 2.SE L'UTENTE CLICCA IN STORIA SOPRA LO PORTA A QUESTA SEZIONE 3.RICORDARSI TUTTI GLI ATTRIBUTI STRONG EM 4. SINTETIZZA IL TESTO-->
        <section class="chi-siamo-boxes" id="chi-siamo-storia">
            <div class="chi-siamo-testo">
            <h3>STORIA</h3>
            <p>Benvenuti a Non Solo Pizza</p>
              <p>  Nata dalla passione di Simone e Alessio, "Non Solo Pizza" è più di una pizzeria: è il risultato di un sogno condiviso e di un amore per la cucina autentica. Da oltre due anni e mezzo, portiamo nel quartiere pizze dal gusto unico, preparate con ingredienti freschi, farine selezionate e una lunga lievitazione per garantire leggerezza e digeribilità.
                
                Al fianco dei fratelli c’è Marisa, la loro madre, custode dei segreti di famiglia e creatrice di sughi e ricette che aggiungono un tocco speciale al nostro menù. Insieme, ci impegniamo a offrire un’esperienza familiare e accogliente, dove ogni pizza racconta una storia di tradizione e innovazione.
                
                Scoprite i sapori autentici di Non Solo Pizza e lasciatevi conquistare dall’amore che mettiamo in ogni fetta.</p>
            </div>
                <img src="../assets/altre/chi-siamo/chi-siamo-storia.jpg" alt="Pizzaiolo">
        </section>

        <section class="chi-siamo-boxes" id="chi-siamo-filosofia">
            <div class="chi-siamo-testo">
                <h3>FILOSOFIA</h3>
                <img src="../assets/altre/chi-siamo/chi-siamo-filosofia.jpg" alt="Pizzaiolo">
            </div>
            <p>Più di una pizzeria: siamo una famiglia che crede nella tradizione, nella qualità e nel piacere del buon cibo. Le nostre pizze, preparate con passione, e le specialità fritte portano in tavola sapori autentici e irresistibili.

                La qualità è il cuore di tutto ciò che facciamo. Usiamo ingredienti freschi, locali e a chilometro zero, valorizzando il legame con il territorio per offrirti un prodotto genuino e sostenibile. Ogni impasto e ogni ricetta seguono metodi tramandati in famiglia, per garantire gusto e autenticità in ogni boccone.
                
                Per noi, cibo significa condivisione e calore. Che tu scelga una pizza fragrante o una frittura croccante, ti accoglieremo con la passione di chi ama far sentire ogni cliente parte della propria storia.
                
                Non Solo Pizza: tradizione, famiglia e territorio in ogni assaggio.</p>
    </section>
        <section class="chi-siamo-boxes" id="chi-siamo-qualita">
            <div class="chi-siamo-testo">
                <h3>QUALITÀ</h3>
                <p>Da Non Solo Pizza, la qualità è il nostro ingrediente principale. Ogni giorno selezioniamo con cura i migliori prodotti per garantire ai nostri clienti un’esperienza autentica e irresistibile.

                    Utilizziamo solo farine di alta qualità per un impasto leggero e fragrante, lavorato con passione e rispettando i tempi di lievitazione naturale. Il nostro pomodoro, maturato al sole, e la mozzarella fresca sono scelti per esaltare i sapori tradizionali.
                    Le verdure sono sempre fresche e di stagione, mentre gli oli e i condimenti sono rigorosamente extravergini, per garantire un sapore genuino in ogni morso.</p>
             </div>    
             <img src="../assets/altre/chi-siamo/chi-siamo-qualita.jpg" alt="Qualità ingredienti">

        </section>
    </main>
    <footer>
        <img class="imgValidCode" src="./images/valid-xhtml10.png" alt="HTML valido">
        <p>Indirizzo: Via Scandolara 81, Scandolara TV</p>
        <p>Orari</p>
        <p>Contatti: 0422345324</p>
    </footer>
</body>
</html>