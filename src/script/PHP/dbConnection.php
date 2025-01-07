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
}
