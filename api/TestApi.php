<?php
require_once 'Api.php';

class TestApi extends Api
{
    // Создание токена
    public function create(){
        $sql=sprintf("INSERT INTO tokens(token, `time`) VALUES ('%s', %d)", $this->generateToken(), time());
        return $this->response(['id' => $this->db->query($sql)], 200);
    }

    // Получение текена по ИД
    public function getById(){
        if(isset($this->params['id'])){
            $sql=sprintf("SELECT token FROM tokens WHERE id=%d", $this->params['id']);
            return $this->response(['token' => $this->db->getOneData($sql)['token']], 200);
        }else{
            return $this->response(['error' => 'No id'], 200);
        }
    }

    // Удаление записи токена из БД
    public function delete(){
        if(isset($this->params['id'])){
            $sql=sprintf("DELETE FROM tokens WHERE id=%d", $this->params['id']);
            $this->db->query($sql);
            return $this->response(['error'=>0], 200);
        }else{
            return $this->response(['error' => 'No id'], 200);
        }
    }

    // Генерация токена с помощью алгоритма хэширования MD5
    private function generateToken(){
        $token = md5(microtime() . 'salt' . time());
        return $token;
    }
}