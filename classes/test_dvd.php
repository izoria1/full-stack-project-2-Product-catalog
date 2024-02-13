<?php

require_once 'Database.php'; // Adjust path as needed
require_once 'DVDProduct.php'; // Adjust path as needed

function testSaveDVDProduct($sku, $name, $price, $size) {
    try {
        $dvdProduct = new DVDProduct($sku, $name, $price, $size);
        $dvdProduct->save();
        echo "Test passed: DVD Product saved successfully.\n";
    } catch (Exception $e) {
        echo "Test failed: " . $e->getMessage() . "\n";
    }
}

echo "Testing with Complete and Correct Data:\n";
testSaveDVDProduct("DVD123", "Test DVD", 19.99, 700);

echo "\nTesting with Missing Data (missing size):\n";
testSaveDVDProduct("DVD124", "Incomplete DVD", 14.99, null);

echo "\nTesting with Invalid Data Types (size as string):\n";
testSaveDVDProduct("DVD125", "Invalid Type DVD", 16.99, "invalidSize");

echo "\nTesting with Duplicate SKU:\n";
// Ensure "DVD123" already exists in your database before this test
testSaveDVDProduct("DVD123", "Duplicate SKU DVD", 22.99, 700);
