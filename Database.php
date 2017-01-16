<?php

class Database
{
    /**
     * Instance of PDO connection
     * @var PDO
     */
    private $connection;

    /**
     * Database constructor.
     * @param $login
     * @param $password
     * @param $databaseName
     * @param $host
     */
    public function __construct($login, $password, $databaseName, $host)
    {
        $this->connection = new PDO("mysql:dbname=$databaseName;host=$host", $login, $password);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->query('SET NAMES utf8');
    }

    /**
     * Function to make query to the Database with or without parameters
     * @param $query
     * @param bool $params
     * @return PDOStatement
     */
    public function query($query, $params = false)
    {
        if ($params) {
            $res = $this->connection->prepare($query);
            $res->execute($params);
        } else {
            $res = $this->connection->query($query);
        }
        return $res;
    }

    /**
     * Function to return the last id inserted int the database
     * @return string
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}