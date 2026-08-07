<?php

class DbRepository implements IDbManager{

    

    private IDbManager $db;

    public function __construct(){
        if(strtolower($_SESSION["DbType"]) == "mysql"){
            $this->db = new dbManager();
        }
        if(strtolower($_SESSION["DbType"]) == "Postgre"){
            //$db = new dbManagerPostgre();
        }
        if(strtolower($_SESSION["DbType"]) == "SqlServer"){
            //$db = new dbManagerSqlServer();
        }
    }

    public function OpenConnection():?PDO{
        return $this->db->OpenConnection();
    }
    public function CloseConnection(){
        $this->db->CloseConnection();
    }
    public function IsConnected():bool{
        return $this->db->IsConnected();
    }
    public function Delete(string $sql, ?array $ar=null):bool{
        return $this->db->Delete($sql, $ar);
    }
    public function Insert(string $sql, ?array $ar=null):bool{
        return $this->db->Insert($sql, $ar);
    }
    public function Update(string $sql, ?array $ar=null):bool{
        return $this->db->Update($sql, $ar);
    }
    public function Select(string $sql, ?array $ar=null):?array{
        return $this->db->Select($sql, $ar);
    }


}