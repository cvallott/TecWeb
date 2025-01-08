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
                                $stringaIngredienti .= $ingrediente['ingrediente'].",";
                            }
                        }
                        $stringaIngredienti = substr($stringaIngredienti, 0, -1);
                        $stringaReturn .= "<p>" . $stringaIngredienti . "</p>";
                        $queryPrezzo = "SELECT prezzo FROM pizza WHERE id='".$riga['id']."'";
                        $prezzoPizza = mysqli_query($this->connection, $queryPrezzo);
                        if (mysqli_num_rows($prezzoPizza) > 0) {
                            $prezzo = $prezzoPizza->fetch_assoc();
                            $stringaReturn .= "<p class='pizza-prezzo'>Prezzo: €" . number_format($prezzo['prezzo'], 2, ',', '.') . "</p>";
                        }
                        $stringaReturn .= "</div>";

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

    public function getPizzeSpeciali(): string {
        $query = "SELECT nome,descrizione,path FROM pizza WHERE categoria='speciale'";
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

    public function registerUser($name, $surname, $username, $email, $hashedPassword) {
        $ruolo = 0; // ruolo default
        $query = "INSERT INTO utente (nome, cognome, username, email, password, ruolo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('sssssi', $name, $surname, $username, $email, $hashedPassword, $ruolo);
        return $stmt->execute();
    }

    public function userLogin($username, $password) {
        $query = "SELECT nome, cognome, ruolo, password FROM utente WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return array($user['nome'], $user['cognome'], $user['ruolo']);
            }
        }
        return false;
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



