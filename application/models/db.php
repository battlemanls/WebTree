<?php

class DB{

    private static $db;
    public static $host = 'host=localhost';
    public static $database = 'dbname=TreesDB';
    public static $user = 'user=postgres';
    public static $pass = 'password=12345677';
    public static $port = "port=5432";

    private function __construct()
    {
    }

    private static function get_connect(){
       /* static::$db = mysqli_connect(static::$host, static::$user, static::$pass, static::$database) or die("Ошибка " . mysqli_error(static::$db));*/
        static::$db = pg_connect(static::$host . " " . static::$port . " " . static::$database . " " .  static::$user . " " . static::$pass);
    }

    public static function get_db(){
        if(static::$db == null){
            $db = new DB();
            $db::get_connect();
            return static::$db;
        }
        else{
            return static::$db;
        }

}

}