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

    public function getPizzeClassiche(): string{
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
                        $stringaReturn .= "<p>" . $riga['descrizione'] . "</p>";
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
        /*
        $query = "SELECT * FROM pizza WHERE categoria='classica'";
        $result = mysqli_query($this->connection, $query);
        $stringaReturn = "";
        if (mysqli_num_rows($result) > 0) {
            $stringaReturn .= "<div class='pizza-container'>";
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $stringaReturn .= "<div class='pizza' id='p-" . $row['id'] . "' >";
                $stringaReturn .= "<div><img src='" . $row['path'] . "' alt='" . $row['nome'] . "'></div>";
                $stringaReturn .= "<div class='pizza-testo'>";
                $stringaReturn .= "<h3>" . $row['nome'] . "</h3>";
                $stringaReturn .= "<p>" . $row['descrizione'] . "</p>";
                $stringaReturn .= "</div>";

                $stringaReturn .= "<div class='order-actions'>";


                if (isset($_SESSION['carrello'][$row['id']])) {
                    //$stringaReturn .= $_SESSION['carrello'][$row['id']['quantita']];
                    $stringaReturn .= '<form method="POST" action="?scroll=p-' . $row['id'] . '" class="inlineComponents">
                        <div class="quantity-controls">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <button type="submit" class="decrease" name="azione" value="decrementa"><i class="fa fa-minus"></i></button>
                        
                    </form>';
                    $stringaReturn .= '<h4>';
                    $stringaReturn .= $_SESSION['carrello'][$row['id']]['quantita'];
                    $stringaReturn .= '</h4>';
                    $stringaReturn .= '<form method="POST" action="?scroll=p-' . $row['id'] . '" class="inlineComponents">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <button type="submit" class="increase" name="azione" value="incrementa"><i class="fa fa-plus"></i></button>
                        </div>
                    </form>';
                } else {
                    $stringaReturn .= '<form method="POST" action="?scroll=p-' . $row['id'] . '">';
                    $stringaReturn .= '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    $stringaReturn .= '<input type="hidden" name="nome" value="' . $row['nome'] . '">';
                    $stringaReturn .= '<input type="hidden" name="quantita" value="1">';
                    $stringaReturn .= '<button type="submit" name="azione" value="aggiungi" class="home-button">Aggiungi al Carrello</button>';
                    $stringaReturn .= '</form>';
                }

                /*



                $stringaReturn .= '<form method="POST" action="" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$row['id'].'">
                        <button type="submit" name="azione" value="decrementa"><i class="fa fa-minus"></i></button>
                    </form>';
                $stringaReturn .= '<h4>';
                if(isset($_SESSION['carrello'][$row['id']]['quantita'])){
                    $stringaReturn .= $_SESSION['carrello'][$row['id']]['quantita'];
                }else{
                    $stringaReturn .= "0";
                }
                $stringaReturn .='</h4>';
                $rowsCarrello .= '<form method="POST" action="" class="inlineComponents">
                        <input type="hidden" name="id" value="'.row['id]'.'">
                        <button type="submit" name="azione" value="decrementa"><i class="fa fa-minus"></i></button>
                    </form>';
                $rowsCarrello .= '<h4>'. $item['quantita'] .'</h4>';
                $rowsCarrello .= '<form method="POST" action="" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$id.'">
                        <button type="submit" name="azione" value="incrementa"><i class="fa fa-plus"></i></button>
                    </form>';
                /*
                $stringaReturn .= '<form method="POST" action="" class="inlineComponents">
                        <input type="hidden" name="id" value="'.$row['id'].'">
                        <button type="submit" name="azione" value="incrementa"><i class="fa fa-plus"></i></button>
                    </form>';
                // Form per il carrello
                $stringaReturn .= "<form method='POST' action='carrello.php'>";
                $stringaReturn .= "<input type='hidden' name='id' value='" . $row['id'] . "'>"; // id
                $stringaReturn .= "<input type='hidden' name='nome' value='" . $row['nome'] . "'>";
                $stringaReturn .= "<input type='hidden' name='quantita' value='1'>";
                //$stringaReturn .= "<button type='submit' name='azione' value='aggiungi' class='add-to-cart'>Aggiungi al carrello</button>";
                $stringaReturn .= "</form>";
                */
                /*
                $stringaReturn .= "</div>";
                $stringaReturn .= "</div>";
            }

            $stringaReturn .= "</div>";
        }
               */
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
                $stringaReturn .= "<a href='menu-prenota.php'><img src='../../../assets/".$row['path']."' alt='TODO'>";
                $stringaReturn .= "<p><strong>".$row['nome']."</strong></p>";
                $stringaReturn .= "<p>".$row['descrizione']."</p>";
                $stringaReturn .= "</a></li>";
            }
        }
        return $stringaReturn;
    }
}
