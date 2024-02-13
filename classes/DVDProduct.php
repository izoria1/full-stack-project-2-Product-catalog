<?php

require_once 'Product.php'; // Ensure this path is correct
require_once 'Database.php'; // Ensure this path is correct

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
    // Setter for price with validation
    public function setPrice($price) {
        if ($price < 0) {
            throw new Exception("Price cannot be negative.");
        }
        $this->price = $price;
    }

    // Setter for size with validation
    public function setSize($size) {
        if ($size < 0) {
            throw new Exception("Size cannot be negative.");
        }
        $this->size = $size;
    }

    // Overridden save method to include size and database logic
    public function save() {
        $db = Database::getInstance()->getConnection();

        if ($this->getPrice() < 0 || $this->getSize() < 0) {
            throw new Exception("Price and size must be non-negative.");
        }

        // Check SKU uniqueness
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sku = :sku");
        $stmt->execute([':sku' => $this->getSku()]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("SKU {$this->getSku()} already exists.");
        }

        // Transaction begins
        $db->beginTransaction();
        try {
            // Insert base product data
            $stmt = $db->prepare("INSERT INTO products (sku, name, price) VALUES (:sku, :name, :price)");
            $stmt->execute([
                ':sku' => $this->getSku(),
                ':name' => $this->getName(),
                ':price' => $this->getPrice()
            ]);

            // Insert DVD-specific attributes
            $this->saveSpecificAttributes($db);

            // Commit transaction
            $db->commit();
            echo "Saved DVD Product successfully.\n";
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            $db->rollBack();
            throw $e; // Re-throw the exception for further handling
        }
    }

    // Implement the abstract display method
    public function display() {
        // Display logic for DVDProduct
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



