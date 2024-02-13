<?php

require_once 'FurnitureProduct.php';
require_once 'Database.php'; // Ensure this path is correct

// Assuming Database.php and other necessary files are correctly included and set up

// Example for a successful creation and save
try {
    $furnitureProduct = new FurnitureProduct("FUR99", "Stylish Chair", 150.00, 122, 76, 45);
    $furnitureProduct->save();
    echo "Product saved successfully: " . $furnitureProduct->display() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example for handling invalid data (negative dimensions)
try {
    $furnitureProductInvalid = new FurnitureProduct("FUR124", "Small Table", 85.00, 50, 60, -40);
    $furnitureProductInvalid->save();
} catch (Exception $e) {
    echo "Validation failed: " . $e->getMessage() . "\n";
}

// Example for handling duplicate SKU
try {
    $furnitureProductDuplicateSKU = new FurnitureProduct("FUR99", "Another Chair", 120.00, 100, 70, 50);
    $furnitureProductDuplicateSKU->save();
} catch (Exception $e) {
    echo "Duplicate SKU: " . $e->getMessage() . "\n";
}

