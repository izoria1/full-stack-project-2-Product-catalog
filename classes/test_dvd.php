<?php

require_once 'Database.php'; // Path to your Database class
require_once 'DVDProduct.php'; // Path to your DVDProduct class


// Specify the SKU of the product you want to delete
$skuToDelete = "DVD0010";

try {
    DVDProduct::delete($skuToDelete);
} catch (Exception $e) {
    echo "Error deleting product: " . $e->getMessage();
}
