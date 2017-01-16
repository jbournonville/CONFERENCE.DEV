<?php

/**
 * Created by IntelliJ IDEA.
 * User: julien
 * Date: 26/12/2016
 * Time: 11:16
 */
class Table
{
    protected static $table;

    /**
     * Function to get all data from a table
     * @param $db Database
     * @return mixed
     */
    public static function getAll($db)
    {
        $req = $db->query("SELECT * from ". static::$table);
        return $req->fetchAll();
    }

    /**
     * Function to delete an element from a table
     * @param $id
     * @param $db Database
     */
    public static function delete($id, $db)
    {
        $query = "DELETE FROM ". static::$table . " WHERE id = ?";
        $db->query($query, [$id]);
    }

    /**
     * Function to get an element from a table
     * @param $db Database
     * @param $id
     * @return mixed
     */
    public static function getById($db, $id)
    {
        $req = $db->query("SELECT * from " . static::$table . " WHERE id = ?", [$id]);
        return $req->fetch();
    }

}