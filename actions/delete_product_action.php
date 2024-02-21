<?php

require_once '../classes/Database.php'; // Include database connection
require_once '../classes/DVDProduct.php'; // Include DVD product class
require_once '../classes/BookProduct.php'; // Include Book product class
require_once '../classes/FurnitureProduct.php'; // Include Furniture product class

if (isset($_POST['skus']) && !empty($_POST['skus'])) {
    $db = Database::getInstance()->getConnection(); // Get database connection instance
    $skus = $_POST['skus']; // Retrieve SKUs from POST request

    try {
        $db->beginTransaction(); // Start transaction for multiple deletions

        foreach ($skus as $sku) {
            // Delete product-specific attributes by SKU to maintain data integrity
            $deleteSpecificQuery = "DELETE FROM dvd_products WHERE sku = :sku;
                                    DELETE FROM book_products WHERE sku = :sku;
                                    DELETE FROM furniture_products WHERE sku = :sku;";
            $stmt = $db->prepare($deleteSpecificQuery); // Prepare the query
            $stmt->execute([':sku' => $sku]); // Execute deletion for specific product types

            // Delete the general product data
            $deleteProductQuery = "DELETE FROM products WHERE sku = :sku"; // Prepare deletion query for main product table
            $stmt = $db->prepare($deleteProductQuery); // Prepare the query
            $stmt->execute([':sku' => $sku]); // Execute deletion for general product data
        }

        $db->commit(); // Commit the transaction to finalize deletion
        echo json_encode(['success' => true, 'message' => 'Products deleted successfully.']); // Return success response
    } catch (Exception $e) {
        $db->rollBack(); // Rollback the transaction in case of any error
        echo json_encode(['success' => false, 'message' => 'Failed to delete products: ' . $e->getMessage()]); // Return error response
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No SKUs provided for deletion.']); // Return error if no SKUs were provided
}
