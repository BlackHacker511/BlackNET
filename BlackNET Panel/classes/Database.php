<?php

namespace BlackNET;

/**
 * a class that handles the database and PDO functions
 *
 * @package BlackNET
 * @version 3.7.0
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */

class Database
{
    /**
     * Database Host
     *
     * @var string
     */
    private $host = DB_HOST;
    /**
     * Database Username
     *
     * @var string
     */
    private $user = DB_USER;
    /**
     * Database Password
     *
     * @var string
     */
    private $pass = DB_PASS;
    /**
     * Database Name
     *
     * @var string
     */
    private $dbname = DB_NAME;
    /**
     * Database Connection
     *
     * @var \PDO
     */
    private $connection;
    /**
     * Database Connection Error
     *
     * @var string
     */
    private $error;
    /**
     * Database PDO Statment
     *
     * @var \PDOStatement|bool
     */
    private $stmt;
    /**
     * Check if the database is connected
     *
     * @var bool
     */
    private $dbconnected = false;

    /**
     * Database class constructor
     *
     * @return void
     */
    public function __construct()
    {

        // Set PDO Connection
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        );

        // Create a new PDO instanace
        try {
            $this->connection = new \PDO($dsn, $this->user, $this->pass, $options);
            $this->dbconnected = true;
        } catch (\PDOException $e) {
            $this->error = $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * Get the Error Message
     *
     * @return string
     *  Returns the error message generated from PDOException
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Check if the class is connected to the database
     *
     * @return bool
     *  Returns true if the class is connected to the database or false otherwise
     */
    public function isConnected()
    {
        return $this->dbconnected;
    }

    /**
     * Prepare the statement with SQL query
     *
     * @param string $query
     *  The SQL query you want to execute
     * @return void
     */
    public function query($query)
    {
        $this->stmt = $this->connection->prepare($query);
    }

    /**
     * Execute the prepared statement
     *
     * @return bool
     *  Returns true if the query is prepared query is executed successfully or false otherwise
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * Execute a query without results
     *
     * @param mixed $query
     *  The SQL query you want to execute
     * @return mixed
     *  Returns the number of rows that were modified or deleted
     */
    public function exec($query)
    {
        return $this->connection->exec($query);
    }

    /**
     * Get the result set as an array of objects
     *
     * @return array
     *  Returns an array containing all of the remaining rows in the result set
     */
    public function resultset()
    {
        $data = $this->stmt->fetchAll(\PDO::FETCH_OBJ);
        if (is_array($data)) {
            return $data;
        }
    }

    /**
     * Get the record row count
     *
     * @return int
     *  Returns the number of rows
     */
    public function rowCount()
    {
        $data = $this->stmt->rowCount();
        if (is_int($data)) {
            return $data;
        }
    }

    /**
     * Get a single record as an object
     *
     * @return object|bool
     *  Returns an object that contains the information from a single record or false otherwise
     */
    public function single()
    {
        $data = $this->stmt->fetch(\PDO::FETCH_OBJ);

        if (is_object($data)) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Return the last inserted record id
     *
     * @return mixed
     *  Returns the last row id that was inserted into the database
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Bind the values with the PDO statment
     *
     * @param string $param
     *  The query parameter you want bind to it
     * @param mixed $value
     *  The value you want to bind with the parameter
     * @param mixed $type
     *  The type of the parameter [optional]
     * @return void
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Close the database connection
     *
     * @return void
     */
    public function __destruct()
    {
        $this->connection = null;
    }
}
