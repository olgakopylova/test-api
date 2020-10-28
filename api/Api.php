<?php
require_once 'core/DB.php';
require_once 'core/Logs.php';
abstract class Api
{
    protected $method;
    public $uri;
    public $params;
    public $action;
    protected $db;
    private $token_id='4a27ca7cef330c64c8402f6422cf198e';

    public function __construct(){
        $this->db = new DB();
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        // Параметры запроса
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $postdata = file_get_contents('php://input');
            $this->params = json_decode($postdata, true);
        }else{
            $this->params = $_REQUEST;
        }

        $this->uri = explode('/', strtok($_SERVER['REQUEST_URI'], '?'));

        // Определение тима метода запроса
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER))
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE')
                $this->method = 'DELETE';

    }

    public function start(){
        if(isset($this->params['token_id'])&&$this->params['token_id']==$this->token_id){
            $this->action = $this->getAction();

            if (method_exists($this, $this->action)) {
                return $this->{$this->action}();
            } else {
                Logs::logger(1);
                throw new RuntimeException('Invalid Method', 405);
            }
        }else{
            return $this->response(['error' => 'No token_id'],200);
        }
    }

    public function getAction(){
        switch ($this->method){
            case "POST":
                if(array_search('generate',$this->uri)!==false)
                    return 'create';
                break;
            case "GET":
                if(array_search('by-id',$this->uri)!==false)
                    return 'getById';
                break;
            case "DELETE":
                if(array_search('remove',$this->uri)!==false)
                    return 'delete';
                break;
            default:
                Logs::logger(2);
                break;
        }
        return "none";
    }

    public function getStatus($code){
        $status=[
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Invalid Method',
            500 => 'Internal Server Error'
        ];
        return isset($status[$code])?$status[$code]:$status[500];
    }

    protected function response($data, $status = 500) {
        header("HTTP/1.1 " . $status . " " . $this->getStatus($status));
        return json_encode($data);
    }

    abstract protected function create();
    abstract protected function getById();
    abstract protected function delete();
}