<?php

class Events extends Table
{
    protected static $table = "events";

    /**
     * Function that returns all events to come
     * @param $db Database
     * @return mixed
     */
    public static function getAllNextEvents($db)
    {
        $req = $db->query("SELECT * FROM events WHERE eventDate >= CURRENT_DATE");
        return $req->fetchAll();
    }

    /**
     * Function that returns next events to come
     * @param $db Database
     * @return mixed
     */
    public static function getNextEvent($db)
    {
        $req = $db->query("SELECT * FROM events WHERE eventDate >= CURRENT_DATE ORDER BY eventDate;");
        return $req->fetch();
    }

    /**
     * Function to update data in the database
     * @param $data
     * @param $db Database
     */
    public static function updateData($data, $db)
    {
        $params = [];
        foreach ($data as $item) {
            $params[] = $item;
        }

        $query = "UPDATE events SET event = ?, address = ?, description = ?, eventDate = ?, duration = ? , nbSlotsPerDay = ?, nbSpeakerMaxByModule = ?, bookingOpen = ? WHERE id = ? ";
        $db->query($query, $params);
    }

    /**
     * Function to create a new event in the database
     * @param $data
     * @param $db Database
     */
    public static function create($data, $db)
    {
        $params = [];
        foreach ($data as $item) {
            $params[] = $item;
        }

        $query = "INSERT INTO events (event, address, description, eventDate, duration, nbSlotsPerDay, nbSpeakerMaxByModule) VALUE (?, ?, ?, ?, ?, ?, ?)";
        $db->query($query, $params);
    }

    /**
     * Function that return days of the conference
     * @param $db Database
     * @param $idEvent
     * @return array
     */
    public static function getEventDays($db, $idEvent)
    {
        $event = $db->query("SELECT * FROM event_booking.events WHERE id = ?", [$idEvent])->fetch();
        $startDate = $event->eventDate;
        $nbDays = $event->duration;
        $days = [];

        for ($i = 0; $i < $nbDays; $i++) {
            $date = new DateTime($startDate);
            $date->add(new DateInterval("P" . $i . "D"));
            $days[] = $date->format("Y-m-d");
        }
        return $days;
    }

    /**
     * Function that returns the number of slot configured for the event
     * @param $db Database
     * @param $idEvent
     * @return mixed
     */
    public static function getNbSlots($db, $idEvent)
    {
        return $db->query("SELECT nbSlotsPerDay FROM events WHERE id = ?", [$idEvent])->fetchColumn();
    }

    /**
     * Function that returns the number of speakers configured for the event
     * @param $db Database
     * @param $idEvent
     * @return mixed
     */
    public static function getNbSpeakers($db, $idEvent)
    {
        return $db->query("SELECT nbSpeakerMaxByModule FROM events WHERE id = ?", [$idEvent])->fetchColumn();
    }

    /**
     * Function that returns if a s user already subscribe to the event
     * @param $db Database
     * @param $idEvent
     * @param $idUser
     * @return bool
     */
    public static function isUserAlreadyBookedEvent($db, $idEvent, $idUser)
    {
        $query = "SELECT * FROM sessions_auditors WHERE idUser = ? AND idSession IN (SELECT id FROM sessions WHERE idSlot IN (SELECT  id FROM slots WHERE idEvent = ? ))";

        if ($db->query($query, [$idUser, $idEvent])->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Function that returns the main session of an event
     * @param $db Database
     * @param $idEvent
     * @return mixed
     */
    public static function getMainSession($db, $idEvent)
    {
        $query = "SELECT sessions.id FROM sessions INNER JOIN slots ON sessions.idSlot = slots.id  WHERE sessions.mainSession = TRUE AND slots.idEvent = ?";
        return $db->query($query, [$idEvent])->fetchColumn();
    }

    /**
     * Function that returns slots of an event
     * @param $db Database
     * @param $idEvent
     * @return mixed
     */
    public static function getSlots($db, $idEvent)
    {
        $query = "SELECT slot FROM slots WHERE idEvent = ?";
        return $db->query($query, [$idEvent])->fetchColumn();
    }

    /**
     * Function to know if the event is well configured
     * @param $db Database
     * @param $idEvent
     * @return bool
     */
    public static function isConfigurationValid($db, $idEvent)
    {
        if (!Events::getMainSession($db, $idEvent)) {
            return false;
        }
        if (!Events::getSlots($db, $idEvent)) {
            return false;
        }
        return true;
    }

    /**
     * Function to know if subscriptions are open for the event
     * @param $db Database
     * @param $idEvent
     * @return mixed
     */
    public static function isBookingOpen($db, $idEvent)
    {
        $query = "SELECT bookingOpen FROM events WHERE id = ?";
        return $db->query($query, [$idEvent])->fetchColumn();
    }

    /**
     * Function to know if subscriptions are open for the event
     * @param $db Database
     * @param $idEvent
     * @param $idUser
     */
    public static function subscribeUser($db, $idEvent, $idUser)
    {
        $mainSession = Events::getMainSession($db, $idEvent);
        $query = "INSERT INTO sessions_auditors (idSession, idUser) VALUES (?,?)";
        $db->query($query, [$mainSession, $idUser]);
    }

    /**
     * Function that returns slots that are available for the event
     * @param $db Database
     * @param $idEvent
     * @return mixed
     */
    public static function getAvailableSlots($db, $idEvent)
    {
        $req = $db->query("
            SELECT slots.id, slot
            FROM slots INNER JOIN events
            ON slots.idEvent = events.id
            WHERE events.id = ?
        ", [$idEvent]);

        return $req->fetchAll();
    }

}