<?php

require_once 'Product.php'; // Ensure this path is correct

class FurnitureProduct extends Product {
    protected $height;
    protected $width;
    protected $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        parent::__construct($sku, $name, $price);
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function getHeight() {
        return $this->height;
    }
    
    public function setHeight($height) {
        $this->height = $height;
    }
    
    public function getWidth() {
        return $this->width;
    }
    
    public function setWidth($width) {
        $this->width = $width;
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function setLength($length) {
        $this->length = $length;
    }
    
    public function save() {
        // Implement saving logic to database
        echo "Saving Furniture Product: " . $this->getSku() . "\n";
    }
    
    public function display() {
        // Implement how to display the furniture product
        echo "Displaying Furniture Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Dimensions: " . $this->getHeight() . "x" . $this->getWidth() . "x" . $this->getLength() . "\n";
    }  
}

