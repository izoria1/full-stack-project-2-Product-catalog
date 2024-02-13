<?php

require_once 'Product.php'; // Ensure this path is correct

class DVDProduct extends Product {
    // DVD-specific attribute
    protected $size; // Size in MB

    // Modified constructor to include size attribute
    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->size = $size;
    }

    // Getter for size
    public function getSize() {
        return $this->size;
    }

    // Setter for size
    public function setSize($size) {
        $this->size = $size;
    }

    // Overridden save method to include size
    public function save() {
        // Simulate saving to the database. Actual implementation would involve database logic.
        echo "Saving DVD Product: " . $this->getSku() . ", Size: " . $this->getSize() . " MB\n";
    }

    // Overridden display method to include size
    public function display() {
        echo "Displaying DVD Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Size: " . $this->getSize() . " MB\n";
    }

    public function saveSpecificAttributes($db) {
        $query = "INSERT INTO dvd_products (sku, size) VALUES (:sku, :size)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':size', $this->getSize());
        $stmt->execute();
    }

    // New method to update specific attributes
    public function updateSpecificAttributes($db) {
        $query = "UPDATE dvd_products SET size = :size WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':size', $this->getSize());
        $stmt->execute();
    }

    // New method to delete specific attributes
    public function deleteSpecificAttributes($db) {
        $query = "DELETE FROM dvd_products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();
    }

}



