<?php
require 'inc/app.php';

$sess = Session::getInstance();

$target_dir = "img/halls/";
$origin_file = basename($_FILES["pictureToUpload"]["name"]);
$imageFileType = pathinfo($origin_file,PATHINFO_EXTENSION);

$target_file = $target_dir . strtolower($_POST['hall']) .".". $imageFileType;

$uploadOk = 1;
// Check if file already exists
if (file_exists($target_file)) {
    if (unlink($target_file)){
        $uploadOk = 1;
    }else{
        $uploadOk = 0;
    }
}
// Check file size
if ($_FILES["pictureToUpload"]["size"] > 800000) {
    $sess->setFlashMessage("Le fichier est trop volumineux (< 800 KB)", 'fail');
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $sess->setFlashMessage("Seuls les fichiers au format JPG, JPEG & PNG sont autorisés", 'fail');
    $uploadOk = 0;
}

// Delete old file if needed
if (Halls::havePicture($db, $_POST['id'])){
    $hall = Halls::getById($db, $_POST['id']);
    if (file_exists($hall->picture)){
        unlink(realpath($hall->picture));
    }
}


// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $sess->setFlashMessage("Transfert annulé", 'fail');
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["pictureToUpload"]["tmp_name"], $target_file)) {
        Halls::setPicture($db, $_POST['id'], $target_file);
        $sess->setFlashMessage("Le fichier ". basename( $_FILES["pictureToUpload"]["name"]). " a été téléchargé", 'success');
    } else {
        $sess->setFlashMessage("Il y a eut un problème lors du transfert", 'fail');
    }
}