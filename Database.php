<?php

namespace classes;

use PDO;

class Database 
{
    private $pdo;

    public static $charset = "utf8";
    public static $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ];
    
    public function __construct($host, $db_name, $user_name, $password) 
    {
        $dsn = "mysql:host={$host}; dbname={$db_name}; charset=" . self::$charset;
        $this->pdo = new PDO($dsn, $user_name, $password, self::$options);
    }
    
    public function __destruct()
    {
        $this->pdo = null;
    }
    
    //query
    private function getSTMT($query, $params = [])
    {
        $stmt = false;
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $stmt;
    }
    
    
    
    //getRow
    
    
    
    //getRows
    
    
    
    //getColumn
    
    
    
    //insert
    
    
    
    //update
    
    
    
    //delete
    
    
    
    //lastInsertId
    
    
    
    //numRows
    
    
}
