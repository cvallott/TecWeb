<?php
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 0) {
    header("location:area-riservata.php");
}
