<?php

require_once 'Product.php'; // Ensure this path is correct
class BookProduct extends Product {
    // Additional property specific to BookProduct
    private $weight;
    
    // Constructor
    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight;
    }
    
    // Getter and Setter for Weight
    public function getWeight() {
        return $this->weight;
    }
    
    public function setWeight($weight) {
        $this->weight = $weight;
    }
    
    // Implement abstract methods
    public function save() {
        echo "Saving Book Product: " . $this->getSku() . "\n";
        // Add database saving logic here
    }
    
    public function display() {
        echo "Displaying Book Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Weight: " . $this->getWeight() . " Kg\n";
    }
}


