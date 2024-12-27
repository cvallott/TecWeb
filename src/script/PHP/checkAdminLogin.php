<?php

if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 1) {
    header("location:area-riservata.php");
}
