<?php

require_once 'Product.php'; // Ensure this path is correct
require_once 'Database.php'; // Ensure the path to Database.php is correct

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

    // Override the setPrice method from Product to add validation
    public function setPrice($price) {
        if ($price < 0) {
            throw new InvalidArgumentException("Price cannot be negative.");
        }
        $this->price = $price;
    }
    
    // Implement abstract methods
    public function save() {
        // Get database connection from the Database singleton
        $db = Database::getInstance()->getConnection();

        // Validate price and weight before proceeding
        if ($this->getPrice() < 0 || $this->getWeight() <= 0) {
            echo "Error: Invalid price or weight.\n";
            return false;
        }

        // Check for SKU uniqueness
        if (!$this->isSkuUnique($db, $this->getSku())) {
            echo "Error: SKU " . $this->getSku() . " already exists.\n";
            return false; // Stop execution if SKU is not unique
        }

        // Insert into products table
        $query = "INSERT INTO products (sku, name, price, type) VALUES (:sku, :name, :price, 'book')";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':name', $this->getName());
        $stmt->bindValue(':price', $this->getPrice());
        $success = $stmt->execute();

        // If insert into products was successful, proceed to insert specific attributes
        if ($success) {
            $this->saveSpecificAttributes($db);
            echo "Book Product saved successfully.\n";
        } else {
            echo "Error: Failed to save product.\n";
        }
    }
    
    public function display() {
        echo "Displaying Book Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Weight: " . $this->getWeight() . " Kg\n";
    }

    private function isSkuUnique($db, $sku) {
        $query = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $sku);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count == 0; // True if SKU is unique
    }

    // New method to save specific attributes
    public function saveSpecificAttributes($db) {
        $query = "INSERT INTO book_products (sku, weight) VALUES (:sku, :weight)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':weight', $this->getWeight());
        $stmt->execute();
    }

    // New method to update specific attributes
    public function updateSpecificAttributes($db) {
        $query = "UPDATE book_products SET weight = :weight WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':weight', $this->getWeight());
        $stmt->execute();
    }

    // New method to delete specific attributes
    public function deleteSpecificAttributes($db) {
        $query = "DELETE FROM book_products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();
    }
}


