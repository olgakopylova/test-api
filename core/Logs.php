<?php


class Logs
{
    public static $file = "base.log";
    public static $dir = "logs/";
    public static $warnings=[
        0 => 'Frequent requests',
        1 => 'Referring to a non-existent method',
        2 => 'Undescribed request',
        3 => 'Non-existent request'
    ];

    // Запись логов
    public  static function logger($type){
        if (!file_exists(self::$dir))
            mkdir(self::$dir, 0777, true);

        $ip = self::getRealIpAddr();
        $date = date("H:i:s d.m.Y");
        $home = $_SERVER['REQUEST_URI'];

        $lines = $date." | ".'warn'." | ".self::$warnings[$type]." | ".$ip." | ".$home." |\r\n";

        file_put_contents(self::$dir.self::$file, $lines . "\n", FILE_APPEND);
    }

    public  static function getLogs(){
        $file=file("base.log");
        $data=[];
        for ($si=sizeof($file)-1; $si+1>sizeof($file)-50; $si--)
            array_push(explode("|", $file[$si]), $data);

        return json_encode();
    }

    public static function  getRealIpAddr() {
        // ОПределение IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip=$_SERVER['REMOTE_ADDR'];
        return $ip;
    }
}