<?php
namespace DB;
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
    public function userLogin($username, $password) {
        $query = "SELECT ruolo FROM utente WHERE `username`='$username' AND `password`='$password'";
        $result = mysqli_query($this->connection, $query);
        if(mysqli_num_rows($result) == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            return $row['ruolo'];
        }
        return false;
    }

    public function getPizzeSpeciali(): string {
        $query = "SELECT nome,descrizione,path FROM pizza WHERE categoria='speciale'";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if(mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $stringaReturn .= "<li>";
                $stringaReturn .= "<a href='menu-prenota.php'><img src='../../../assets/".$row['path']."' alt='TODO'>";
                $stringaReturn .= "<p><strong>".$row['nome']."</strong></p>";
                $stringaReturn .= "<p>".$row['descrizione']."</p>";
                $stringaReturn .= "</a></li>";
            }
        }
        return $stringaReturn;
    }

    public function getIngredienti(): string {
        $query = "SELECT nome FROM ingrediente";
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

    public function insertIngrediente($nome, $veget, $pagg) {

        $queryInsert = "INSERT INTO ingrediente(nome, veget, pagg) " .
            "VALUES (\"$nome\", \"$veget\", \"$pagg\")";

        $queryResult = mysqli_query($this->connection, $queryInsert) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }
        else {
            return false;
        }
    }

    public function insertPizza($nome, $prezzo, $veget, $categoria, $descrizione, $path) {

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

    public function insertPizzaIngrediente($nome, $ingredienti) {
        // Sanitizza il nome della pizza
        /*$nome = mysqli_real_escape_string($this->connection, $nome);*/
        $query = "SELECT id FROM pizza WHERE nome='$nome'";
        $result = mysqli_query($this->connection, $query);

        if (!$result) {
            die("Errore nella query per trovare l'ID della pizza: " . mysqli_error($this->connection));
        }

        if (mysqli_num_rows($result) === 0) {
            die("Errore: Nessuna pizza trovata con il nome '$nome'.");
        }

        $row = mysqli_fetch_assoc($result);
        $pizzaId = $row['id'];

        // Itera sugli ingredienti e inseriscili
        foreach ($ingredienti as $ingrediente) {
            $ingrediente = mysqli_real_escape_string($this->connection, $ingrediente);
            $queryInsert = "INSERT INTO pizza_ingredente (pizza, ingrediente) VALUES ('$pizzaId', '$ingrediente')";

            if (!mysqli_query($this->connection, $queryInsert)) {
                die("Errore nell'inserimento dell'ingrediente '$ingrediente': " . mysqli_error($this->connection));
            }
        }
        return true;
    }
}
