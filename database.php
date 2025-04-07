<?php

class Database
{

    private $host = 'localhost';
    private $dbname = 'Prodavnica';
    private $username = 'root';
    private $password = '';

    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {

            echo "Greška pri povezivanju na bazu: " . $this->conn->connect_error;
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
