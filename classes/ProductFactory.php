<?php

require_once 'Product.php'; // Include the base Product class
require_once 'DVDProduct.php'; // Include the DVDProduct class
require_once 'BookProduct.php'; // Include the BookProduct class
require_once 'FurnitureProduct.php'; // Include the FurnitureProduct class

class ProductFactory
{
    protected $productTypeClassMap = [
        'DVD' => 'DVDProduct', // Maps 'DVD' type to DVDProduct class
        'Book' => 'BookProduct', // Maps 'Book' type to BookProduct class
        'Furniture' => 'FurnitureProduct', // Maps 'Furniture' type to FurnitureProduct class
    ];

    public function __construct($productTypeClassMap = [])
    {
        // Allow for custom class mapping on instantiation
        if (!empty($productTypeClassMap)) {
            $this->productTypeClassMap = array_merge($this->productTypeClassMap, $productTypeClassMap);
        }
    }

    public function createProduct($type, $sku, $name, $price, $specificAttribute)
    {
        // Validate the product type against the map
        if (!isset($this->productTypeClassMap[$type])) {
            throw new InvalidArgumentException("Invalid product type: " . $type);
        }

        $className = $this->productTypeClassMap[$type]; // Determine the class name based on type

        // Instantiate and return the product class based on type
        switch ($type) {
            case 'DVD':
                // For DVD products, $specificAttribute represents the size in MB
                return new $className($sku, $name, $price, $specificAttribute);
            case 'Book':
                // For Book products, $specificAttribute represents the weight in KG
                return new $className($sku, $name, $price, $specificAttribute);
            case 'Furniture':
                // For Furniture products, $specificAttribute is an array of dimensions [height, width, length]
                return new $className($sku, $name, $price, $specificAttribute['height'], $specificAttribute['width'], $specificAttribute['length']);
            default:
                // Handle any unanticipated types
                throw new InvalidArgumentException("Unhandled product type: " . $type);
        }
    }
}