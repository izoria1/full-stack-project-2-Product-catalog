<?php

require_once '../classes/Database.php';
require_once '../classes/DVDProduct.php';
require_once '../classes/BookProduct.php';
require_once '../classes/FurnitureProduct.php';

if (isset($_POST['skus']) && !empty($_POST['skus'])) {
    $db = Database::getInstance()->getConnection();
    $skus = $_POST['skus'];

    // Begin transaction
    try {
        $db->beginTransaction();

        foreach ($skus as $sku) {
            // Delete from product-specific table first to avoid foreign key constraint violation
            $deleteSpecificQuery = "DELETE FROM dvd_products WHERE sku = :sku;
                                    DELETE FROM book_products WHERE sku = :sku;
                                    DELETE FROM furniture_products WHERE sku = :sku;";
            $stmt = $db->prepare($deleteSpecificQuery);
            $stmt->execute([':sku' => $sku]);

            // Then delete from the main products table
            $deleteProductQuery = "DELETE FROM products WHERE sku = :sku";
            $stmt = $db->prepare($deleteProductQuery);
            $stmt->execute([':sku' => $sku]);
        }

        // Commit transaction
        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Products deleted successfully.']);
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete products: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No SKUs provided for deletion.']);
}
