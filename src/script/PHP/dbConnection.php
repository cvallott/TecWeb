<?php
namespace DB;
use mysqli_sql_exception;

class DBConnection {
    private const HOST_DB = "db";
    private const DATABASE_NAME = "tecweb";
    private const USERNAME = "tecweb";
    private const PASSWORD = "tecweb";
    private const PORT = 3306;

    private $connection;

    public function openDBConnection(): bool {

        mysqli_report(MYSQLI_REPORT_ERROR);

        $this->connection = mysqli_connect(DBConnection::HOST_DB, DBConnection::USERNAME, DBConnection::PASSWORD, DBConnection::DATABASE_NAME, DBConnection::PORT);

        //debug
        //return mysqli_connect_error();

        //production
        if(mysqli_connect_errno()) {
            return false;
        }else{
            return true;
        }

    }

    public function closeConnection(): void {
        mysqli_close($this->connection);
    }
//    public function userLogin($username, $password) {
//        $query = "SELECT nome,cognome,ruolo FROM utente WHERE `username`='$username' AND `password`='$password'";
//        $result = mysqli_query($this->connection, $query);
//        if(mysqli_num_rows($result) == 1) {
//            $row = $result->fetch_array(MYSQLI_ASSOC);
//            return array($row['nome'], $row['cognome'], $row['ruolo']);
//        }
//        return false;
//    }

    public function getMenuPizze(): string{
        $queryCategorie = "SELECT * FROM categoria";
        $categorie = mysqli_query($this->connection, $queryCategorie);
        $stringaReturn = "";
        if(mysqli_num_rows($categorie) > 0) {
            while ($row = $categorie->fetch_array(MYSQLI_ASSOC)) {
                $stringaReturn .= "<section class='menu-prodpercat' id='".str_replace(' ','',$row['cat'])."'>";
                $stringaReturn .= "<h2>".$row['nomeEsteso']."</h2>";
                $stringaReturn .= "<p class='sez-intro'>".$row['descrizione']."</p>";
                $queryPizze = "SELECT * FROM pizza WHERE categoria='".$row['cat']."'";
                $pizze = mysqli_query($this->connection, $queryPizze);
                if(mysqli_num_rows($pizze) > 0) {
                    $stringaReturn .= "<div class='pizza-container'>";
                    while ($riga = $pizze->fetch_array(MYSQLI_ASSOC)) {
                        $stringaReturn .= "<div class='pizza' id='p-".$riga['id']."'>";
                        $stringaReturn .= "<div><img src='" . $riga['path'] . "' alt='" . $riga['nome'] . "'></div>";
                        $stringaReturn .= "<div class='pizza-testo'>";
                        $stringaReturn .= "<h3>" . $riga['nome'] . "</h3>";
                        $queryIngredienti = "SELECT pizza_ingrediente.ingrediente AS ingrediente, ingrediente.peso AS peso FROM pizza_ingrediente JOIN ingrediente ON pizza_ingrediente.ingrediente=ingrediente.nome WHERE pizza='".$riga['id']."' ORDER BY peso";
                        $ingredientiPizza = mysqli_query($this->connection, $queryIngredienti);
                        $stringaIngredienti = "";
                        if(mysqli_num_rows($ingredientiPizza) > 0) {
                            while ($ingrediente = $ingredientiPizza->fetch_array(MYSQLI_ASSOC)) {
                                $stringaIngredienti .= $ingrediente['ingrediente'].", ";
                            }
                        }
                        $stringaIngredienti = substr($stringaIngredienti, 0, -2);
                        $stringaReturn .= "<p>" . $stringaIngredienti . "</p>";
                        $stringaReturn .= "</div>";

                        $stringaReturn .= "<p class='pizza-prezzo'>€ " . number_format($riga['prezzo'], 2, ',', '.') . "</p>";

                        $stringaReturn .= "<div class='order-actions'>";

                        if(isset($_SESSION['carrello'][$riga['id']])){
                            $stringaReturn .= '<form method="POST" action="?scroll=p-'.$riga['id'].'" class="inlineComponents">
                        <div class="quantity-controls">
                        <input type="hidden" name="id" value="'.$riga['id'].'">
                        <button type="submit" class="decrease" name="azione" value="decrementa"><i class="fa fa-minus"></i></button>
                        
                        </form>';
                            $stringaReturn .= '<h4>';
                            $stringaReturn .= $_SESSION['carrello'][$riga['id']]['quantita'];
                            $stringaReturn .= '</h4>';
                            $stringaReturn .= '<form method="POST" action="?scroll=p-'.$riga['id'].'" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$riga['id'].'">
                        <button type="submit" class="increase" name="azione" value="incrementa"><i class="fa fa-plus"></i></button>
                        </div>
                     </form>';
                        }else{
                            $stringaReturn .= '<form method="POST" action="?scroll=p-'.$riga['id'].'">';
                            $stringaReturn .= '<input type="hidden" name="id" value="'.$riga['id'].'">';
                            $stringaReturn .= '<input type="hidden" name="prezzo" value="'.$riga['prezzo'].'">';
                            $stringaReturn .= '<input type="hidden" name="nome" value="'.$riga['nome'].'">';
                            $stringaReturn .= '<input type="hidden" name="quantita" value="1">';
                            $stringaReturn .= '<button type="submit" name="azione" value="aggiungi" class="home-button">Aggiungi al Carrello</button>';
                            $stringaReturn .= '</form>';
                        }
                        $stringaReturn .= '</div>';
                        $stringaReturn .= '</div>';
                    }
                    $stringaReturn .= '</div>';
                }
            }
            $stringaReturn .= '</section>';
        }
        return $stringaReturn;
    }

