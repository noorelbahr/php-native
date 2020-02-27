<?php

class Database
{
    private $servername;
    private $username;
    private $password;
    private $database;
    private $charset;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->servername = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->database = 'native';
        $this->charset = 'utf8mb4';
    }

    /**
     * Run query statement
     * - - -
     * @param $statement
     * @return false|PDOStatement
     */
    public function query($statement)
    {
        return $this->connect()->query($statement);
    }

    /**
     * Run prepare statement
     * - - -
     * @param $statement
     * @param array $variables
     * @return bool|PDOStatement
     */
    public function prepareExecute($statement, array $variables = [])
    {
        $statement = $this->connect()->prepare($statement);
        $statement->execute($variables);
        return $statement;
    }

    /**
     * Connect PDO
     * - - -
     * @return PDO
     */
    private function connect()
    {
        $dsn = 'mysql:host=' . $this->servername . ';dbname=' . $this->database . ';charset=' . $this->charset;
        $pdo = new PDO($dsn, $this->username, $this->password);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }

}
