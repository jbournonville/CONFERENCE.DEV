<?php

/**
 * Created by IntelliJ IDEA.
 * User: julien
 * Date: 03/01/2017
 * Time: 09:12
 */
class Halls extends Table
{

    protected static $table = 'halls';
    private static $picturePath = "img/halls/";

    /**
     * Function to create a hall in the database
     * @param $data
     * @param $db Database
     */
    public static function create($data, $db)
    {
        $params = [];
        foreach ($data as $item) {
            $params[] = $item;
        }

        $query = "INSERT INTO halls (hall, building, picture, capacity) VALUE (?, ?, ?, ?)";
        $db->query($query, $params);
    }

    /**
     * Function to update data of a hall in the database
     * @param $data
     * @param $db Database
     */
    public static function updateData($data, $db)
    {
        $params = [];
        foreach ($data as $item) {
            $params[] = $item;
        }
        $id = $data['id'];

        $hall = self::getById($db, $id);

        $query = "UPDATE halls SET hall = ?, building = ?, capacity = ? WHERE id = ? ";
        $db->query($query, $params);

        if ($hall->hall != $data['hall']) {
            if (self::havePicture($db, $id)) {
                self::renamePicture($db, $id, $hall->picture, $data['hall']);
            }
        }
    }

    /**
     * Function to get building name of a hall
     * @param $db Database
     * @return mixed
     */
    public static function getBuildings($db)
    {
        $query = "SELECT building FROM halls GROUP BY building";
        $res = $db->query($query);
        return $res->fetchAll();
    }

    /**
     * Function to know if a hall have a picture set in the database
     * @param $db Database
     * @param $idHall
     * @return bool
     */
    public static function havePicture($db, $idHall)
    {
        $query = "SELECT picture FROM halls WHERE id = ?";
        $res = $db->query($query, [$idHall])->fetch();
        if ($res->picture == null) {
            return false;
        }

        return true;
    }

    /**
     * Function to set a hall picture in the database
     * @param $db Database
     * @param $idHall
     * @param $picture
     */
    public static function setPicture($db, $idHall, $picture)
    {
        $query = "UPDATE halls SET picture = ? WHERE id = ?";
        $db->query($query, [$picture, $idHall]);
    }

    /**
     * Function to upload a picture to the server in the img/halls folder
     * @param $file
     * @param $idHall
     * @param $db Database
     */
    public static function uplaodPicture($file, $idHall, $db)
    {
        $sess = Session::getInstance();


        $origin_file = basename($file['name']);
        $imageFileType = pathinfo($origin_file, PATHINFO_EXTENSION);

        $target_file = self::$picturePath . strtolower($_POST['hall']) . "." . $imageFileType;

        $uploadOk = 1;
// Check if file already exists
        if (file_exists($target_file)) {
            if (unlink($target_file)) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }
// Check file size
        if ($file["size"] > 800000) {
            $sess->setFlashMessage("Le fichier est trop volumineux (< 800 KB)", 'fail');
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $sess->setFlashMessage("Seuls les fichiers au format JPG, JPEG & PNG sont autorisés", 'fail');
            $uploadOk = 0;
        }

// Delete old file if needed
        if (self::havePicture($db, $idHall)) {
            $hall = self::getById($db, $idHall);
            if (file_exists($hall->picture)) {
                unlink(realpath($hall->picture));
            }
        }

// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $sess->setFlashMessage("Transfert annulé", 'fail');
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                self::setPicture($db, $idHall, $target_file);
                $sess->setFlashMessage("Le fichier " . basename($file["name"]) . " a été téléchargé", 'success');
            } else {
                $sess->setFlashMessage("Il y a eut un problème lors du transfert", 'fail');
            }
        }
    }

    /**
     * Function to rename a hall picture if the hall is renamed
     * @param $db Database
     * @param $idHall
     * @param $oldFile
     * @param $newName
     */
    private static function renamePicture($db, $idHall, $oldFile, $newName)
    {

        $imageFileType = pathinfo($oldFile, PATHINFO_EXTENSION);

        $newPath = self::$picturePath . strtolower($newName) . "." . $imageFileType;

        rename($oldFile, $newPath);

        self::setPicture($db, $idHall, $newPath);
    }
}

