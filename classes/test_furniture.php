<?php

require_once 'Database.php'; // Adjust the path as necessary
require_once 'FurnitureProduct.php'; // Adjust the path as necessary

// Helper function to display all Furniture products
function fetchAndDisplayAllFurnitureProducts() {
    echo "Fetching all Furniture Products:\n";
    FurnitureProduct::fetchAll();
    echo "\n";
}

// Test Create Operation
function testCreateFurnitureProduct($sku, $name, $price, $height, $width, $length) {
    echo "Attempting to create Furniture Product: SKU=$sku, Name=$name, Price=$price, Dimensions=$height x $width x $length\n";
    try {
        $furnitureProduct = new FurnitureProduct($sku, $name, $price, $height, $width, $length);
        $furnitureProduct->save();
        echo "Furniture Product created successfully.\n";
    } catch (Exception $e) {
        echo "Failed to create Furniture Product: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Test Update Operation
// Note: Assuming the FurnitureProduct class includes an 'update' method similar to 'save'
function testUpdateFurnitureProduct($sku, $newName, $newPrice, $newHeight, $newWidth, $newLength) {
    echo "Attempting to update Furniture Product: SKU=$sku\n";
    // Implementation would follow a similar structure to 'save', adjusting attributes and verifying the update
}

// Test Delete Operation
function testDeleteFurnitureProduct($sku) {
    echo "Attempting to delete Furniture Product: SKU=$sku\n";
    try {
        FurnitureProduct::delete($sku);
        echo "Furniture Product deleted successfully.\n";
    } catch (Exception $e) {
        echo "Failed to delete Furniture Product: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Create a new Furniture product with valid data
testCreateFurnitureProduct("FURN1001", "Elegant Sofa", 299.99, 80, 120, 35);

// Attempt to create a Furniture product with missing data (height)
testCreateFurnitureProduct("FURN1002", "Minimalist Chair", 149.99, null, 85, 40);

// Attempt to create a Furniture product with invalid data type for dimensions
testCreateFurnitureProduct("FURN1003", "Vintage Shelf", 199.99, "85", "invalidWidth", 45);

// Attempt to create a Furniture product with a duplicate SKU
testCreateFurnitureProduct("FURN1001", "Elegant Sofa Duplicate", 299.99, 80, 120, 35);

// Display all Furniture products
fetchAndDisplayAllFurnitureProducts();

// Delete a Furniture product
testDeleteFurnitureProduct("FURN1001");

// Fetch and display all Furniture products to verify deletion
fetchAndDisplayAllFurnitureProducts();
