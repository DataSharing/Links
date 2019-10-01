<?php

class db
{
    Public static $DB = null;

    public function __construct($username, $password, $hostname, $dbname)
    {
        global $DB;
        $connection = NULL;
        try {
            $connection = new PDO("mysql:host=" . $hostname . ";dbname=" . $dbname . ";charset=utf8", "" . $username . "", "" . $password . "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        $DB = $connection;
    }
}
