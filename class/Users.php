<?php

class Users extends Table
{

    protected static $table = "users";

    private $id;
    private $username;
    private $password;
    private $email;
    private $name;
    private $surname;
    private $idRole;
    private $themeChoice;

    private static $instance;

    /**
     * Users constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->id = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->password = $user->password;
        $this->name = $user->name;
        $this->surname = $user->surname;
        $this->idRole = $user->idRole;
        $this->themeChoice = $user->themeChoice;
    }

    /**
     * Function to get the user instance to put it on web browser session
     * @param $userId
     * @param $db Database
     * @return Users
     */
    public static function getInstance($userId, $db)
    {
        if (is_null(self::$instance)) {
            $user = $db->query("SELECT * FROM users WHERE id = ?", [$userId])->fetch();
            self::$instance = new Users($user);
        }
        return self::$instance;
    }

    /**
     * Function that returns Name and Surname
     * @return string
     */
    public function getLongName()
    {
        if (isset($this->name) && isset($this->name)) {
            return $this->surname . " " . $this->name;
        }
        return $this->username;
    }

    /**
     * Function to know if the user is an administrator
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->idRole == 1) {
            return true;
        }
        return false;
    }

    /**
     * Function to know if the user is a speaker
     * @return bool
     */
    public function isSpeaker()
    {
        if ($this->idRole == 2) {
            return true;
        }
        return false;
    }

    /**
     * Function to get user id
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Function to know the theme chosen by the user
     * @return mixed
     */
    public function getThemeChoice()
    {
        return $this->themeChoice;
    }

    /**
     * Function to know if the user profile is complete
     * @return bool
     */
    public function profilIsCompleted()
    {
        if ($this->name == null) {
            return false;
        }
        if ($this->surname == null) {
            return false;
        }

        return true;
    }

    /**
     * Function to update user data in the database
     * @param $params
     * @param $db Database
     */
    public static function updateData($params, $db)
    {

        $query = "UPDATE users SET username = ?, name = ?, surname = ?, email = ?, password = ? , idRole = ?, themeChoice = ? WHERE id = ? ";
        $db->query($query, $params);
    }

    /**
     * Function to reset user instance
     * @param $id
     * @param $db Database
     * @return Users
     */
    public function resetUser($id, $db)
    {
        self::$instance = null;
        return self::getInstance($id, $db);
    }

    /**
     * Function to get the role (string) of the user
     * @param $user
     * @param $db Database
     * @return mixed
     */
    public static function getRole($user, $db)
    {
        $req = $db->query("SELECT role FROM roles INNER JOIN users ON roles.id = users.idRole WHERE users.id = ? ", [$user]);
        $res = $req->fetch();
        return $res->role;
    }

    /**
     * Function to update lastActiveConnection field of the user in the database
     * @param $db Database
     * @param $idUser
     */
    public static function updateActiveConnection($db, $idUser)
    {
        $db->query("UPDATE users SET lastActiveConnection = NOW() WHERE id = ?", [$idUser]);
    }

    /**
     * Function to get the number of users registered in the database
     * @param $db Database
     * @return mixed
     */
    public static function getNbUser($db)
    {
        return $db->query("SELECT COUNT(id) AS nb FROM users")->fetchColumn();
    }

    /**
     * Function to get the number of speakers registered in the database
     * @param $db Database
     * @return mixed
     */
    public static function getNbSpeakers($db)
    {
        return $db->query("SELECT COUNT(id) AS nb FROM users WHERE idRole = 2")->fetchColumn();
    }

    /**
     * Function to know how many user are currently connected to the app
     * @param $db Database Database
     * @return mixed
     */
    public static function getNbUserConnected($db)
    {
        return $db->query("SELECT COUNT(id) AS nb FROM users WHERE lastActiveConnection > DATE_SUB(NOW(), INTERVAL 5 MINUTE)")->fetchColumn();
    }

    /**
     * Function to know how many user have been connected this day
     * @param $db Database
     * @return mixed
     */
    public static function getNbUserConnectedToday($db)
    {
        return $db->query("SELECT COUNT(id) AS nb FROM users WHERE EXTRACT(DAY FROM lastActiveConnection)  = EXTRACT(DAY FROM NOW())")->fetchColumn();
    }


}

