<?php

// Include necessary classes
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/ProductFactory.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/DVDProduct.php';
require_once __DIR__ . '/../classes/BookProduct.php';
require_once __DIR__ . '/../classes/FurnitureProduct.php';

$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';
    $sku = $_POST['sku'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    
    // Prepare specific attributes based on product type
    $specificAttributes = [];
    switch ($type) {
        case 'DVD':
            $specificAttributes = $_POST['size'] ?? null;
            break;
        case 'Book':
            $specificAttributes = $_POST['weight'] ?? null;
            break;
        case 'Furniture':
            $specificAttributes = [
                'height' => $_POST['height'] ?? null,
                'width' => $_POST['width'] ?? null,
                'length' => $_POST['length'] ?? null,
            ];
            break;
    }

    // Instantiate ProductFactory
    $factory = new ProductFactory();
    
    try {
        if ($action == 'create') {
            $product = $factory->createProduct($type, $sku, $name, $price, $specificAttributes);
            if ($product->save()) {
                // Redirect to the product list page on successful creation
                header('Location: ../public/index.php?success=1');
                exit();
            } else {
                // Optional: Handle failure case, maybe pass a message via session or query
                header('Location: ../public/add-product.php?error=Failed to create product');
                exit();
            }
        }
        // Additional actions (like 'update') can be handled here
    } catch (Exception $e) {
        // Optional: Handle exception case
        header('Location: ../public/add-product.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ../public/index.php');
    exit();
}
