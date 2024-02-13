<?php

require_once 'Database.php'; // Update the path to where your Database class is located

try {
    $dbConnection = Database::getInstance()->getConnection();
    // If no exception is thrown, the connection is successful
    echo "Database connection test successful.";
} catch (PDOException $e) {
    // If an exception is caught, there was an error connecting to the database
    echo "Database connection test failed: " . $e->getMessage();
}
