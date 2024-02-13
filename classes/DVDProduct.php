<?php

require_once 'Product.php'; // Ensure this path is correct
require_once 'Database.php'; // Ensure this path is correct

class DVDProduct extends Product {
    protected $size; // Size in MB

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->size = $size;
    }

    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        if ($size < 0) {
            throw new Exception("Size cannot be negative.");
        }
        $this->size = $size;
    }

    public function setPrice($price) {
        if ($price < 0) {
            throw new Exception("Price cannot be negative.");
        }
        $this->price = $price;
    }

    public static function fetchAll() {
        $db = Database::getInstance()->getConnection();

        // Join the products table with the dvd_products table to fetch all DVD products
        $query = "SELECT p.sku, p.name, p.price, d.size 
                  FROM products p
                  INNER JOIN dvd_products d ON p.sku = d.sku";

        $stmt = $db->prepare($query);
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            echo "SKU: " . $product['sku'] . "\n";
            echo "Name: " . $product['name'] . "\n";
            echo "Price: $" . $product['price'] . "\n";
            echo "Size: " . $product['size'] . " MB\n\n";
        }
    }

    // Method to delete the product and its specific attributes
    public static function delete($sku) {
        $db = Database::getInstance()->getConnection();
    
        // Start transaction
        $db->beginTransaction();
    
        try {
            // First, delete DVD-specific attributes to avoid foreign key constraint issues
            $deleteSpecific = $db->prepare("DELETE FROM dvd_products WHERE sku = :sku");
            $deleteSpecific->execute([':sku' => $sku]);
    
            // Then, delete the product from the base table
            $deleteProduct = $db->prepare("DELETE FROM products WHERE sku = :sku");
            $deleteProduct->execute([':sku' => $sku]);
    
            if ($deleteProduct->rowCount() > 0) {
                // If deletion was successful, commit the transaction
                $db->commit();
                echo "Product with SKU $sku deleted successfully.\n";
            } else {
                // If no rows were affected, roll back the transaction and report failure
                $db->rollBack();
                echo "No product found with SKU $sku, or deletion failed.\n";
            }
        } catch (Exception $e) {
            // On error, roll back the transaction and re-throw the exception
            $db->rollBack();
            throw $e;
        }
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

    // Method to update the product and its specific attributes
    public function update() {
        $db = Database::getInstance()->getConnection();

        // Check if the product exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sku = :sku");
        $stmt->execute([':sku' => $this->getSku()]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Product with SKU {$this->getSku()} does not exist.");
        }

        // Transaction begins
        $db->beginTransaction();

        try {
            // Update base product data
            $stmt = $db->prepare("UPDATE products SET name = :name, price = :price WHERE sku = :sku");
            $stmt->execute([
                ':sku' => $this->getSku(),
                ':name' => $this->getName(),
                ':price' => $this->getPrice()
            ]);

            // Update DVD-specific attributes
            $this->updateSpecificAttributes($db);

            // Commit transaction
            $db->commit();
            echo "Updated DVD Product successfully.\n";
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            $db->rollBack();
            throw $e; // Re-throw the exception for further handling
        }
    }

}



