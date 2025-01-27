<?php

function pulisciInput($value) {
    if (is_array($value)) {
        return array_map('trim', array_map('strip_tags', $value));
    } else {
        $value = trim($value);
        $value = strip_tags($value);
        $value = htmlentities($value);
        return $value;
    }
}

function pulisciDescrizione($value){
    $value = trim($value);
    $value = htmlentities($value);
    return $value;
}

function checkImage() {
    if (!isset($_FILES["file"]) || $_FILES["file"]["error"] === UPLOAD_ERR_NO_FILE) {
        return ["success" => false, "message" => "Nessun file caricato"];
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/pizze/';
    $target_file = $uploadDir . basename($_FILES["file"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check === false) {
        return ["success" => false, "message" => "Il file caricato non è un'immagine valida"];
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        return ["success" => false, "message" => "Il file esiste già"];
    }

    // Check file size
    if ($_FILES["file"]["size"] > 500000) {
        return ["success" => false, "message" => "Il file è troppo grande. Dimensione massima: 500KB"];
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        return ["success" => false, "message" => "Solo i formati JPG, JPEG, PNG e GIF sono consentiti"];
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        return ["success" => true, "message" => "File caricato con successo", "path" => $target_file];
    } else {
        return ["success" => false, "message" => "Errore durante il caricamento del file"];
    }
}