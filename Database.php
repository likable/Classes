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
    
    /**
     * Подготавливает и выполняет запрос к базе данных, возвращает стэйтмент 
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного выражения
     * @return object PDOStatement or FALSE
     */
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
    
    /**
     * Возвращает результат запроса только для одной строки
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного выражения
     * @return array Массив с результатами запроса
     */
    public function getRow($query, $params = [])
    {
        return $this->getSTMT($query, $params)->fetch();
    }
    
    /**
     * Возвращает полный результат запроса
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного выражения
     * @return array Массив с результатами запроса
     */
    public function getRows($query, $params = [])
    {
        return $this->getSTMT($query, $params)->fetchAll();
    }
    
    /**
     * Возвращает результат запроса только для одного столбца
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного выражения
     * @return array Массив с результатами запроса
     */
    public function getColumn($query, $params = [])
    {
        return $this->getSTMT($query, $params)->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Выполняет запрос на добавление в базу данных новой информации
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного выражения
     * @return object Текущий объект
     */
    public function insert($query, $params = [])
    {
        $this->getSTMT($query, $params);
        return $this;
    }
    
    /**
     * Выполняет запрос на обновление информации
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного выражения
     * @return object Текущий объект
     */
    public function update($query, $params = [])
    {
        if (count($params) === 0) {
            exit("В целях безопасности метод update применяется исключительно с параметрами.");
        }
        $this->getSTMT($query, $params);
        return $this;
    }
    
    /**
     * Выполняет запрос на удаление информации
     * 
     * @param string $query SQL-запрос
     * @param array $params Параметры для подготовленного выражения
     * @return object Текущий объект
     */
    public function delete($query, $params = [])
    {
        if (count($params) === 0) {
            exit("В целях безопасности метод delete применяется исключительно с параметрами.");
        }
        $this->getSTMT($query, $params);
        return $this;
    }
    
    /**
     * Возвращает последний id у добавленных в текущую рабочую сессию данных
     * 
     * @return int Последний id или 0, если не было операций INSERT
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
