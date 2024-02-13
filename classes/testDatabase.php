<?php

// Assuming Database.php is in the current directory
require_once 'Database.php';

// Test Singleton implementation
$instance1 = Database::getInstance();
$instance2 = Database::getInstance();

if ($instance1 === $instance2) {
    echo "Singleton test passed. Only one instance exists.\n";
} else {
    echo "Singleton test failed. Multiple instances detected.\n";
}

// Test database connection success
try {
    $connection = $instance1->getConnection();
    $stmt = $connection->query("SELECT 1");
    $result = $stmt->fetch();
    if ($result) {
        echo "Connection test passed. Database is successfully connected.\n";
    } else {
        echo "Connection test failed. Unable to fetch data.\n";
    }
} catch (Exception $e) {
    echo "Connection test failed with an exception: " . $e->getMessage() . "\n";
}

// Test error handling by attempting to execute a faulty query
try {
    $stmt = $connection->query("SELECT * FROM table_that_does_not_exist");
} catch (PDOException $e) {
    echo "Error handling test passed. Caught exception: " . $e->getMessage() . "\n";
}
