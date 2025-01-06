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
        $query = "SELECT nome,cognome,ruolo FROM utente WHERE `username`='$username' AND `password`='$password'";
        $result = mysqli_query($this->connection, $query);
        if(mysqli_num_rows($result) == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            return array($row['nome'], $row['cognome'], $row['ruolo']);
        }
        return false;
    }

    public function getPizzeFM(): string {
        $query = "SELECT nome,descrizione,path FROM pizza WHERE categoria='Fuori menù'";
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

    public function queryUtenti($filtro = null): string {
        if($filtro != null){
            $query = "SELECT nome, cognome, username, email, ruolo FROM utente WHERE ruolo='".$_POST['ruolo']."'";
        }else if ($filtro == null){
            $query = "SELECT nome, cognome, username, email, ruolo FROM utente";
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
                $stringaReturn .= "<form action=\"../../gestisci-utenti.php?action=update\" method=\"post\">";
                $stringaReturn .= "<select name=\"ruolo\" class=\"select\">";
                $stringaReturn .= "<option value=\"0\">Cliente</option>";
                $stringaReturn .= "<option value=\"1\">Amministratore</option>";
                $stringaReturn .= "</select>";
                $stringaReturn .= "<input type=\"hidden\" name=\"email\" value=\"".$row['email']."\">";
                $stringaReturn .= "<input type=\"submit\" value=\"Conferma\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
                $stringaReturn .= "</td>";
                $stringaReturn .= "<td data-title=\"Elimina Utente\">";
                $stringaReturn .= "<form action=\"../../gestisci-utenti.php?action=delete\" method=\"post\">";
                $stringaReturn .= "<input type=\"hidden\" name=\"email\" value=\"".$row['email']."\">";
                $stringaReturn .= "<input type=\"submit\" value=\"Elimina utente\" class=\"invia-button\" />";
                $stringaReturn .= "</form>";
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

    public function uploadImage() {
        // Controlla se un file è stato caricato
        if (!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE) {
            /*echo "Nessun file caricato.<br>";*/
            return;
        }

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/pizze/';
        $target_file = $uploadDir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if($check !== false) {
                /*echo "File is an image - " . $check["mime"] . ".";*/
                $uploadOk = 1;
            } else {
                /*echo "File is not an image.";*/
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            /*echo "Sorry, file already exists.";*/
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["file"]["size"] > 500000) {
            /*echo "Sorry, your file is too large.";*/
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            /*echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";*/
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            /*echo "Sorry, your file was not uploaded.";*/
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                /*echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";*/
            } else {
                /*echo "Sorry, there was an error uploading your file.";*/
            }
        }
    }

    public function updateUtente($ruolo) {
        $queryUpdate = "UPDATE utente SET ruolo='".$ruolo."' WHERE email='".$_POST['email']."'";

        $query = "SELECT ruolo FROM utente WHERE email='".$_POST['email']."'";
        $result = mysqli_query($this->connection, $query);
        $queryResult = mysqli_query($this->connection, $queryUpdate) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        if(mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();
            if($ruolo == $row['ruolo']){
                return false;
            }else if(mysqli_affected_rows($this->connection) > 0){
                return true;
            }
            else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function deleteUtente() {
        $queryDelete = "DELETE FROM utente WHERE email='".$_POST['email']."'";

        $queryResult = mysqli_query($this->connection, $queryDelete) or die("Errore in openDBConnection: " . mysqli_error($this->connection));
        if(mysqli_affected_rows($this->connection) > 0){
            return true;
        }
        else {
            return false;
        }
    }
}
