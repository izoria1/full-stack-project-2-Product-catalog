<?php

require_once 'Database.php'; // Adjust path as needed
require_once 'FurnitureProduct.php'; // Adjust path as needed

function testSaveFurnitureProduct($sku, $name, $price, $height, $width, $length) {
    try {
        $furnitureProduct = new FurnitureProduct($sku, $name, $price, $height, $width, $length);
        $furnitureProduct->save();
        echo "Test passed: Furniture Product saved successfully.\n";
    } catch (Exception $e) {
        echo "Test failed: " . $e->getMessage() . "\n";
    }
}

echo "Testing with Complete and Correct Data:\n";
testSaveFurnitureProduct("FURN123", "Test Furniture", 99.99, 30, 20, 15);

echo "\nTesting with Missing Data (missing length):\n";
testSaveFurnitureProduct("FURN124", "Incomplete Furniture", 89.99, 30, 20, null);

echo "\nTesting with Invalid Data Types (length as string):\n";
testSaveFurnitureProduct("FURN125", "Invalid Type Furniture", 79.99, 30, 20, "invalidLength");

echo "\nTesting with Duplicate SKU:\n";
// Ensure "FURN123" already exists in your database before this test
testSaveFurnitureProduct("FURN123", "Duplicate SKU Furniture", 109.99, 25, 15, 10);
