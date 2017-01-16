<?php

class Modules extends Table
{
    protected static $table = "modules";

    /**
     * Function to create a module in the database
     * @param $data
     * @param $db Database
     */
    public static function create($data, $db)
    {
        $params = [];
        foreach ($data as $item) {
            $params[] = $item;
        }

        $query = "INSERT INTO modules (module, description) VALUE (?, ?)";
        $db->query($query, $params);
    }

    /**
     * Function to update module's data in the database
     * @param $data
     * @param $db Database
     */
    public static function updateData($data, $db)
    {
        $params = [];
        foreach ($data as $item) {
            $params[] = $item;
        }

        $query = "UPDATE modules SET module = ?, description = ? WHERE id = ? ";
        $db->query($query, $params);
    }

    /**
     * Function to know if the user have already subscribe to the session
     * @param $db Database
     * @param $idUser
     * @param $idModule
     * @return bool
     */
    public static function isUserAlreadyBookThisModule($db, $idUser, $idModule)
    {
        $query = "SELECT * FROM sessions_auditors LEFT JOIN sessions ON sessions_auditors.idSession = sessions.id WHERE idUser = ? AND idModule = ?";
        $res = $db->query($query, [$idUser, $idModule]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }


}