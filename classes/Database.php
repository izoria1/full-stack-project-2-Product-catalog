<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Initialize your database connection here
        // Using PDO as an example
        $this->connection = new PDO("mysql:host=localhost;dbname=product_db", "root", "");
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function __clone() {
        // Prevent cloning of the instance
        throw new Exception("Cloning is not allowed.");
    }

    public function __wakeup() {
        // Prevent unserialization of the instance
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
