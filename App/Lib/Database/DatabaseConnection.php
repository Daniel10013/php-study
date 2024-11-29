<?php

namespace App\Lib\Database;

use \mysqli;
use mysqli_sql_exception;
use App\Lib\DefaultResponses;
use App\Exceptions\Database\DatabaseConnection as ConnectionError;

class DatabaseConnection{

    private string $db_host = DB_HOST;
    private string $db_name = DATABASE;
    private string $db_user = DB_USER;
    private string $db_password = DB_PASSWORD;
    private mysqli $connection;

    function __construct() {
        try{
            $this->connection = new mysqli(
                $this->db_host,
                $this->db_user,
                $this->db_password,
                $this->db_name,
            );
        }catch(mysqli_sql_exception $e){
            DefaultResponses::internalServerError($e->getMessage());
        }
    }

    public function getConnection(){
        return $this->connection;
    }

    public function checkDatabaseConnection(): bool{
        if ($this->connection->connect_errno) {
            throw new ConnectionError('Unable to connect to database', INTERNAL_SERVER_ERROR);
        }
        $this->connection->close();
        return true;
    }
}