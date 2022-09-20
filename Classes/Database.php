<?php

namespace Classes;

/**
 * Class Database
 */
class Database
{

    private $db = null;

    public function __construct()
    {
        try {
            global $hostname, $port, $database, $username, $password;
            $this->db = new \PDO("mysql:host=$hostname;port=$port;dbname=$database", $username, $password);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Connect to the MySQL database
     * @return \PDO
     */
    public function connet()
    {
        return $this->db;
    }
}
