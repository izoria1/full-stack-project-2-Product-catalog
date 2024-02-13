<?php
require_once 'Database.php'; // Make sure this path is correct.
require_once 'BookProduct.php';

// Example test case
$bookProduct = new BookProduct("s1", "Testing Book", 19.99, 5);

// Attempt to save the product
$bookProduct->save();
