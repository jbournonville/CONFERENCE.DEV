<?php

/**
 * Created by IntelliJ IDEA.
 * User: julien
 * Date: 01/12/2016
 * Time: 20:42
 */
class App
{

    private static $dbUser = 'root';
    private static $dbPassword = 'root';
    private static $dbHost = 'localhost';
    private static $dbName = 'event_booking';
    private static $database = null;

    /**
     * Function to get the connnection to the database
     * @return Database|null
     */
    public static function dbConnection()
    {
        if (!self::$database) {
            self::$database = new Database(self::$dbUser, self::$dbPassword, self::$dbName, self::$dbHost);
        }
        return self::$database;
    }

    /**
     * Function to redirect to an other page
     * @param $page
     * @param array $params
     */
    public static function redirect($page, $params = [])
    {
        if (empty($params)) {
            header("location: $page.php");
            exit();
        } else {
            $string = "";
            foreach ($params as $key => $value) {
                $string .= "$key=$value";
            }
            header("location: $page.php?$string");
        }
        exit;
    }

    /**
     * Function to register an new user to the database
     * @param $db Database
     * @param $username Users->username
     * @param $email Users->email
     * @param $password Users->password
     */
    public static function signUpUser($username, $email, $password, $db)
    {
        $passwordEncrypt = password_hash($password, PASSWORD_BCRYPT);
        $db->query("INSERT INTO users (username, email, password, idRole) VALUES (?,?,?,?)", [$username, $email, $passwordEncrypt, 1]);
    }

    /**
     * Function to redirect to the sing in page if he is not logged
     * @param $session Session
     */
    public static function needAuth($session)
    {
        if (!$session->getParams('user')) {
            App::redirect('signin');
            exit();
        }
    }

    /**
     * Function to redirect to the index page if a user access to the page without admin rights
     * @param $user Users
     */
    public static function needAdmin($user)
    {
        if (!$user->isAdmin()) {
            App::redirect('index');
            exit();
        }
    }

    /**
     *Function to logout from the app
     */
    public static function logout()
    {
        Session::getInstance()->unsetParam('user');
    }

    /**
     * Function to show flash message if some are set in session
     * @param $session Session
     */
    public static function showFlashMessage($session)
    {
        if ($session->hasFlashMessage()) {
            foreach ($session->getFlashMessage() as $type => $message) {
                echo "<div class='alert alert-$type'>$message</div>";
            }
        }
    }

    /**
     * Return a date time value to a date only format
     * @param $date
     * @return false|string
     */
    public static function dateFormat($date)
    {
        $datec = date_create($date);
        return date_format($datec, 'd/m/Y');
    }

    /**
     * Return a date time value to a hour only format
     * @param $date
     * @return false|string
     */
    public static function hourFormat($date)
    {
        $datec = date_create($date);
        return date_format($datec, 'H:i');
    }

    /**
     * Return a date time value to a date and hour format
     * @param $date
     * @return false|string
     */
    public static function dateHourFormat($date)
    {
        $datec = date_create($date);
        return date_format($datec, 'd/m/Y H:i');
    }

    /**
     * Return an excerpt of a string with a specified length
     * @param $string
     * @param $length
     * @return string
     */
    public static function excerpt($string, $length)
    {
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length);
            $string = substr($string, 0, strrpos($string, " "));
            $end = " ...";
            $string = $string . $end;
        }
        return $string;
    }
}