    public function getMenuCategorie(): string {
        $query = "SELECT cat, nomeEsteso FROM categoria";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $stringaReturn .= "<a href='#".str_replace(' ','',$row['cat'])."'>".$row['nomeEsteso']."</a>";
            }
        }
        return $stringaReturn;
    }

    public function getFuoriMenu(): string {
        $query = "SELECT nome,descrizione,path FROM pizza WHERE categoria='Fuori menù'";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<li>";
                $stringaReturn .= "<a href='menu-prenota.php'><img src='".$row['path']."' alt='TODO'>";
                $stringaReturn .= "<p><strong>".$row['nome']."</strong></p>";
                $stringaReturn .= "<p>".$row['descrizione']."</p>";
                $stringaReturn .= "</a></li>";
            }
        }
        return $stringaReturn;
    }

    public function getFuoriMenuPerCarrello(): string
    {
        $query = "SELECT * FROM pizza WHERE categoria='Fuori menù'";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

                $stringaReturn .= "<li>";
                $stringaReturn .= "<img src='.".$row['path']."'>";
                $stringaReturn .= "<p><strong>".$row['nome']."</strong></p>";
                $stringaReturn .= "<p>".$row['descrizione']."</p>";
                $stringaReturn .= "<form method='POST' action='' >";
                $stringaReturn .= "<input type='hidden' name='id' value='".$row['id']."'>";
                $stringaReturn .= "<input type='hidden' name='prezzo' value='".$row['prezzo']."'>";
                $stringaReturn .= "<input type='hidden' name='nome' value='".$row['nome']."'>";
                $stringaReturn .= "<input type='hidden' name='quantita' value='1'>";
                $stringaReturn .= "<button type='submit' name='azione' value='aggiungi' class='home-button'>Aggiungi</button>";
                $stringaReturn .= "</form></li>";
            }
        }
        return $stringaReturn;
    }

    public function queryIngredienti($filtro = null): string {

            $query = "SELECT * FROM ingrediente";


        return $query;
    }

    public function getIngredienti($query): string {
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        $conta=1;
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<div class=\"check\">";
                $stringaReturn .= "<input type=\"checkbox\" id=\"ingr".$conta."\" name=\"ingredienti[]\" value=\"".$row['nome']."\">";
                $stringaReturn .= "<label for=\"ingr".$conta."\">".$row['nome']."</label>";
                $stringaReturn .= "</div>";
                $conta++;
            }
        }
        return $stringaReturn;
    }

    public function getCategorie(): string {
        $query = "SELECT cat FROM categoria";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<option value='".$row['cat']."'>".$row['cat']."</option>";
            }
        }
        return $stringaReturn;
    }

    public function filtraProdotti($val = null, $filtro = 1) {
        if ($filtro > 0) {
            if ($_POST['tipo'] == '0') {
                $query = "SELECT * FROM ingrediente WHERE 1=1";
                if (!empty($_POST['nome'])) {
                    $query .= " AND nome = '". $_POST['nome'] ."'";
                }
                return $this->getIngredientiTabella($query);
            } elseif ($_POST['tipo'] == '1') {
                $query = "SELECT * FROM pizza WHERE 1=1";
                if (!empty($_POST['nome'])) {
                    $query .= " AND nome = '". $_POST['nome'] ."'";
                }
                return $this->getPizzeTabella($query);
            } elseif ($_POST['tipo'] == '2') {
                $query = "SELECT * FROM cucina WHERE 1=1";
                if (!empty($_POST['nome'])) {
                    $query .= " AND nome = '". $_POST['nome']."'";
                }
                return $this->getCucinaTabella($query);
            } else {
                if($val == 0) {
                    $query = "SELECT * FROM pizza WHERE 1=1";
                    if (!empty($_POST['nome'])) {
                        $query .= " AND nome = '". $_POST['nome']."'";
                    }
                    return $this->getPizzeTabella($query);
                }
                if($val == 1) {
                    $query = "SELECT * FROM cucina WHERE 1=1";
                    if (!empty($_POST['nome'])) {
                        $query .= " AND nome = '". $_POST['nome']."'";
                    }
                    return $this->getCucinaTabella($query);
                }
                if($val == 2) {
                    $query = "SELECT * FROM ingrediente WHERE 1=1";
                    if (!empty($_POST['nome'])) {
                        $query .= " AND nome = '". $_POST['nome']."'";
                    }
                    return $this->getIngredientiTabella($query);
                }
            }
        } else {
            if($val == 0) {
                $query = "SELECT * FROM pizza";
                return $this->getPizzeTabella($query);
            }
            if($val == 1) {
                $query = "SELECT * FROM cucina";
                return $this->getCucinaTabella($query);
            }
            if($val == 2) {
                $query = "SELECT * FROM ingrediente";
                return $this->getIngredientiTabella($query);
            }
        }
    }


    public function getIngredientiTabella($query): string {
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['nome']."</th>";
                $stringaReturn .= "<td data-title=\"Tipo\">Ingrediente singolo</td>";
                $stringaReturn .= "<td></td>";
                $stringaReturn .= "<td data-title=\"Modifica\"><a href=\"../../aggiungi-ingrediente.php\">Modifica</a></td>";
                $_SESSION['modificaAttiva'] = $row['nome'];
                $stringaReturn .= "<td data-title=\"Elimina\">";
                $stringaReturn .= "<form action=\"../../prodotti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"nome\" value=\"".$row['nome']."\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"deleteIngrediente\">";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina ingrediente\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }


    public function getCucinaTabella($query): string {
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['nome']." - codice: ".$row['id']."</th>";
                $stringaReturn .= "<td data-title=\"Tipo\">Piatto</td>";
                $stringaReturn .= "<td data-title=\"Prezzo\">&euro; ".$row['prezzo']."</td>";
                $stringaReturn .= "<td data-title=\"Modifica\"><a href=\"../../aggiungi-cucina.php\">Modifica</a></td>";
                $stringaReturn .= "<td data-title=\"Elimina\">";
                $stringaReturn .= "<form action=\"../../prodotti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"deleteCucina\">";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina piatto cucina\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }

    public function getPizzeTabella($query): string {
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['nome']." - codice: ".$row['id']."</th>";
                $stringaReturn .= "<td data-title=\"Tipo\">Pizza ".$row['categoria']."</td>";
                $stringaReturn .= "<td data-title=\"Prezzo\">&euro; ".$row['prezzo']."</td>";
                $stringaReturn .= "<td data-title=\"Modifica\"><a href=\"../../aggiungi-pizza.php\">Modifica</a></td>";
                $stringaReturn .= "<td data-title=\"Elimina\">";
                $stringaReturn .= "<form action=\"../../prodotti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"deletePizza\">";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina pizza\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }

    public function queryUtenti($filtro = null): string {
        $query = "";
        if($filtro != null){
            if(isset($_POST['ruolo']) && $_POST['ruolo'] == ''){
                $query .= "SELECT nome, cognome, username, email, ruolo FROM utente WHERE (ruolo=0 OR ruolo=1)";
            }else{
                $query .= "SELECT nome, cognome, username, email, ruolo FROM utente WHERE ruolo='".$_POST['ruolo']."'";
            }
            if(isset($_POST['nome_utente']) && $_POST['nome_utente'] != ''){
                $query .= " AND nome LIKE '".$_POST['nome_utente']."'";
            }
            if(isset($_POST['username_utente']) && $_POST['username_utente'] != ''){
                $query .= " AND username LIKE '".$_POST['username_utente']."'";
            }
        }else if ($filtro == null){
            $query .= "SELECT nome, cognome, username, email, ruolo FROM utente";
        }
        return $query;
    }

    public function getUtenti($query): string {
        /*$query = "SELECT nome, cognome, username, email, ruolo FROM utente";*/
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['nome']." ".$row['cognome']."</th>";
                $stringaReturn .= "<td data-title=\"Username\">".$row['username']."</td>";
                $stringaReturn .= "<td data-title=\"Email\">".$row['email']."</td>";
                if($row['ruolo']==0){
                    $stringaReturn .= "<td data-title=\"Ruolo\">Cliente</td>";
                }else{
                    $stringaReturn .= "<td data-title=\"Ruolo\">Amministratore</td>";
                }
                $stringaReturn .= "<td data-title=\"Modifica ruolo\">";
                $stringaReturn .= "<form action=\"../../gestisci-utenti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"update\">";
                $stringaReturn .= "<select name=\"ruolo\" class=\"select\">";
                $stringaReturn .= "<option value=\"0\">Cliente</option>";
                $stringaReturn .= "<option value=\"1\">Amministratore</option>";
                $stringaReturn .= "</select>";
                $stringaReturn .= "<input type=\"hidden\" name=\"email\" value=\"".$row['email']."\">";
                $stringaReturn .= "<input type=\"submit\" value=\"Conferma\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "<td data-title=\"Elimina Utente\">";
                $stringaReturn .= "<form action=\"../../gestisci-utenti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"email\" value=\"".$row['email']."\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"delete\">";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina utente\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }

    public function queryOrdini($filtro = 0): string {
        $query = '';
        if($filtro != null){
            if(isset($_POST['stato']) && $_POST['stato'] == ''){
                $query .= "SELECT o.id, u.nome AS cliente, o.data, o.ora, o.stato FROM ordine AS o, utente AS u WHERE u.email = o.utente AND (stato=0 OR stato=1 OR stato=-1)";
            }else{
                $query .= "SELECT o.id, u.nome AS cliente, o.data, o.ora, o.stato FROM ordine AS o, utente AS u WHERE u.email = o.utente AND stato='".$_POST['stato']."'";
            }
            if(isset($_POST['data']) && $_POST['data'] != ''){
                $query .= " AND data LIKE '".$_POST['data']."'";
            }
            if(isset($_POST['cliente']) && $_POST['cliente'] != ''){
                $query .= " AND u.nome LIKE '".$_POST['cliente']."'";
            }
        }else if ($filtro == 0){
            $query .= "SELECT o.id, u.nome AS cliente, o.data, o.ora, o.stato FROM ordine AS o JOIN utente AS u ON u.email = o.utente";
        }
        return $query;
    }

    public function getTotalePrezzoOrdine($idOrdine){
        $query = "SELECT PO.quantita, COALESCE(P.prezzo, C.prezzo) AS prezzo FROM prodotti_ordine AS PO LEFT JOIN pizza AS P ON PO.pizza = P.id LEFT JOIN cucina AS C ON PO.cucina = C.id WHERE PO.ordine = '".$idOrdine."'";
        $result = mysqli_query($this->connection, $query);
        $prezzo = 0;
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $prezzo += ($row['quantita']*$row['prezzo']);
            }
            return $prezzo;
        }else{
            /*qualche errore*/
        }
    }

    public function getTotaleProdottiOrdine($idOrdine){
        $query = "SELECT PO.quantita FROM prodotti_ordine AS PO WHERE PO.ordine='".$idOrdine."'";
        $result = mysqli_query($this->connection, $query);
        $conta = 0;
        if(mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $conta += $row['quantita'];
            }
            return $conta;
        }else{
            /*qualche errore*/
        }
    }

    public function getOrdini($query): string {
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['id']."</th>";
                $stringaReturn .= "<td data-title=\"Cliente\">".$row['cliente']."</td>";
                $stringaReturn .= "<td data-title=\"Data e orario\">".$row['data']." - ".$row['ora']."</td>";
                $tot = $this->getTotaleProdottiOrdine($row['id']);
                $prezzo = $this->getTotalePrezzoOrdine($row['id']);
                $stringaReturn .= "<td data-title=\"Totale\">&euro; ".$prezzo." - ".$tot." prodotti</td>";
                if($row['stato']==0){
                    $stringaReturn .= "<td data-title=\"Stato\">In corso</td>";
                }else if($row['stato']==1){
                    $stringaReturn .= "<td data-title=\"Stato\">Consegnato</td>";
                }else{
                    $stringaReturn .= "<td data-title=\"Stato\">Annullato</td>";
                }
                $stringaReturn .= "<td data-title=\"Modifica stato\">";
                $stringaReturn .= "<form action=\"../../visualizza-ordini.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"update\">";
                $stringaReturn .= "<select name=\"stato\" class=\"select\">";
                $stringaReturn .= "<option value=\"1\">Consegnato</option>";
                $stringaReturn .= "<option value=\"0\">In corso</option>";
                $stringaReturn .= "<option value=\"-1\">Annullato</option>";
                $stringaReturn .= "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">"; /*POSSO METTERLI???*/
                $stringaReturn .= "</select>";
                $stringaReturn .= "<input type=\"submit\" value=\"Conferma\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "<td data-title=\"Dettagli\">";
                $stringaReturn .= "<img src=\"../../../assets/icons/see-more.png\" alt=\"\" height=\"15\">";
                $stringaReturn .= "<a href=\"../../dettagli-ordine.php?idOrdine=".$row['id']."\">Visualizza dettagli</a>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }

    public function getDettagliOrdine($idOrdine): string {
        $query = "SELECT COALESCE(P.nome, C.nome) AS prodotto, PO.quantita AS quantita, (PO.quantita * COALESCE(P.prezzo, C.prezzo)) AS prezzo FROM prodotti_ordine AS PO LEFT JOIN pizza AS P ON PO.pizza = P.id LEFT JOIN cucina AS C ON PO.cucina = C.id WHERE PO.ordine = '".$idOrdine."'";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['prodotto']."</th>";
                $stringaReturn .= "<td data-title= \"Quantità\">".$row['quantita']."</td>";
                $stringaReturn .= "<td data-title=\"Prezzo\">&euro; ".$row['prezzo']."</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }

    public function getOrdiniUtente($email): string {
        $query = "SELECT o.id, o.data, o.ora, o.stato FROM ordine AS o WHERE '".$email."' = o.utente";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC) ){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['id']."</th>";
                $stringaReturn .= "<td data-title=\"Data e ora\">".$row['data']." - ".$row['ora']."</td>";
                if($row['stato']==0){
                    $stringaReturn .= "<td data-title=\"Stato\">In corso</td>";
                }else if($row['stato']==1){
                    $stringaReturn .= "<td data-title=\"Stato\">Consegnato</td>";
                }else{
                    $stringaReturn .= "<td data-title=\"Stato\">Annullato</td>";
                }
                $tot = $this->getTotaleProdottiOrdine($row['id']);
                $prezzo = $this->getTotalePrezzoOrdine($row['id']);
                $stringaReturn .= "<td data-title=\"Totale\">&euro; ".$prezzo." - ".$tot." prodotti</td>";
                $stringaReturn .= "<td data-title=\"Dettagli\">";
                $stringaReturn .= "<img src=\"../../../assets/icons/see-more.png\" alt=\"\" height=\"15\">";
                $stringaReturn .= "<a href=\"../../dettagli-ordine.php?idOrdine=".$row['id']."\">Visualizza dettagli</a>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }

    public function isVeget(array $ingredienti) {
        $veget = 0;
        foreach($ingredienti as $ingrediente) {
            $query = "SELECT veget FROM ingrediente WHERE nome='".$ingrediente."'";
            $result = mysqli_query($this->connection, $query);
            if(mysqli_num_rows($result) > 0) {
                $row = $result->fetch_assoc();
                if ($row['veget'] == 1) {
                    $veget = 1;
                }
            }
        }
        return $veget;
    }

    public function insertIngrediente($nome, $veget) {

        $queryInsert = "INSERT INTO ingrediente(nome, veget) " .
            "VALUES (\"$nome\", \"$veget\")";

        $queryResult = mysqli_query($this->connection, $queryInsert) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }
        else {
            return false;
        }
    }

    public function insertPizza($nome, $prezzo, $veget, $categoria, $descrizione, $path): bool {

        $queryInsert = "INSERT INTO pizza(nome, prezzo, veget, categoria, descrizione, path) " .
            "VALUES (\"$nome\", \"$prezzo\", \"$veget\", \"$categoria\", \"$descrizione\", \"$path\")";

        $queryResult = mysqli_query($this->connection, $queryInsert) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }
        else {
            return false;
        }
    }

    public function insertProdottoIngrediente($nome, $ingredienti, $table) {
        // Sanitizza il nome della pizza
        /*$nome = mysqli_real_escape_string($this->connection, $nome);*/
        $query = "SELECT id FROM ". $table . " WHERE nome='$nome'";
        $result = mysqli_query($this->connection, $query);

        if (!$result) {
            die("Errore nella query per trovare l'ID del prodotto: " . mysqli_error($this->connection));
        }

        if (mysqli_num_rows($result) === 0) {
            die("Errore: Nessun prodotto trovato con il nome '$nome'.");
        }

        $row = mysqli_fetch_assoc($result);
        $prodottoId = $row['id'];

        // Itera sugli ingredienti e inseriscili
        foreach ($ingredienti as $ingrediente) {
            $ingrediente = mysqli_real_escape_string($this->connection, $ingrediente);
            $queryInsert = "INSERT INTO ". $table . "_ingrediente (" . $table . ", ingrediente) VALUES ('$prodottoId', '$ingrediente')";

            if (!mysqli_query($this->connection, $queryInsert)) {
                die("Errore nell'inserimento dell'ingrediente '$ingrediente': " . mysqli_error($this->connection));
            }
        }
        return true;
    }

    public function insertCucina($nome, $prezzo, $veget, $path) {

        $queryInsert = "INSERT INTO cucina(nome, prezzo, veget, path) " .
            "VALUES (\"$nome\", \"$prezzo\", \"$veget\", \"$path\")";

        $queryResult = mysqli_query($this->connection, $queryInsert) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }
        else {
            return false;
        }
    }

    public function updateUtente($ruolo) {
        $queryUpdate = "UPDATE utente SET ruolo='".$ruolo."' WHERE email='".$_POST['email']."'";

        $query = "SELECT ruolo FROM utente WHERE email='".$_POST['email']."'";
        $result = mysqli_query($this->connection, $query);
        if(mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();
            if ($ruolo != $row['ruolo']) {
                mysqli_query($this->connection, $queryUpdate) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
                return true;
            }
            return false;
        }
        return false;
    }

    public function updateOrdine($stato) {
        $queryUpdate = "UPDATE ordine SET stato='".$stato."' WHERE id='".$_POST['id']."'";

        $query = "SELECT stato FROM ordine WHERE id='".$_POST['id']."'";
        $result = mysqli_query($this->connection, $query);
        if(mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();
            if ($stato != $row['stato']) {
                mysqli_query($this->connection, $queryUpdate) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
                return true;
            }
            return false;
        }
        return false;
    }

    public function queryDeleteUtente(): string{
        return "DELETE FROM utente WHERE email='".$_POST['email']."'";
    }

    public function queryDeletePizza(): string{
        return "DELETE FROM pizza WHERE id='".$_POST['id']."'";
    }

    public function queryDeleteCucina(): string{
        return "DELETE FROM cucina WHERE id='".$_POST['id']."'";
    }

    public function queryDeleteIngrediente(): string{
        $query = "SELECT pizza AS id, 'pizza' AS tipo FROM pizza_ingrediente WHERE ingrediente = '".$_POST['nome']."' UNION SELECT cucina AS id, 'cucina' AS tipo FROM cucina_ingrediente WHERE ingrediente = '".$_POST['nome']."'";
        $result = mysqli_query($this->connection, $query) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        echo $query;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $queryDelete = "DELETE FROM ".$row['tipo']." WHERE id=".$row['id'];
                $this->delete($queryDelete);
            }
        }

        return "DELETE FROM ingrediente WHERE nome='".$_POST['nome']."'";
    }

    public function delete($queryDelete) {
        $queryResult = mysqli_query($this->connection, $queryDelete) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }
        else {
            return false;
        }
    }

    public function checkUserExists($username) {
        $query = "SELECT username FROM utente WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function checkEmailExists($email) {
        $query = "SELECT email FROM utente WHERE email = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function checkIngrediente($nome) {
        $query = "SELECT nome FROM ingrediente WHERE nome = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('s', $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function registerUser($name, $surname, $username, $email, $hashedPassword) {
        $ruolo = 0; // ruolo default
        $query = "INSERT INTO utente (nome, cognome, username, email, password, ruolo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('sssssi', $name, $surname, $username, $email, $hashedPassword, $ruolo);
        return $stmt->execute();
    }

    public function userLogin($username, $password) {
        $query = "SELECT nome, cognome, ruolo, password, email FROM utente WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return array($user['nome'], $user['cognome'], $user['ruolo'], $user['email']);
            }
        }
        return false;
    }

    public function getFasceOrarie($daOrdinare){
        $query = "SELECT disponiblitaorarie.fascia AS fascia, pizzePerFascia.pizze AS pizze FROM disponiblitaorarie LEFT JOIN pizzePerFascia ON disponiblitaorarie.fascia = pizzePerFascia.fascia WHERE disponiblitaorarie.fascia NOT IN (SELECT orario FROM checkOrari);";
        $risultato = mysqli_query($this->connection, $query);
        $selectReturn ="";
        $primadisponibilita = "";
        if(mysqli_num_rows($risultato) > 0){
            while($row = mysqli_fetch_assoc($risultato)){
                if($primadisponibilita==""){
                    $primadisponibilita = $row["fascia"];
                }
                if($daOrdinare + $row["pizze"] < 20) {
                    $selectReturn .= "<option value='" . $row['fascia'] . "'>" . $row['fascia'] . "</option>";
                }
            }
        }
        return array($primadisponibilita, $selectReturn);
    }

    public function insertOrder($orario, $nota = ""){
        if($nota != ""){
            $query = "INSERT INTO ordine (utente, ora, nota) VALUES (?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('sss',$_SESSION['email'], $orario, $nota);
            $stmt->execute();
            return $stmt->insert_id;
        }else{
            $query = "INSERT INTO ordine (utente, ora) VALUES (?, ?)";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('ss',$_SESSION['email'], $orario);
            $stmt->execute();
            return $stmt->insert_id;
        }
    }

    public function itemToOrdine($idOrd){

        foreach ($_SESSION['carrello'] as $id => $item) {
            $query = "INSERT INTO prodotti_ordine (ordine, pizza, quantita) VALUES ($idOrd,$id, ".$item['quantita'].")";
            try {
                mysqli_query($this->connection, $query);
            }catch (mysqli_sql_exception $e) {
                return false;
            }


        }
        return true;

    }
//    public function userLogin($username, $password) {
//        $query = "SELECT nome, cognome, ruolo, password FROM utente WHERE username = ?";
//        $stmt = $this->connection->prepare($query);
//        $stmt->bind_param('s', $username);
//        $stmt->execute();
//        $result = $stmt->get_result();
//
//        // Debug
//        error_log("Login attempt for user: " . $username);
//        error_log("Query result rows: " . $result->num_rows);
//
//        if ($result->num_rows === 1) {
//            $user = $result->fetch_assoc();
//            if (password_verify($password, $user['password'])) {
//                return array($user['nome'], $user['cognome'], $user['ruolo']);
//            }
//            error_log("Password verification failed");
//        }
//        return false;
//    }

}



