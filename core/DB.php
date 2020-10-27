<?php

class DB
{
    private $host = 'localhost';
    private $login = 'id15231219_olga';
    private $password = 'tIM?XsHj_]u?1nvx';
    private $database = 'id15231219_api';
    private $db;

    public function __construct(){
        try {
            $this->db = mysqli_connect($this->host, $this->login, $this->password, $this->database);
            $this->db->set_charset("utf8");
        } catch (Exception $ex) {
            var_dump($ex);
        }
    }

    public function query($sql){
        mysqli_begin_transaction($this->db);
        $result = mysqli_query($this->db, $sql) or die("Ошибка " . mysqli_error($this->db));
        $id=$this->getLastInserted();
        mysqli_commit($this->db);
        return $id;
    }

    public function delete($id, $tableName){
        mysqli_begin_transaction($this->db);
        $sql=sprintf("DELETE FROM %s WHERE id=%d", $tableName, $id);
        $result = mysqli_query($this->db, $sql) or die("Ошибка " . mysqli_error($this->db));
        mysqli_commit($this->db);
    }

    public function getOneData($sql){
        mysqli_begin_transaction($this->db);
        $result = mysqli_query($this->db, $sql) or die("Ошибка " . mysqli_error($this->db));
        $res=$result->fetch_assoc();
        mysqli_commit($this->db);
        return $res;
    }

    private function getLastInserted(){
        $sql="SELECT LAST_INSERT_ID() id";
        return $this->getOneData($sql)['id'];
    }

}