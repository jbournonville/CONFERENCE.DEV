<?php

/**
 * Created by IntelliJ IDEA.
 * User: julien
 * Date: 26/12/2016
 * Time: 12:58
 */
class Sessions extends Table
{

    protected static $table = "sessions";

    /**
     * Function that returns sessions for the slot
     * @param $db Database
     * @param $idSlot
     * @return mixed
     */
    public static function getSessionsBySlots($db, $idSlot)
    {
        $query = "SELECT * FROM sessions WHERE idSlot = ?";
        return $db->query($query, [$idSlot])->fetchAll();
    }

    /**
     *Function to know if a module is already use in the slot
     * @param $db Database
     * @param $idModule
     * @param $idSlot
     * @return bool
     */
    public static function isModuleAlreadyUse($db, $idModule, $idSlot)
    {
        $res = $db->query("SELECT modules.id, modules.module FROM modules INNER JOIN sessions ON modules.id = sessions.idModule WHERE idSlot = ? AND idModule = ?", [$idSlot, $idModule]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Function to know if the hall is already used in the slot
     * @param $db Database
     * @param $idModule
     * @param $idSlot
     * @return bool
     */
    public static function isHallAlreadyUse($db, $idModule, $idSlot)
    {
        $res = $db->query("SELECT halls.id, halls.halls FROM halls INNER JOIN sessions ON halls.id = sessions.idHall WHERE idSlot = ? AND idModule = ?", [$idSlot, $idModule]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Function to know if a speaker is already used in the slot
     * @param $db Database
     * @param $idSpeaker
     * @param $idSlot
     * @return bool
     */
    public static function isSpeakerAlreadyUse($db, $idSpeaker, $idSlot)
    {
        $res = $db->query("SELECT idUser FROM sessions_speakers INNER JOIN sessions ON idSession = sessions.id WHERE idUser = ? AND idSlot = ?",
            [$idSpeaker, $idSlot]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Function to know if the main session have been defined
     * @param $db Database
     * @param $idEvent
     * @return bool
     */
    public static function isMainSessionAlreadyDefine($db, $idEvent)
    {
        $res = $db->query("SELECT mainSession FROM sessions INNER JOIN slots ON sessions.idSlot = slots.id WHERE mainSession = TRUE AND slots.idEvent = ?",
            [$idEvent]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Function that returns speakers of the session
     * @param $db Database
     * @param $idSession
     * @return mixed
     */
    public static function speakerSession($db, $idSession)
    {
        return $db->query("SELECT idUser AS idSpeaker FROM sessions_speakers WHERE idSession = ? ", [$idSession])->fetchAll();
    }

    /**
     * Function to update data of a session in the database
     * @param $db Database
     * @param $data
     */
    public static function updateData($db, $data)
    {
        $params = [];
        foreach ($data as $item) {
            $params[] = $item;
        }

        $query = "UPDATE sessions SET idModule = ?, idHall = ?, mainSession = ? WHERE id = ? ";
        $db->query($query, $params);
    }

    /**
     * Function to create a session in the database
     * @param $db Database
     * @param $data
     */
    public static function create($db, $data)
    {
        foreach ($data as $item) {
            $params[] = $item;
        }

        $query = "INSERT INTO sessions (idModule, idHall, idSlot)VALUES (?, ?, ?)";
        $db->query($query, $params);
    }

    /**
     * Function to get speaker name
     * @param $db Database
     * @param $idSpeaker
     * @return string
     */
    public static function getSpeakerName($db, $idSpeaker)
    {
        $query = "SELECT name, surname FROM users WHERE id = ?";

        $res = $db->query($query, [$idSpeaker])->fetch();

        $speaker = $res->surname . " " . $res->name;

        return $speaker;
    }

    /**
     * Function to get all speakers
     * @param $db Database
     * @return mixed
     */
    public static function getAllSpeaker($db)
    {
        $query = "SELECT * FROM users WHERE idRole = 2";

        return $db->query($query)->fetchAll();
    }

    /**
     * Function to delete all speakers of a session
     * @param $db Database
     * @param $idSession
     */
    public static function deleteSessionSpeakers($db, $idSession)
    {
        $db->query("DELETE FROM sessions_speakers WHERE idSession = ?", [$idSession]);
    }

    /**
     * Function to add a speaker to a session
     * @param $db Database
     * @param $idSpeaker
     * @param $idSession
     */
    public static function createSessionSpeaker($db, $idSpeaker, $idSession)
    {
        $db->query("INSERT INTO sessions_speakers (idUser, idSession) VALUES (?,?)", [$idSpeaker, $idSession]);
    }

    /**
     * Function to know if the user have already subscribe to the session
     * @param $db Database
     * @param $idUser
     * @param $idSession
     * @return bool
     */
    public static function isUserBookThisSession($db, $idUser, $idSession)
    {
        $query = "SELECT * FROM sessions_auditors WHERE idUser = ? AND idSession = ?";
        $res = $db->query($query, [$idUser, $idSession]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }
    
    /**
     * Function to subscribe a user to the session
     * @param $db Database
     * @param $idUser
     * @param $idSession
     */
    public static function createUserSession($db, $idUser, $idSession)
    {
        $query = "INSERT INTO sessions_auditors (idSession, idUser) VALUES (?, ?)";
        $db->query($query, [$idSession, $idUser]);
    }

    /**
     * Function to delete all users of a session
     * @param $db Database
     * @param $idUser
     */
    public static function deleteUserSessions($db, $idUser)
    {
        $db->query("DELETE FROM sessions_auditors WHERE idUser = ?", [$idUser]);
    }

    /**
     * Function to get all session of a user from a specific slot
     * @param $db Database
     * @param $idSlot
     * @param $idUser
     * @return mixed
     */
    public static function getUserSlotSession($db, $idSlot, $idUser)
    {
        $query = "SELECT * FROM sessions_auditors LEFT JOIN  sessions ON sessions_auditors.idSession = sessions.id WHERE idSlot = ? AND idUser = ?";
        return $db->query($query, [$idSlot, $idUser])->fetch();

    }

    /**
     * Function to get all session of a speaker from a specific slot
     * @param $db Database
     * @param $idSlot
     * @param $idUser
     * @return mixed
     */
    public static function getSpeakerSlotSession($db, $idSlot, $idUser)
    {
        $query = "SELECT * FROM sessions_speakers LEFT JOIN  sessions ON sessions_speakers.idSession = sessions.id WHERE idSlot = ? AND idUser = ?";
        return $db->query($query, [$idSlot, $idUser])->fetch();

    }


    /**
     * Function to get all session of a user
     * @param $db Database
     * @param $idUser
     * @return mixed
     */
    public static function getUserSessions($db, $idUser)
    {
        $query = "SELECT * FROM  (SELECT *, 'auditor' AS type FROM sessions_auditors   UNION   SELECT * , 'speaker' AS type FROM sessions_speakers)  AS t INNER JOIN sessions ON idSession = sessions.id INNER JOIN slots ON sessions.idSlot = slots.id    WHERE idUser = ? ORDER BY slot ASC ";
        return $db->query($query, [$idUser])->fetchAll();
    }

    /**
     * Function to know is a session is full regarding hall's capacity
     * @param $db Database
     * @param $idSession
     * @return bool
     */
    public static function isSessionFull($db, $idSession)
    {
        $query = "SELECT capacity FROM halls INNER JOIN sessions ON halls.id = idHall WHERE sessions.id = ?";
        $max = $db->query($query, [$idSession])->fetchColumn();

        $query = "SELECT COUNT(idSession) AS nb FROM sessions_auditors WHERE idSession = ?";
        $nb = $db->query($query, [$idSession])->fetchColumn();

        if ($nb >= $max) {
            return true;
        }

        return false;

    }

    /**
     * Function to get the number of user that subscribe to the session
     * @param $db Database Database
     * @param $idSession
     * @return mixed
     */
    public static function getNbUserSubscribe($db, $idSession)
    {
        return $db->query("SELECT COUNT(idSession) AS nb FROM sessions_auditors WHERE idSession = ? ", [$idSession])->fetchColumn();
    }

    /** Function to know if the speaker if already planned in the slot to an other session
     * @param $db Database
     * @param $idUser
     * @param $idSlot
     * @return bool
     */
    public static function isSpeakerBusy($db, $idUser, $idSlot)
    {
        $query = "SELECT * FROM sessions_speakers INNER JOIN sessions ON sessions_speakers.idSession = sessions.id WHERE idUser = ? AND idSlot = ?";
        $res = $db->query($query, [$idUser, $idSlot]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }

}