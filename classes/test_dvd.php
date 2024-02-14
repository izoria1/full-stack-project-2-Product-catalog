<?php

require_once 'Database.php'; // Adjust the path as necessary
require_once 'DVDProduct.php'; // Adjust the path as necessary

// Helper function to display all DVD products
function fetchAndDisplayAllDVDProducts() {
    echo "Fetching all DVD Products:\n";
    DVDProduct::fetchAll();
    echo "\n";
}

// Test Create Operation
function testCreateDVDProduct($sku, $name, $price, $size) {
    echo "Attempting to create DVD Product: SKU=$sku, Name=$name, Price=$price, Size=$size MB\n";
    try {
        $dvdProduct = new DVDProduct($sku, $name, $price, $size);
        $dvdProduct->save();
        echo "DVD Product created successfully.\n";
    } catch (Exception $e) {
        echo "Failed to create DVD Product: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Test Update Operation
// Note: Update logic needs to be integrated within the DVDProduct class for this to work
function testUpdateDVDProduct($sku, $newName, $newPrice, $newSize) {
    echo "Attempting to update DVD Product: SKU=$sku\n";
    // Implementation depends on how the update method is defined in your DVDProduct class
}

// Test Delete Operation
function testDeleteDVDProduct($sku) {
    echo "Attempting to delete DVD Product: SKU=$sku\n";
    try {
        DVDProduct::delete($sku);
        echo "DVD Product deleted successfully.\n";
    } catch (Exception $e) {
        echo "Failed to delete DVD Product: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Create a new DVD product with valid data
testCreateDVDProduct("DVD1001", "The Great Gatsby", 19.99, 700);

// Attempt to create a DVD product with missing data (size)
testCreateDVDProduct("DVD1002", "Inception", 14.99, null);

// Attempt to create a DVD product with invalid data type for size
testCreateDVDProduct("DVD1003", "Interstellar", 16.99, "invalidSize");

// Attempt to create a DVD product with a duplicate SKU
testCreateDVDProduct("DVD1001", "The Great Gatsby Duplicate", 19.99, 700);

// Display all DVD products
fetchAndDisplayAllDVDProducts();

// Delete a DVD product
testDeleteDVDProduct("DVD1001");

// Attempt to update a DVD product (assuming implementation)
// testUpdateDVDProduct("DVD1001", "New Name", 20.99, 750);

// Fetch and display all DVD products to verify deletion and updates
fetchAndDisplayAllDVDProducts();
