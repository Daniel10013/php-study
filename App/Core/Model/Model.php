<?php

namespace App\Core\Model;

use mysqli;
use mysqli_result;
use mysqli_sql_exception;
use App\Lib\DefaultResponses;
use App\Core\Exceptions\BaseException;
use App\Lib\Database\DatabaseConnection;

class Model{

    private mysqli $connection; 
    protected string $table;
    protected int $lastId;
    private bool $isToAddQuote = false;

    public function __construct(){
        if(empty($this->table) == true){
            $className = $this::class;
            throw new BaseException("Missing table name on model", BAD_REQUEST, "model_config");
        }
        $this->connection = (new DatabaseConnection())->getConnection();
    }

    public function findAll(): array{
        $query = "SELECT * FROM {$this->table}";
        return $this->rawQuery($query);
    }

    public function find(int $id): array{
        $query = "SELECT * FROM {$this->table} WHERE `id` = ?";
        return $this->rawQuery($query, [$id]);
    }

    public function where(string $field, mixed $value): array {
        $query = "SELECT * FROM {$this->table} WHERE `$field` = '?'";
        return $this->rawQuery($query, [$value]);
    }

    public function first(): array{
        $query = "SELECT * FROM {$this->table} LIMIT 1";
        return $this->rawQuery($query);
    }

    public function delete(int $id): bool{
        $query = "DELETE FROM {$this->table} WHERE `id` = ?";
        return $this->rawQuery($query, $id);
    }

    public function create($dataToCreate): bool{
        if(empty($dataToCreate)){
            DefaultResponses::badRequest("Invalid create data!");
        }

        $columns = array_keys($dataToCreate);
        $values = array_values($dataToCreate);

        $this->isToAddQuote = true;
        $columns = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($values), "?"));

        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->rawQuery($query, $values);
    }

    public function update(array $data, int $id): bool {
        if(empty($data) == true || empty($id) == true){
            DefaultResponses::badRequest("Invalid update data!");
        }

        $columns = array_keys($data);
        $values = array_values($data);
        
        $set = "";
        foreach($columns as $key => $column){
            $columns[$key] = "{$column} = ?";
        }
        
        $set = implode(", ", $columns);
        
        $this->isToAddQuote = true;
        $query = "UPDATE {$this->table} SET $set WHERE `id` = ?";
        $values[] = $id;
        return $this->rawQuery($query, $values);
    }

    public function exists(mixed $value, $field = 'id'): bool{
        $query = "SELECT * FROM {$this->table} WHERE `{$field}` = ?";
        $result = $this->rawQuery($query, $value);
        return count($result) > 0;
    }

    public function join(string $table): array{
        $query = "SELECT * FROM {$this->table} as ac JOIN {$table} as t ON ac.id = t.id ";
        return $this->rawQuery($query);
    }

    protected function rawQuery(string $query, mixed $paramData = []): array | bool {
        try{
            $paramData = is_array($paramData) ? $paramData : [$paramData];
        
            $bindedQuery = $this->getBindedQuery($query, $paramData);
            $queryResult = $this->connection->query($bindedQuery);
            $this->setLastId();
            $this->connection->close();

            if(is_bool($queryResult)){
                return $queryResult;
            }
    
            return $this->getData($queryResult);

        } catch(mysqli_sql_exception $e) {
            DefaultResponses::badRequest($e->getMessage());
        }
    }

    private function getData(mysqli_result $queryResult): array{
        $resultFetch = $queryResult->fetch_all(MYSQLI_ASSOC);
        $databaseData = $resultFetch == NULL ? [] : $resultFetch;
        if(sizeof($databaseData) == 1){
            return $databaseData[0];
        }
        return $databaseData;
    }

    private function getBindedQuery(string $query, array $data):string {
        if(empty($data) == true){
            return $query;
        }

        $escapedData = $this->getEscapedParams($data);
        if(sizeof($escapedData) == 1){
            return str_replace("?", $escapedData[0], $query);
        }

        foreach($escapedData as $escapedValue){
            $bindPosition = strpos($query, "?");
            $query = substr_replace($query, $escapedValue, $bindPosition, 1);
        }
        return $query;
    }

    private function getEscapedParams(array $params): array{
        $dataToEscape = $params;
        foreach($dataToEscape as $key => $param){
            if($this->isToAddQuote == true && is_numeric($param) == false){
                $dataToEscape[$key] = "'{$this->connection->real_escape_string($param)}'";
                continue;
            }
            $dataToEscape[$key] = $this->connection->real_escape_string($param);
        }
        return $dataToEscape;
    }

    private function setLastId():void {
        if($this->connection->insert_id != 0){
            $this->lastId = $this->connection->insert_id;
        }
    }

    public function getLastId(): int{
        return $this->lastId;
    }
}