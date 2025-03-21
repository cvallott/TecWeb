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

        try{
            $this->connection = mysqli_connect(DBConnection::HOST_DB, DBConnection::USERNAME, DBConnection::PASSWORD, DBConnection::DATABASE_NAME, DBConnection::PORT);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        }catch(mysqli_sql_exception $e){
            header('location: errore.php');
        }
        if(mysqli_connect_errno()) {
            return false;
        }else{
            return true;
        }

    }

    public function closeConnection(): void {
        mysqli_close($this->connection);
    }

    public function getMenuPizze($nome = ''): string{
        $visited = false;
        try{
            $queryCategorie = "SELECT * FROM categoria";
            $categorie = mysqli_query($this->connection, $queryCategorie);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }

        $stringaReturn = "";
        if(mysqli_num_rows($categorie) > 0) {
            while ($row = $categorie->fetch_array(MYSQLI_ASSOC)) {
                if(!empty($nome)){
                    try{
                        $queryPizze = "SELECT * FROM pizza WHERE categoria=? AND nome LIKE ?";
                        $stmt = $this->connection->prepare($queryPizze);
                        $nome = '%'.$nome.'%';
                        $stmt->bind_param('ss', $row['cat'],$nome);
                        $stmt->execute();
                        $pizze = $stmt->get_result();
                    }catch(mysqli_sql_exception $e){
                        header("location: errore.php");
                    }

                } else {
                    try{
                        $queryPizze = "SELECT * FROM pizza WHERE categoria='".$row['cat']."'";
                        $pizze = mysqli_query($this->connection, $queryPizze);
                    }catch(mysqli_sql_exception $e){
                        header("location: errore.php");
                    }
                }
                if(mysqli_num_rows($pizze) > 0) {
                    $visited = true;
                    $stringaReturn .= "<section class=\"menu-prodpercat\" id=\"".str_replace(' ','',$row['cat'])."\">";
                    $stringaReturn .= "<h2>".$row['nomeEsteso']."</h2>";
                    $stringaReturn .= "<p class=\"sez-intro\">".$row['descrizione']."</p>";
                    $stringaReturn .= "<div class=\"pizza-container\">";
                    while ($riga = $pizze->fetch_array(MYSQLI_ASSOC)) {
                        $stringaReturn .= "<div class=\"pizza\" id=\"p-".$riga['id']."\">";
                        $stringaReturn .= "<div><img src=\"" . $riga['path'] . "\" alt=\"\" loading=\"lazy\"></div>";
                        $stringaReturn .= "<div class=\"pizza-testo\">";
                        $stringaReturn .= "<h3>" . $riga['nome'];
                        if($riga['veget'] == "1"){
                            $stringaReturn .="<img src=\"assets/icons/foglia.png\" alt=\"Vegetariana\" />";
                        }
                        $stringaReturn .= "</h3>";
                        try{
                            $queryIngredienti = "SELECT pizza_ingrediente.ingrediente AS ingrediente, ingrediente.peso AS peso FROM pizza_ingrediente JOIN ingrediente ON pizza_ingrediente.ingrediente=ingrediente.nome WHERE pizza='".$riga['id']."' ORDER BY peso";
                            $ingredientiPizza = mysqli_query($this->connection, $queryIngredienti);
                        }catch(mysqli_sql_exception $e){
                            header("location: errore.php");
                        }
                        $stringaIngredienti = "";
                        if(mysqli_num_rows($ingredientiPizza) > 0) {
                            while ($ingrediente = $ingredientiPizza->fetch_array(MYSQLI_ASSOC)) {
                                $stringaIngredienti .= $ingrediente['ingrediente'].", ";
                            }
                        }
                        $stringaIngredienti = substr($stringaIngredienti, 0, -2);
                        $stringaReturn .= "<p>" . $stringaIngredienti . "</p>";
                        $stringaReturn .= "</div>";

                        $stringaReturn .= "<p class=\"pizza-prezzo\">&euro; " . $riga['prezzo'] . "</p>";

                        $stringaReturn .= "<div class=\"order-actions\">";

                        if(!isset($_SESSION['tipo']) OR $_SESSION['tipo']!=1) {
                            if (isset($_SESSION['carrello'][$riga['id']])) {
                                $stringaReturn .= '<div class="quantity-controls">
                                <form method="POST" action="menu-prenota.php" class="inlineComponents">
                                <input type="hidden" name="id" value="' . $riga['id'] . '" />
                                <button type="submit" name="azione" aria-label="Rimuovi una '.$riga['nome'].' dal carrello" value="Rimuovi una '.$riga['nome'].' dal carrello"><img src="assets/icons/minus.png" alt="" /></button>
                                
                                </form>';
                                $stringaReturn .= '<h4>';
                                $stringaReturn .= $_SESSION['carrello'][$riga['id']]['quantita'];
                                $stringaReturn .= '</h4>';
                                $stringaReturn .= '<form method="POST" action="menu-prenota.php" class="inlineComponents">
                                <input type="hidden" name="id" value="' . $riga['id'] . '" />
                                <input type="hidden" name="azione" value="incrementa" />
                                <button type="submit" aria-label="Aggiungi una '.$riga['nome'].' al carrello" value="Aggiungi una '.$riga['nome'].' al carrello"><img src="assets/icons/plus.png" alt="" /></button>
                                </div>
                                </form>';
                            } else {
                                $stringaReturn .= '<form method="POST" action="menu-prenota.php">';
                                $stringaReturn .= '<input type="hidden" name="id" value="' . $riga['id'] . '" />';
                                $stringaReturn .= '<input type="hidden" name="prezzo" value="' . $riga['prezzo'] . '" />';
                                $stringaReturn .= '<input type="hidden" name="nome" value="' . $riga['nome'] . '" />';
                                $stringaReturn .= '<input type="hidden" name="quantita" value="1" />';
                                $stringaReturn .= '<input type="hidden" name="azione" value="Aggiungi al carrello" />';
                                $stringaReturn .= '<input type="submit" value="Aggiungi '.$riga['nome'].' al carrello" class="invia-button" />';
                                $stringaReturn .= '</form>';
                            }
                        }
                        $stringaReturn .= '</div>';
                        $stringaReturn .= '</div>';
                    }
                    $stringaReturn .= '</div>';
                    $stringaReturn .= '<div class="torna-su">';
                    $stringaReturn .= '<a href="#MP-intro"><img src="assets/icons/torna-su.png" alt="Torna su" /></a>';
                    $stringaReturn .= '</div>';
                    $stringaReturn .= '</section>';
                }
            }
        }
        if($visited == false){
            $stringaReturn .= '<div class="menu-prodpercat"><h2>Siamo spiacenti, la pizza cercata non esiste</h2>';
            $stringaReturn .= '<div class="torna-su">';
            $stringaReturn .= '<a href="#MP-intro"><img src="assets/icons/torna-su.png" alt="Torna su" /></a>';
            $stringaReturn .= '</div>';
            $stringaReturn .= '</div>';
        }
        return $stringaReturn;
    }

    public function getMenuCucina($nome = '') :string{
        $visited = false;
        $stringaReturn = "";
        if(!empty($nome)){
            try {
                $queryCucina = "SELECT * FROM cucina WHERE nome LIKE ?";
                $stmt = $this->connection->prepare($queryCucina);
                $nome = '%'.$nome.'%';
                $stmt->bind_param('s', $nome);
                $stmt->execute();
                $pizze = $stmt->get_result();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }

        } else {
            try {
                $queryCucina = "SELECT * FROM cucina";
                $pizze = mysqli_query($this->connection, $queryCucina);
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        }
        if(mysqli_num_rows($pizze) > 0) {
            $visited = true;
            $stringaReturn .= "<section class=\"menu-prodpercat\" id=\"".str_replace(' ','',"cucina")."\">";
            $stringaReturn .= "<h2>La nostra cucina</h2>";
            $stringaReturn .= "<p class=\"sez-intro\">La nostra passione, coltivata a 360°: Non Solo Pizza!</p>";
            $stringaReturn .= "<div class=\"pizza-container\">";
            while ($riga = $pizze->fetch_array(MYSQLI_ASSOC)) {
                $stringaReturn .= "<div class=\"pizza\" id=\"c-".$riga['id']."\">";
                $stringaReturn .= "<div><img src=\"" . $riga['path'] . "\" alt=\"\" loading=\"lazy\" /></div>";
                $stringaReturn .= "<div class=\"pizza-testo\">";
                $stringaReturn .= "<h3>" . $riga['nome'];
                if($riga['veget'] == "1"){
                    $stringaReturn .= "<img src=\"assets/icons/foglia.png\" alt=\"Vegetariana\" />";
                }
                $stringaReturn .= "</h3>";
                try {
                    $queryIngredienti = "SELECT cucina_ingrediente.ingrediente AS ingrediente, ingrediente.peso AS peso FROM cucina_ingrediente JOIN ingrediente ON cucina_ingrediente.ingrediente=ingrediente.nome WHERE cucina='" . $riga['id'] . "' ORDER BY peso";
                    $ingredientiPizza = mysqli_query($this->connection, $queryIngredienti);
                }catch(mysqli_sql_exception $e){
                    header("location: errore.php");
                }
                $stringaIngredienti = "";
                if(mysqli_num_rows($ingredientiPizza) > 0) {
                    while ($ingrediente = $ingredientiPizza->fetch_array(MYSQLI_ASSOC)) {
                        $stringaIngredienti .= $ingrediente['ingrediente'].", ";
                    }
                }
                $stringaIngredienti = substr($stringaIngredienti, 0, -2);
                $stringaReturn .= "<p>" . $stringaIngredienti . "</p>";
                $stringaReturn .= "</div>";

                $stringaReturn .= "<p class=\"pizza-prezzo\">&euro; " . $riga['prezzo']. "</p>";

                $stringaReturn .= "<div class=\"order-actions\">";

                if(!isset($_SESSION['tipo']) OR $_SESSION['tipo']!=1) {
                    if (isset($_SESSION['carrello']["c" . $riga['id']])) {
                        $stringaReturn .= '<div class="quantity-controls">';
                        $stringaReturn .= '<form method="POST" action="menu-prenota.php" class="inlineComponents">';
                        $stringaReturn .= '<input type="hidden" name="id" value="c' . $riga['id'] . '" />';
                        $stringaReturn .='<input type="hidden" name="azione" value="decrementa" />';
                        $stringaReturn .='<button type="submit" aria-label="Rimuovi una '.$riga['nome'].' dal carrello" value="Rimuovi una '.$riga['nome'].' dal carrello">><img src="assets/icons/minus.png" alt="" /></button>';
                        $stringaReturn .='</form>';
                        $stringaReturn .= '<h4>';
                        $stringaReturn .= $_SESSION['carrello']["c" . $riga['id']]['quantita'];
                        $stringaReturn .= '</h4>';
                        $stringaReturn .= '<form method="POST" action="menu-prenota.php" class="inlineComponents">';
                        $stringaReturn .= '<input type="hidden" name="id" value="c' . $riga['id'] . '" />';
                        $stringaReturn .= '<input type="hidden" name="id" value=c' . $riga['id'] . '" />';
                        $stringaReturn .='<button type="submit" name="azione" aria-label="Aggiungi una '.$riga['nome'].' al carrello" value="Aggiungi una '.$riga['nome'].' al carrello"><img src="assets/icons/plus.png" alt="" /></button>';
                        $stringaReturn .= '</form></div>';
                    } else {
                        $stringaReturn .= '<form method="POST" action="menu-prenota.php">';
                        $stringaReturn .= '<input type="hidden" name="id" value="c' . $riga['id'] . '" />';
                        $stringaReturn .= '<input type="hidden" name="prezzo" value="' . $riga['prezzo'] . '" />';
                        $stringaReturn .= '<input type="hidden" name="nome" value="' . $riga['nome'] . '" />';
                        $stringaReturn .= '<input type="hidden" name="quantita" value="1" />';
                        $stringaReturn .= '<input type="hidden" name="azione" value="Aggiungi al carrello" />';
                        $stringaReturn .= '<input type="submit" value="Aggiungi '.$riga['nome'].' al carrello" class="invia-button" />';
                        $stringaReturn .= '</form>';
                    }
                }
                $stringaReturn .= '</div>';
                $stringaReturn .= '</div>';
            }
            $stringaReturn .= '</div>';
            $stringaReturn .= '<div class="torna-su">';
            $stringaReturn .= '<a href="#MP-intro"><img src="assets/icons/torna-su.png" alt="Torna su" /></a>';
            $stringaReturn .= '</div>';
            $stringaReturn .= '</section>';
        }
        if($visited == false){
            $stringaReturn .= '<div class="menu-prodpercat"><h2>Siamo spiacenti, il piatto cercato non esiste</h2>';
            $stringaReturn .= '<div class="torna-su">';
            $stringaReturn .= '<a href="#MP-intro"><img src="assets/icons/torna-su.png" alt="Torna su" /></a>';
            $stringaReturn .= '</div>';
            $stringaReturn .= '</div>';
        }
        return $stringaReturn;
    }

    public function getMenuCategorie(): string {
        try {
            $query = "SELECT cat, nomeEsteso FROM categoria";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if (mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $stringaReturn .= "<a href=\"#".str_replace(' ','',$row['cat'])."\">".$row['nomeEsteso']."</a>";
            }
        }
        return $stringaReturn;
    }

    public function getFuoriMenu(): string {
        try {
            $query = "SELECT id,nome,descrizione,path FROM pizza WHERE categoria='Fuori menù'";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<li>";
                $stringaReturn .= "<a href=\"menu-prenota.php#p-".$row['id']."\"><img src=\"".$row['path']."\" alt=\"\" />";
                $stringaReturn .= "<h3>".$row['nome']."</h3>";
                $stringaReturn .= "<p>".$row['descrizione']."</p>";
                $stringaReturn .= "</a></li>";
            }
        }
        return $stringaReturn;
    }

    public function getFuoriMenuPerCarrello(): string
    {
        try {
            $query = "SELECT * FROM pizza WHERE categoria='Fuori menù'";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

                $stringaReturn .= "<li>";
                $stringaReturn .= "<img src=\"".$row['path']."\" alt=\"\" loading=\"lazy\" />";
                $stringaReturn .= "<h4>".$row['nome']."</h4>";
                $stringaReturn .= "<p>".$row['descrizione']."</p>";
                $stringaReturn .= "<form method=\"POST\" action=\"carrello.php\" >";
                $stringaReturn .= "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\" />";
                $stringaReturn .= "<input type=\"hidden\" name=\"prezzo\" value=\"".$row['prezzo']."\" />";
                $stringaReturn .= "<input type=\"hidden\" name=\"nome\" value=\"".$row['nome']."\" />";
                $stringaReturn .= "<input type=\"hidden\" name=\"quantita\" value=\"1\" />";
                $stringaReturn .= '<input type="hidden" name="azione" value="Aggiungi al carrello" />';
                $stringaReturn .= '<input type="submit" value="Aggiungi '.$row['nome'].' al carrello" class="invia-button" />';
                $stringaReturn .= "</form></li>";
            }
        }
        return $stringaReturn;
    }

    public function queryIngredienti($id = null): string {
        if($id != null){
            $query = "SELECT * FROM ingrediente WHERE nome = '".$id."'";
        }else{
            $query = "SELECT * FROM ingrediente";
        }
        return $query;
    }

    public function infoIngredienti($query){
        $ingredienti = array();
        try{
            $resultSelect = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }

        if(mysqli_num_rows($resultSelect) > 0) {
            while($row = $resultSelect->fetch_array(MYSQLI_ASSOC)){
                $ingredienti[0] = $row['nome'];
                $ingredienti[1] = $row['veget'];
            }
        }
        return $ingredienti;
    }

    public function getIngredienti($query, $id = null, $cucina = 0): string {
        $ingredienti = array();
        $resultSelect = "";
        if($id != null){
            if($cucina == 0){
                try{
                    $querySelect = "SELECT ingrediente FROM pizza_ingrediente WHERE pizza = ?";
                    $stmt = $this->connection->prepare($querySelect);
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $resultSelect = $stmt->get_result();
                }catch(mysqli_sql_exception $e){
                    header("location: errore.php");
                }
            }else{
                try {
                    $querySelect = "SELECT ingrediente FROM cucina_ingrediente WHERE cucina = ?";
                    $stmt = $this->connection->prepare($querySelect);
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $resultSelect = $stmt->get_result();
                }catch(mysqli_sql_exception $e){
                    header("location: errore.php");
                }
            }

            if(mysqli_num_rows($resultSelect) > 0) {
                while($row = $resultSelect->fetch_array(MYSQLI_ASSOC)){
                    $ingredienti[] = $row['ingrediente'];
                }
            }
        }

        try {
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        $conta=1;
        $i = 0;
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<div class=\"check\">";
                if(count($ingredienti) > $i) {
                    if($ingredienti[$i] == $row['nome']){
                        $i++;
                        $stringaReturn .= "<input type=\"checkbox\" id=\"ingr".$conta."\" name=\"ingredienti[]\" value=\"".$row['nome']."\" checked />";
                    } else {
                        $stringaReturn .= "<input type=\"checkbox\" id=\"ingr".$conta."\" name=\"ingredienti[]\" value=\"".$row['nome']."\" />";
                    }
                } else{
                    $stringaReturn .= "<input type=\"checkbox\" id=\"ingr".$conta."\" name=\"ingredienti[]\" value=\"".$row['nome']."\" />";
                }
                $stringaReturn .= "<label for=\"ingr".$conta."\">".$row['nome']."</label>";
                $stringaReturn .= "</div>";
                $conta++;
            }
        }
        return $stringaReturn;
    }

    public function getCategorie($id = null): string {
        $categoria = '';
        if($id != null){
            try {
                $querySelect = "SELECT categoria FROM pizza WHERE id = ?";
                $stmt = $this->connection->prepare($querySelect);
                $stmt->bind_param('s', $id);
                $stmt->execute();
                $resultSelect = $stmt->get_result();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
            if(mysqli_num_rows($resultSelect) > 0) {
                $row = $resultSelect->fetch_array(MYSQLI_ASSOC);
                $categoria = $row['categoria'];
            }
        }
        try {
            $query = "SELECT cat FROM categoria";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                if($categoria == $row['cat']){
                    $stringaReturn .= "<option value=\"".$row['cat']."\" selected>".$row['cat']."</option>";
                }else{
                    $stringaReturn .= "<option value=\"".$row['cat']."\">".$row['cat']."</option>";
                }
            }
        }
        return $stringaReturn;
    }

    public function filtraProdotti($val = null, $filtro = 1) {
        if ($filtro > 0) {
            if ($_POST['tipo'] == '0') {
                $query = "SELECT * FROM ingrediente WHERE 1=1";
                if (!empty($_POST['nome'])) {
                    $query .= " AND nome LIKE \"%". $_POST['nome'] ."%\"";
                }
                return $this->getIngredientiTabella($query);
            } elseif ($_POST['tipo'] == '1') {
                $query = "SELECT * FROM pizza WHERE 1=1";
                if (!empty($_POST['nome'])) {
                    $query .= " AND nome LIKE \"%". $_POST['nome'] ."%\"";
                }
                return $this->getPizzeTabella($query);
            } elseif ($_POST['tipo'] == '2') {
                $query = "SELECT * FROM cucina WHERE 1=1";
                if (!empty($_POST['nome'])) {
                    $query .= " AND nome LIKE \"%". $_POST['nome']."%\"";
                }
                return $this->getCucinaTabella($query);
            } else {
                if($val == 0) {
                    $query = "SELECT * FROM pizza WHERE 1=1";
                    if (!empty($_POST['nome'])) {
                        $query .= " AND nome LIKE \"%". $_POST['nome']."%\"";
                    }
                    return $this->getPizzeTabella($query);
                }
                if($val == 1) {
                    $query = "SELECT * FROM cucina WHERE 1=1";
                    if (!empty($_POST['nome'])) {
                        $query .= " AND nome LIKE \"%". $_POST['nome']."%\"";
                    }
                    return $this->getCucinaTabella($query);
                }
                if($val == 2) {
                    $query = "SELECT * FROM ingrediente WHERE 1=1";
                    if (!empty($_POST['nome'])) {
                        $query .= " AND nome LIKE \"%". $_POST['nome']."%\"";
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
        try {
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['nome']."</th>";
                $stringaReturn .= "<td data-title=\"Tipo\">Ingrediente singolo</td>";
                $stringaReturn .= "<td></td>";
                $nome = str_replace(" ", "_", $row['nome']);
                $stringaReturn .= "<td data-title=\"Modifica\"><a href=\"aggiungi-ingrediente.php?nome=".$nome."\">Modifica</a></td>";
                $stringaReturn .= "<td data-title=\"Elimina\">";
                $stringaReturn .= "<form action=\"prodotti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"nome\" value=\"".$nome."\" />";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"deleteIngrediente\" />";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina ingrediente ".$row['nome']."\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        } else {
            $stringaReturn .= "<tr>";
            $stringaReturn .= "<td colspan=\"5\"> Nessun ingrediente trovato </td>";
            $stringaReturn .= "</td>";
            $stringaReturn .= "</tr>";
        }
        return $stringaReturn;
    }


    public function getCucinaTabella($query): string {
        try {
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['nome']."</th>";
                $stringaReturn .= "<td data-title=\"Tipo\">Piatto</td>";
                $stringaReturn .= "<td data-title=\"Prezzo\">&euro; ".$row['prezzo']."</td>";
                $stringaReturn .= "<td data-title=\"Modifica\" class=\"noprint\"><a href=\"aggiungi-cucina.php?id=".$row['id']."\">Modifica</a></td>";
                $stringaReturn .= "<td data-title=\"Elimina\" class=\"noprint\">";
                $stringaReturn .= "<form action=\"prodotti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\" />";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"deleteCucina\" />";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina piatto ".$row['nome']."\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        } else {
            $stringaReturn .= "<tr>";
            $stringaReturn .= "<td colspan=\"5\"> Nessun piatto trovato </td>";
            $stringaReturn .= "</td>";
            $stringaReturn .= "</tr>";
        }
        return $stringaReturn;
    }

    public function getPizzeTabella($query): string {
        try {
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['nome']."</th>";
                $stringaReturn .= "<td data-title=\"Tipo\">Pizza ".$row['categoria']."</td>";
                $stringaReturn .= "<td data-title=\"Prezzo\">&euro; ".$row['prezzo']."</td>";
                $stringaReturn .= "<td data-title=\"Modifica\" class=\"noprint\"><a href=\"aggiungi-pizza.php?id=".$row['id']."\">Modifica</a></td>";
                $stringaReturn .= "<td data-title=\"Elimina\" class=\"noprint\">";
                $stringaReturn .= "<form action=\"prodotti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\" />";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"deletePizza\" />";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina pizza ".$row['nome']."\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        } else {
            $stringaReturn .= "<tr>";
            $stringaReturn .= "<td colspan=\"5\"> Nessuna pizza trovata </td>";
            $stringaReturn .= "</td>";
            $stringaReturn .= "</tr>";
        }
        return $stringaReturn;
    }

    public function getInfoPizza($id){
        $return = array();
        $result = "";
        try {
            $query = "SELECT nome, prezzo, descrizione FROM pizza WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $return[0] = $row['nome'];
            $return[1] = $row['prezzo'];
            $return[2] = $row['descrizione'];
            return $return;
        }
        return '';
    }

    public function getInfoCucina($id){
        $return = array();
        try {
            $query = "SELECT nome, prezzo FROM cucina WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $return[0] = $row['nome'];
            $return[1] = $row['prezzo'];
            return $return;
        }
        return '';
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
                $query .= " AND nome LIKE '%".$_POST['nome_utente']."%'";
            }
            if(isset($_POST['username_utente']) && $_POST['username_utente'] != ''){
                $query .= " AND username LIKE '%".$_POST['username_utente']."%'";
            }
        }else if ($filtro == null){
            $query .= "SELECT nome, cognome, username, email, ruolo FROM utente";
        }
        return $query;
    }

    public function getUtenti($query): string {
        $conta = 0;
        try {
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
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
                $stringaReturn .= "<td data-title=\"Modifica ruolo\" class=\"noprint\">";
                $stringaReturn .= "<form action=\"gestisci-utenti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"update\" />";
                $stringaReturn .= "<label for=\"ruoloUpdate".$conta."\">Modifica ".$row['nome'].":</label>";
                $stringaReturn .= "<select name=\"ruolo\" class=\"select\" id=\"ruoloUpdate".$conta."\">";
                $stringaReturn .= "<option value=\"0\">Cliente</option>";
                $stringaReturn .= "<option value=\"1\">Amministratore</option>";
                $stringaReturn .= "</select>";
                $stringaReturn .= "<input type=\"hidden\" name=\"email\" value=\"".$row['email']."\" />";
                $stringaReturn .= "<input type=\"submit\" value=\"Aggiorna ".$row['nome']."\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "<td data-title=\"Elimina Utente\" class=\"noprint\">";
                $stringaReturn .= "<form action=\"gestisci-utenti.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"email\" value=\"".$row['email']."\" />";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"delete\" />";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina ".$row['nome']."\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
                $conta++;
            }
        } else {
            $stringaReturn .= "<tr>";
            $stringaReturn .= "<td colspan=\"6\"> Nessun utente trovato </td>";
            $stringaReturn .= "</td>";
            $stringaReturn .= "</tr>";
        }
        return $stringaReturn;
    }

    public function queryOrdini($filtro = 0): string {
        $query = '';
        if($filtro != null){
            if(isset($_POST['stato']) && $_POST['stato'] == ''){
                $query .= "SELECT o.id, u.nome AS cliente_nome, u.cognome AS cliente, o.data, o.ora, o.stato FROM ordine AS o, utente AS u WHERE u.email = o.utente AND (stato=0 OR stato=1 OR stato=-1)";
            }else{
                $query .= "SELECT o.id, u.nome AS cliente_nome, u.cognome AS cliente, o.data, o.ora, o.stato FROM ordine AS o, utente AS u WHERE u.email = o.utente AND stato='".$_POST['stato']."'";
            }
            if(isset($_POST['data']) && $_POST['data'] != ''){
                $query .= " AND data LIKE '%".$_POST['data']."%'";
            }
            if(isset($_POST['cliente']) && $_POST['cliente'] != ''){
                $query .= " AND u.nome LIKE '%".$_POST['cliente']."%'";
            }
        }else if ($filtro == 0){
            $query .= "SELECT o.id, u.nome AS cliente_nome, u.cognome AS cliente, o.data, o.ora, o.stato FROM ordine AS o JOIN utente AS u ON u.email = o.utente";
        }
        $query .= " ORDER BY o.id DESC";
        return $query;
    }

    public function getTotalePrezzoOrdine($idOrdine){
        try {
            $query = "SELECT PO.quantita, COALESCE(P.prezzo, C.prezzo) AS prezzo FROM prodotti_ordine AS PO LEFT JOIN pizza AS P ON PO.pizza = P.id LEFT JOIN cucina AS C ON PO.cucina = C.id WHERE PO.ordine = '" . $idOrdine . "'";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $prezzo = 0;
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $prezzo += ($row['quantita']*$row['prezzo']);
            }
            return $prezzo;
        }
    }

    public function getTotaleProdottiOrdine($idOrdine){
        try {
            $query = "SELECT PO.quantita FROM prodotti_ordine AS PO WHERE PO.ordine='" . $idOrdine . "'";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $conta = 0;
        if(mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $conta += $row['quantita'];
            }
            return $conta;
        }
    }

    public function getOrdini($query): string {
        try {
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['id']."</th>";
                $stringaReturn .= "<td data-title=\"Cliente\">".$row['cliente_nome']." ".$row['cliente']."</td>";
                $stringaReturn .= "<td data-title=\"Data e ora\"><time datetime=\"".$row['data']."\">".$row['data']."</time> - ".$row['ora']."</td>";
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
                $stringaReturn .= "<td data-title=\"Modifica stato\" class=\"noprint\">";
                $stringaReturn .= "<form action=\"visualizza-ordini.php\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"action\" value=\"update\" />";
                $stringaReturn .= "<label for=\"modificaS".$row['id']."\">Modifica ".$row['id'].":</label>";
                $stringaReturn .= "<select name=\"stato\" id=\"modificaS".$row['id']."\" class=\"select\">";
                $stringaReturn .= "<option value=\"1\">Consegnato</option>";
                $stringaReturn .= "<option value=\"0\">In corso</option>";
                $stringaReturn .= "<option value=\"-1\">Annullato</option>";
                $stringaReturn .= "</select>";
                $stringaReturn .= "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\" />";
                $stringaReturn .= "<input type=\"submit\" value=\"Modifica ".$row['id']."\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "<td data-title=\"Dettagli\" class=\"noprint\">";
                $stringaReturn .= "<img class=\"visualizza-dettagli\" src=\"assets/icons/see-more.png\" alt=\"\" />";
                $stringaReturn .= "<a href=\"dettagli-ordine.php?idOrdine=".$row['id']."\">Visualizza dettagli</a>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        } else {
            $stringaReturn .= "<tr>";
            $stringaReturn .= "<td colspan=\"7\"> Nessun ordine trovato </td>";
            $stringaReturn .= "</td>";
            $stringaReturn .= "</tr>";
        }
        return $stringaReturn;
    }

    public function getDettagliOrdine($idOrdine): string {
        if($_SESSION['tipo'] == 0){
            try{
                $query = "SELECT id FROM ordine WHERE id = ? AND utente = ?";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param("ss", $idOrdine, $_SESSION['email']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows == 0){
                    header("location: riepilogo-ordini.php");
                }
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        }else{
            try{
                $query = "SELECT id FROM ordine WHERE id = ?";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param("s", $idOrdine);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows == 0){
                    header("location: visualizza-ordini.php");
                }
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        }
        try {
            $query = "SELECT COALESCE(P.nome, C.nome) AS prodotto, PO.quantita AS quantita, (PO.quantita * COALESCE(P.prezzo, C.prezzo)) AS prezzo FROM prodotti_ordine AS PO LEFT JOIN pizza AS P ON PO.pizza = P.id LEFT JOIN cucina AS C ON PO.cucina = C.id WHERE PO.ordine = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $idOrdine);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
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
    public function getNote($idOrdine): string {
        try {
            $query = "SELECT nota FROM ordine WHERE id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $idOrdine);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if($row['nota'] == null){
                $stringaReturn = "<p id=\"note\">Nessuna nota</p>";
            }else{
                $stringaReturn .= "<p id=\"note\">Note: ".$row['nota']."</p>";
            }
        }
        return $stringaReturn;
    }

    public function getOrdiniUtente($email): string {
        try {
            $query = "SELECT o.id, o.data, o.ora, o.stato FROM ordine AS o WHERE '" . $email . "' = o.utente ORDER BY o.id DESC";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC) ){
                $stringaReturn .= "<tr>";
                $stringaReturn .= "<th scope=\"row\">".$row['id']."</th>";
                $stringaReturn .= "<td data-title=\"Data e ora\"><time datetime=\"".$row['data']."\">".$row['data']."</time> - ".$row['ora']."</td>";
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
                $stringaReturn .= "<td data-title=\"Dettagli\" class=\"noprint\">";
                $stringaReturn .= "<img class=\"visualizza-dettagli\" src=\"assets/icons/see-more.png\" alt=\"\" />";
                $stringaReturn .= "<a href=\"dettagli-ordine.php?idOrdine=".$row['id']."\">Visualizza dettagli</a>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "</tr>";
            }
        }
        return $stringaReturn;
    }

    public function isVeget(array $ingredienti) {
        foreach($ingredienti as $ingrediente) {
            try {
                $query = "SELECT veget FROM ingrediente WHERE nome='" . $ingrediente . "'";
                $result = mysqli_query($this->connection, $query);
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
            if(mysqli_num_rows($result) > 0) {
                $row = $result->fetch_assoc();
                if ($row['veget'] == 0) {
                    return 0;
                }
            }
        }
        return 1;
    }

    public function insertIngrediente($nome, $veget, $nomeOld = null) {
        if($nomeOld != null){
            try {
                $query = "UPDATE ingrediente SET nome = ?, veget = ? WHERE nome = ?";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('sis', $nome, $veget, $nomeOld);
                $stmt->execute();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        } else {
            try {
                $query = "INSERT INTO ingrediente(nome, veget) " . "VALUES (?,?)";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('si', $nome, $veget);
                $stmt->execute();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        }
        return true;
    }

    public function insertPizza($nome, $prezzo, $veget, $categoria, $descrizione, $path, $id = null): bool {
        $path = str_replace("/var/www/html/", "", $path);
        if($id != null){
            try {
                $query = "UPDATE pizza SET nome = ?, prezzo = ?, veget = ?, categoria = ?, descrizione = ?, path = ? WHERE id = ?";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('sdisssi', $nome, $prezzo, $veget, $categoria, $descrizione, $path, $id);
                $stmt->execute();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        } else {
            try {
                $query = "INSERT INTO pizza(nome, prezzo, veget, categoria, descrizione, path) VALUES (?,?,?,?,?,?)";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('sdisss', $nome, $prezzo, $veget, $categoria, $descrizione, $path);
                $stmt->execute();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        }
        return true;
    }

    function getCurrentPath($id,$tabella){
        $result= '';
        try {
            $query = "SELECT path FROM ".$tabella." WHERE id=?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('d', $id);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        if(mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();
            return $row['path'];
        }
        return false;
    }

    public function insertProdottoIngrediente($nome, $ingredienti, $table, $id = null) {
        $result = "";
            if ($id != null){
                try {
                    $query = "DELETE FROM " .$table. "_ingrediente WHERE ".$table."=?";
                    $stmt = $this->connection->prepare($query);
                    $stmt->bind_param('s', $id);
                    $stmt->execute();
                }catch(mysqli_sql_exception $e){
                    header("location: errore.php");
                }
            }
        try {
            $query = "SELECT id FROM $table WHERE nome = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $nome);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $row = mysqli_fetch_assoc($result);
        $prodottoId = $row['id'];

        foreach ($ingredienti as $ingrediente) {
            try {
                $ingrediente = mysqli_real_escape_string($this->connection, $ingrediente);
                $queryInsert = "INSERT INTO " .$table. "_ingrediente ($table, ingrediente) VALUES (?,?)";
                $stmt = $this->connection->prepare($queryInsert);
                $stmt->bind_param('is', $prodottoId, $ingrediente);
                $stmt->execute();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        }
        return true;
    }

    public function insertCucina($nome, $prezzo, $veget, $path, $id = null) {
        $path = str_replace("/var/www/html/", "", $path);
        if($id != null){
            try {
                $query = "UPDATE cucina SET nome = ?, prezzo = ?, veget = ?, path = ? WHERE id = ?";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('sdisi', $nome, $prezzo, $veget, $path, $id);
                $stmt->execute();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        } else {
            try {
                $query = "INSERT INTO cucina(nome, prezzo, veget, path) VALUES (?,?,?,?)";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('sdis', $nome, $prezzo, $veget, $path);
                $stmt->execute();
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
        }
        return true;
    }

    public function updateUtente($ruolo) {
        try {
            $queryUpdate = "UPDATE utente SET ruolo = ? WHERE email = ?";
            $stmtUno = $this->connection->prepare($queryUpdate);
            $stmtUno->bind_param('is', $ruolo, $_POST['email']);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        try {
            $query = "SELECT ruolo FROM utente WHERE email=?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        if(mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();
            if ($ruolo != $row['ruolo']) {
                $stmtUno->execute();
                return true;
            }
            return false;
        }
        return false;
    }

    public function updateOrdine($stato) {
        try {
            $queryUpdate = "UPDATE ordine SET stato=? WHERE id=?";
            $stmt = $this->connection->prepare($queryUpdate);
            $stmt->bind_param('si', $stato, $_POST['id']);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        try {
            $query = "SELECT stato FROM ordine WHERE id='" . $_POST['id'] . "'";
            $result = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        if(mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();
            if ($stato != $row['stato']) {
                $stmt->execute();
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
        try {
            $query = "DELETE FROM pizza_ingrediente WHERE pizza='" . $_POST['id'] . "'";
            mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return "DELETE FROM pizza WHERE id='".$_POST['id']."'";
    }

    public function queryDeleteCucina(): string{
        try {
            $query = "DELETE FROM cucina_ingrediente WHERE cucina='" . $_POST['id'] . "'";
            mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return "DELETE FROM cucina WHERE id='".$_POST['id']."'";
    }

    public function queryDeleteIngrediente(): string{
        try {
            $query = "SELECT pizza AS id, 'pizza' AS tipo FROM pizza_ingrediente WHERE ingrediente = '" . $_POST['nome'] . "' UNION SELECT cucina AS id, 'cucina' AS tipo FROM cucina_ingrediente WHERE ingrediente = '" . $_POST['nome'] . "'";
            $result = mysqli_query($this->connection, $query) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        if (mysqli_num_rows($result) > 0) {
            try {
                mysqli_query($this->connection, "DELETE FROM pizza_ingrediente WHERE ingrediente='" . $_POST['nome'] . "'");
                mysqli_query($this->connection, "DELETE FROM cucina_ingrediente WHERE ingrediente='" . $_POST['nome'] . "'");
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
            while ($row = mysqli_fetch_assoc($result)) {
                $queryDelete = "DELETE FROM ".$row['tipo']." WHERE id=".$row['id'];
                $this->delete($queryDelete);
            }
        }

        return "DELETE FROM ingrediente WHERE nome='".$_POST['nome']."'";
    }

    public function removeAssocProdIngr($ingrediente){
        try{
            mysqli_query($this->connection, "DELETE FROM pizza_ingrediente WHERE ingrediente='".$ingrediente."'");
            mysqli_query($this->connection, "DELETE FROM cucina_ingrediente WHERE ingrediente='".$ingrediente."'");
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
    }

    public function delete($queryDelete) {
        try {
            mysqli_query($this->connection, $queryDelete) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return true;

    }

    public function checkUserExists($username) {
        try {
            $query = "SELECT username FROM utente WHERE username = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return $result->num_rows > 0;
    }

    public function checkEmailExists($email) {
        try {
            $query = "SELECT email FROM utente WHERE email = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return $result->num_rows > 0;
    }

    public function checkIngrediente($nome) {
        $result = "";
        try {
            $query = "SELECT nome FROM ingrediente WHERE nome = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $nome);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return $result->num_rows > 0;
    }

    public function checkPizza($nome) {
        $result = "";
        try {
            $query = "SELECT nome FROM pizza WHERE nome = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $nome);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return $result->num_rows > 0;
    }

    public function checkCucina($nome) {
        $result = "";
        try {
            $query = "SELECT nome FROM cucina WHERE nome = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $nome);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return $result->num_rows > 0;
    }

    public function registerUser($name, $surname, $username, $email, $hashedPassword) {
        $ruolo = 0;
        try {
            $query = "INSERT INTO utente (nome, cognome, username, email, password, ruolo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('sssssi', $name, $surname, $username, $email, $hashedPassword, $ruolo);
            return $stmt->execute();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        return false;
    }

    public function userLogin($username, $password) {
        try {
            $query = "SELECT nome, cognome, ruolo, password, email FROM utente WHERE username = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return array($user['nome'], $user['cognome'], $user['ruolo'], $user['email']);
            }
        }
        return false;
    }

    public function getFasceOrarie($daOrdinare){
        try {
            $query = "SELECT disponiblitaorarie.fascia AS fascia, pizzePerFascia.pizze AS pizze FROM disponiblitaorarie LEFT JOIN pizzePerFascia ON disponiblitaorarie.fascia = pizzePerFascia.fascia WHERE disponiblitaorarie.fascia NOT IN (SELECT orario FROM checkOrari) ORDER BY disponiblitaorarie.fascia;";
            $risultato = mysqli_query($this->connection, $query);
        }catch(mysqli_sql_exception $e){
            header("location: errore.php");
        }
        $selectReturn ="";
        $primadisponibilita = "";
        if(mysqli_num_rows($risultato) > 0){
            while($row = mysqli_fetch_assoc($risultato)){
                $orarioFascia = substr($row['fascia'], 0, -6);
                $orarioCorrente = date("H:i");
                if(strtotime($orarioCorrente) < strtotime($orarioFascia)){
                    if($primadisponibilita==""){
                        $primadisponibilita = $row["fascia"];
                    }
                    if($daOrdinare + $row["pizze"] < 20) {
                        $selectReturn .= "<option value=\"" . $row['fascia'] . "\">" . $row['fascia'] . "</option>";
                    }
                }
            }
        }
        return array($primadisponibilita, $selectReturn);
    }

    public function insertOrder($orario, $nota = ""){
        if($nota != ""){
            try {
                $query = "INSERT INTO ordine (utente, ora, nota) VALUES (?, ?, ?)";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('sss', $_SESSION['email'], $orario, $nota);
                $stmt->execute();
                return $stmt->insert_id;
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
            return false;
        }else{
            try {
                $query = "INSERT INTO ordine (utente, ora) VALUES (?, ?)";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param('ss', $_SESSION['email'], $orario);
                $stmt->execute();
                return $stmt->insert_id;
            }catch(mysqli_sql_exception $e){
                header("location: errore.php");
            }
            return false;
        }
    }

    public function itemToOrdine($idOrd){

        foreach ($_SESSION['carrello'] as $id => $item) {
            if(str_contains($id, "c")){
                $id = ltrim($id, 'c');
                $query = "INSERT INTO prodotti_ordine (ordine, cucina, quantita) VALUES ($idOrd,$id, " . $item['quantita'] . ")";
                try {
                    mysqli_query($this->connection, $query);
                } catch (mysqli_sql_exception $e) {
                    header("location: errore.php");
                }
            }else {
                $query = "INSERT INTO prodotti_ordine (ordine, pizza, quantita) VALUES ($idOrd,$id, " . $item['quantita'] . ")";
                try {
                    mysqli_query($this->connection, $query);
                } catch (mysqli_sql_exception $e) {
                    header("location: errore.php");
                }
            }


        }
        return true;

    }
}



