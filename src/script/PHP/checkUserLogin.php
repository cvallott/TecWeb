<?php
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 0) {
    unset($_SESSION["redirect"]);
    $_SESSION['redirect'] = basename($_SERVER['PHP_SELF']);
    header("location:area-riservata.php");
}
