<?php

require_once 'Product.php'; // Ensure this path is correct
require_once 'Database.php'; // Ensure this path is correct

class DVDProduct extends Product {
    protected $size; // Size in MB

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->setSize($size); // Apply validation through the setter
    }

    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        if (!is_numeric($size) || $size <= 0) {
            throw new InvalidArgumentException("Size must be a positive number.");
        }
        $this->size = $size;
    }

    public function setPrice($price) {
        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException("Price cannot be negative.");
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
    
        try {
            $db->beginTransaction();
    
            if (empty($this->getSku()) || empty($this->getName()) || !is_numeric($this->getPrice()) || !is_numeric($this->getSize())) {
                throw new Exception("Please ensure all fields are filled correctly. SKU, Name, Price, and Size are required.");
            }
    
            if (!$this->isSkuUnique($db, $this->getSku())) {
                throw new Exception("The SKU '{$this->getSku()}' already exists. Please use a unique SKU.");
            }
    
            $query = "INSERT INTO products (sku, name, price, type) VALUES (:sku, :name, :price, 'dvd')";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->execute();
    
            $this->saveSpecificAttributes($db);
    
            $db->commit();
            echo "DVD Product saved successfully.\n";
        } catch (Exception $e) {
            $db->rollBack();
            echo "Error saving product: " . $e->getMessage() . "\n";
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

    private function isSkuUnique($db, $sku) {
        $query = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $sku);
        $stmt->execute();
        return $stmt->fetchColumn() == 0;
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



