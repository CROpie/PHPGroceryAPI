<?php

class Dbh {

    private $host = "localhost";
    private $user = "root";
    private $pwd = "fishandchips";
    private $dbName = "phpGroceryAPI";

    private $connection;

    protected function connect() {

        if (!$this->connection) {
            $this->connection = new mysqli($this->host, $this->user, $this->pwd, $this->dbName);
            if ($this->connection->connect_errno) {
                throw new Exception("Failed to connect: " . $this->connection->connect_error);
            }
        }
        return $this->connection;
    }

    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

}
