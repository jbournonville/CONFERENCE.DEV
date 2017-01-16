<?php


class Slots extends Table
{
    protected static $table = 'slots';

    /**
     * Function to get all slots by day and event
     * @param $db Database
     * @param $date
     * @param $idEvent
     * @return mixed
     */
    public static function getSlotsByDay($db, $date, $idEvent)
    {
        $query = "SELECT slots.id, slot FROM slots INNER JOIN events ON idEvent = events.id WHERE events.id = ? AND CAST(slot AS DATE) = ?
";
        return $db->query($query, [$idEvent, $date])->fetchAll();
    }

    /**
     * Function to update slots in the database
     * @param $db Database
     * @param $idSlot
     * @param $slot
     */
    public static function updateSlot($db, $idSlot, $slot)
    {
        $query = "UPDATE slots SET slot = ? WHERE id = ?";
        $db->query($query, [$slot, $idSlot]);
    }

    /**
     * Function to create slot in tha database
     * @param $db Database
     * @param $idEvent
     * @param $slot
     */
    public static function createSlot($db, $idEvent, $slot)
    {
        $query = "INSERT INTO slots (idEvent, slot) VALUES (?, ?)";
        $db->query($query, [$idEvent, $slot]);
    }

    /**
     * Function get the date of the slot
     * @param $db Database
     * @param $idSlot
     * @return mixed
     */
    public static function getSlotDay($db, $idSlot)
    {
        $query = "SELECT CAST(slot AS DATE) AS slot FROM slots WHERE id = ?";
        return $db->query($query, [$idSlot])->fetchColumn();
    }

    /**
     * Function to know if the slot contain the main session
     * @param $db Database
     * @param $idSlot
     * @return bool
     */
    public static function isMainSlot($db, $idSlot)
    {
        $query = "SELECT mainSession FROM sessions WHERE idSlot=? AND mainSession = TRUE";
        $res = $db->query($query, [$idSlot]);
        if ($res->fetch()) {
            return true;
        }
        return false;
    }
}