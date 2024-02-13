<?php

require_once 'FurnitureProduct.php';
require_once 'Database.php';

$skuToDelete = 'FUR999'; // Replace with the actual SKU of the product you wish to delete
FurnitureProduct::delete($skuToDelete);

FurnitureProduct::fetchAll();
