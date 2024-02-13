<?php

require_once 'Product.php';
require_once 'DVDProduct.php';
require_once 'BookProduct.php';
require_once 'FurnitureProduct.php';

class ProductFactory {
    protected $productTypeClassMap = [
        'DVD' => 'DVDProduct',
        'Book' => 'BookProduct',
        'Furniture' => 'FurnitureProduct',
    ];

    public function __construct($productTypeClassMap = []) {
        // Override the default mapping with any custom mapping provided
        if (!empty($productTypeClassMap)) {
            $this->productTypeClassMap = array_merge($this->productTypeClassMap, $productTypeClassMap);
        }
    }

    public function createProduct($type, $sku, $name, $price, $specificAttribute) {
        if (!isset($this->productTypeClassMap[$type])) {
            throw new InvalidArgumentException("Invalid product type: " . $type);
        }

        $className = $this->productTypeClassMap[$type];

        // Depending on the type, we pass the specific attribute to the constructor.
        switch ($type) {
            case 'DVD':
                return new $className($sku, $name, $price, $specificAttribute); // $specificAttribute is size for DVD
            case 'Book':
                return new $className($sku, $name, $price, $specificAttribute); // $specificAttribute is weight for Book
            case 'Furniture':
                // Assuming $specificAttribute is an array with height, width, length for Furniture
                return new $className($sku, $name, $price, $specificAttribute['height'], $specificAttribute['width'], $specificAttribute['length']);
            default:
                throw new InvalidArgumentException("Unhandled product type: " . $type);
        }
    }
}
