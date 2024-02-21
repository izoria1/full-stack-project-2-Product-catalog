<?php

// Include necessary class definitions for database connection and product handling
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/ProductFactory.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/DVDProduct.php';
require_once __DIR__ . '/../classes/BookProduct.php';
require_once __DIR__ . '/../classes/FurnitureProduct.php';

// Establish database connection using the Singleton pattern
$db = Database::getInstance()->getConnection();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';
    $sku = $_POST['sku'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    
    // Determine specific attributes based on the product type selected
    $specificAttributes = [];
    switch ($type) {
        case 'DVD':
            // Assign DVD size if DVD type is selected
            $specificAttributes = $_POST['size'] ?? null;
            break;
        case 'Book':
            // Assign book weight if Book type is selected
            $specificAttributes = $_POST['weight'] ?? null;
            break;
        case 'Furniture':
            // Collect furniture dimensions if Furniture type is selected
            $specificAttributes = [
                'height' => $_POST['height'] ?? null,
                'width' => $_POST['width'] ?? null,
                'length' => $_POST['length'] ?? null,
            ];
            break;
    }

    // Use the ProductFactory to handle product creation
    $factory = new ProductFactory();
    
    try {
        if ($action == 'create') {
            // Attempt to create and save the product
            $product = $factory->createProduct($type, $sku, $name, $price, $specificAttributes);
            if ($product->save()) {
                // On success, redirect to the product listing with a success flag
                header('Location: ../public/index.php?success=1');
                exit();
            } else {
                // On failure, redirect back to the add-product page with an error message
                header('Location: ../public/add-product.php?error=Failed to create product');
                exit();
            }
        }
        // Placeholder for additional actions like 'update'
    } catch (Exception $e) {
        // Redirect back to the add-product page with an error message on exception
        header('Location: ../public/add-product.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirect to the product listing page if the script is accessed without posting form data
    header('Location: ../public/index.php');
    exit();
}
