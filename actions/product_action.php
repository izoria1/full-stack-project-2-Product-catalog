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
    if ($type == 'DVD') {
        $specificAttributes = $_POST['size'] ?? null;
    } elseif ($type == 'Book') {
        $specificAttributes = $_POST['weight'] ?? null;
    } elseif ($type == 'Furniture') {
        $specificAttributes = [
            'height' => $_POST['height'] ?? null,
            'width' => $_POST['width'] ?? null,
            'length' => $_POST['length'] ?? null,
        ];
    }

    // Instantiate ProductFactory
    $factory = new ProductFactory();
    
    try {
        if ($action == 'create') {
            $product = $factory->createProduct($type, $sku, $name, $price, $specificAttributes);
            if ($product && $product->save()) {
                echo json_encode(['success' => true, 'message' => 'Product created successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create product']);
            }
        }
        // Handle 'update' action if necessary
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    header('Location: index.php');
}
