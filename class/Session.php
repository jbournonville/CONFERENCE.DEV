<?php

/**
 * Class Session
 * Class that manage web browser session
 */
class Session
{

    private static $instance;

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Function to get the instance of session (singleton)
     * @return Session
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    /**
     * Function to set session parameter
     * @param $key
     * @param $value
     */
    public function setParams($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Function to get session parameter
     * @param $key
     * @return bool
     */
    public function getParams($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return false;
    }

    /**
     * Function to unset session parameter
     * @param $key
     */
    public function unsetParam($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Function to set flash messages to the session
     * @param $message
     * @param string $type
     */
    public function setFlashMessage($message, $type = 'std')
    {
        $_SESSION['flashMessage'][$type] = $message;
    }

    /**
     * Function to know if there are messages in the session
     * @return bool
     */
    public function hasFlashMessage()
    {
        return isset($_SESSION['flashMessage']);
    }

    /**
     * Function to get flash messages from the session
     * @return mixed
     */
    public function getFlashMessage()
    {
        $flashMessages = $_SESSION['flashMessage'];
        unset($_SESSION['flashMessage']);
        return $flashMessages;
    }
}