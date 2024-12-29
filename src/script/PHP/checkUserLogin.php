<?php

if(!isset($_SESSION["tipo"])){
    header("location:area-riservata.php");
}