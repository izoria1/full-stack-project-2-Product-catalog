<?php
class Database
{
    private static $instance = null; // Holds the singleton instance of this class
    private $connection; // Holds the PDO connection to the database

    private function __construct()
    {
        // Production database credentials
        $host = ''; // Usually something like 'localhost' or a specific server address
        $dbname = '';
        $username = '';
        $password = '';

        // Establishes the database connection using PDO with production credentials
        $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Set error mode to exception to handle errors more gracefully
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function __clone()
    {
        // Prevents the instance from being cloned to ensure a singleton instance
        throw new Exception("Cloning is not allowed.");
    }

    public function __wakeup()
    {
        // Prevents the instance from being unserialized to ensure a singleton instance
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        // Returns the singleton instance, creating it if it does not exist
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        // Returns the PDO database connection
        return $this->connection;
    }
}
