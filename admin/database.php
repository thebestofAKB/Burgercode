<?php

class Database
{
    private static $dbHost = "localhost";
    private static $dbName = "burgercode";
    private static $dbUserName = "root";
    private static $dbUserPassword = "Akbmamou@2021";
    
    private static $connection = null;
    
    public static function connect()
    {
        try
        {
            self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, 
                self::$dbUserName, self::$dbUserPassword);
        }
        catch(PDOException $e)
        {
            die("Erreur de Connexion: " . $e->getMessage());
        }
        
        return self::$connection;
    }
    
    public static function disconnect()
    {
        self::$connection = null;
    }
    

}



?>