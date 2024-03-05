<?php

namespace App\Lib\Database;

use \mysqli;
use App\Exceptions\Database\DatabaseConnection as ConnectionError;

class DatabaseConnection{

    private $db_host = DB_HOST;
    private $db_name = DATABASE;
    private $db_user = DB_USER;
    private $db_password = DB_PASSWORD;
    private $connection;

    function __construct() {
        $this->connection = new mysqli(
            $this->db_host,
            $this->db_user,
            $this->db_password,
            $this->db_name
        );
    }

    public function checkDatabaseConnection(): bool{
        if ($this->connection->connect_errno) {
            throw new ConnectionError('Unable to connect to database', INTERNAL_SERVER_ERROR);
        }
        return true;
    }
}