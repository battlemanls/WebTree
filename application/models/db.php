<?php

/**
 *
 * db Class
 *
 */

class DB{

    /**
     * database object
     * @var DB static
     */
    private static $db;

    /**
     * host name
     * @var string static
     */
    public static $host = 'host=localhost';

    /**
     * database name
     * @var string static
     */

    public static $database = 'dbname=TreesDB';

    /**
     * user name
     * @var string static
     */

    public static $user = 'user=postgres';

    /**
     * password name
     * @var string static
     */

    public static $pass = 'password=12345677';

    /**
     * port number
     * @var string static
     */

    public static $port = "port=5432";

    private function __construct()
    {
    }

    /**
     * connect to db and set object DB in var db
     */

    private static function get_connect(){
        static::$db = pg_connect(static::$host . " " . static::$port . " " . static::$database . " " .  static::$user . " " . static::$pass);
    }

    /**
     * return object DB
     * @return DB
     */

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