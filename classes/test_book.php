<?php

require_once 'Database.php'; // Adjust path as needed
require_once 'BookProduct.php'; // Adjust path as needed

function testSaveBookProduct($sku, $name, $price, $weight) {
    try {
        $bookProduct = new BookProduct($sku, $name, $price, $weight);
        $bookProduct->save();
        echo "Test passed: Product saved successfully.\n";
    } catch (Exception $e) {
        echo "Test failed: " . $e->getMessage() . "\n";
    }
}

echo "Testing with Complete and Correct Data:\n";
testSaveBookProduct("BOOK1", "Test Book", 20.99, 1.5);

echo "\nTesting with Missing Data (missing weight):\n";
testSaveBookProduct("BOOK2", "Incomplete Book", 17.99, null);

echo "\nTesting with Invalid Data Types (weight as string):\n";
testSaveBookProduct("BOOK3", "Invalid Type Book", 16.99, "invalidWeight");

echo "\nTesting with Duplicate SKU:\n";
// Ensure "BOOK123" already exists in your database before this test
testSaveBookProduct("BOOK1", "Duplicate SKU Book", 22.99, 2.0);
