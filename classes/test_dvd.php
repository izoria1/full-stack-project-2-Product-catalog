<?php

require_once 'Database.php'; // Path to your Database class
require_once 'DVDProduct.php'; // Path to your DVDProduct class

// Attempt to create a new DVDProduct and save it
$sku = "DVD00101"; // Make sure this SKU is unique for each test
$name = "Testing DVD";
$price = 19.2;
$size = 20; // Size in MB

$dvdProduct = new DVDProduct($sku, $name, $price, $size);

try {
    $dvdProduct->save();
    echo "Product saved successfully!";
} catch (Exception $e) {
    echo "Error saving product: " . $e->getMessage();
}
