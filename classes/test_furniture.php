<?php

require_once 'FurnitureProduct.php';
require_once 'Database.php';

// Assuming you have a product with SKU 'FURN1234' in your database
$skuToUpdate = 'FUR777';
$newName = 'Updated Furniture Name';
$newPrice = 150.00; // Make sure this is a valid value (e.g., non-negative)
$newHeight = 20;
$newWidth = 20;
$newLength = 25;

try {
    $db = Database::getInstance()->getConnection();
    
    // Instantiate your FurnitureProduct with new values
    $furnitureProduct = new FurnitureProduct($skuToUpdate, $newName, $newPrice, $newHeight, $newWidth, $newLength);
    
    // Call the update method
    $furnitureProduct->update();

    echo "Update operation successful.\n";

    // Fetch the updated details to verify
    $verifyQuery = "SELECT p.name, p.price, f.height, f.width, f.length FROM products p INNER JOIN furniture_products f ON p.sku = f.sku WHERE p.sku = :sku";
    $stmt = $db->prepare($verifyQuery);
    $stmt->bindValue(':sku', $skuToUpdate);
    $stmt->execute();

    $updatedProduct = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($updatedProduct) {
        echo "Updated Product Details:\n";
        echo "Name: " . $updatedProduct['name'] . "\n";
        echo "Price: $" . $updatedProduct['price'] . "\n";
        echo "Dimensions: " . $updatedProduct['height'] . "x" . $updatedProduct['width'] . "x" . $updatedProduct['length'] . "\n";
    } else {
        echo "Product update verification failed: Product details could not be retrieved.\n";
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}
