<?php

class Database
{
    protected $servername;
    protected $username;
    protected $password;
    protected $database;
    protected $charset;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->servername = DB_SERVER;
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
        $this->database = DB_NAME;
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
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $pdo;
    }

}
