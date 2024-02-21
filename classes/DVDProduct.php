<?php

require_once 'Product.php'; // Ensure this path correctly points to the Product class
require_once 'Database.php'; // Ensure this path correctly points to the Database connection class

class DVDProduct extends Product {
    protected $size; // Holds the size of the DVD in MB

    // Constructor initializes DVD product with SKU, name, price, and size
    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->setSize($size); // Set size with validation
    }

    // Returns the size of the DVD
    public function getSize() {
        return $this->size;
    }

    // Sets the size after validating it as a positive number
    public function setSize($size) {
        if (!is_numeric($size) || $size <= 0) {
            throw new InvalidArgumentException("Size must be a positive number.");
        }
        $this->size = $size;
    }

    // Sets the price after validating it as a non-negative number
    public function setPrice($price) {
        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException("Price cannot be negative.");
        }
        $this->price = $price;
    }

    // Fetches all DVD products from the database
    public static function fetchAll() {
        $db = Database::getInstance()->getConnection();
        $query = "SELECT p.sku, p.name, p.price, d.size FROM products p INNER JOIN dvd_products d ON p.sku = d.sku";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns fetched data
    }

    // Deletes a DVD product by SKU, handling related attributes first to maintain integrity
    public static function delete($sku) {
        $db = Database::getInstance()->getConnection();
    
        $db->beginTransaction(); // Starts transaction
    
        try {
            $deleteSpecific = $db->prepare("DELETE FROM dvd_products WHERE sku = :sku");
            $deleteSpecific->execute([':sku' => $sku]);
    
            $deleteProduct = $db->prepare("DELETE FROM products WHERE sku = :sku");
            $deleteProduct->execute([':sku' => $sku]);
    
            if ($deleteProduct->rowCount() > 0) {
                $db->commit(); // Commits transaction if successful
                return true; 
            } else {
                $db->rollBack(); // Rolls back if no product was deleted
                return false; 
            }
        } catch (Exception $e) {
            $db->rollBack(); // Rolls back on error
            throw $e; 
        }
    }

    // Saves a new DVD product to the database, including specific attributes
    public function save() {
        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction(); // Starts transaction
    
            // Validates required fields
            if (empty($this->getSku()) || empty($this->getName()) || !is_numeric($this->getPrice()) || !is_numeric($this->getSize())) {
                throw new Exception("Please ensure all fields are filled correctly. SKU, Name, Price, and Size are required.");
            }
    
            // Checks SKU uniqueness
            if (!$this->isSkuUnique($db, $this->getSku())) {
                throw new Exception("The SKU '{$this->getSku()}' already exists. Please use a unique SKU.");
            }
    
            $query = "INSERT INTO products (sku, name, price, type) VALUES (:sku, :name, :price, 'dvd')";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->execute();
    
            $this->saveSpecificAttributes($db); // Saves DVD-specific attributes

            $db->commit(); // Commits transaction
            return true; 
        } catch (Exception $e) {
            $db->rollBack(); // Rolls back on failure
            return false;
        }
    }

    // Displays DVD product details
    public function display() {
        echo "Displaying DVD Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Size: " . $this->getSize() . " MB\n";
    }

    // Checks if the SKU is unique in the database
    private function isSkuUnique($db, $sku) {
        $query = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $sku);
        $stmt->execute();
        return $stmt->fetchColumn() == 0; // Returns true if SKU is unique
    }

    // Saves DVD-specific attributes to the database
    public function saveSpecificAttributes($db) {
        $query = "INSERT INTO dvd_products (sku, size) VALUES (:sku, :size)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':size', $this->getSize());
        $stmt->execute();
    }

    // Updates DVD-specific attributes in the database
    public function updateSpecificAttributes($db) {
        $query = "UPDATE dvd_products SET size = :size WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':size', $this->getSize());
        $stmt->execute();
    }

    // Deletes DVD-specific attributes from the database
    public function deleteSpecificAttributes($db) {
        $query = "DELETE FROM dvd_products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();
    }

    // Updates a DVD product and its specific attributes in the database
    public function update() {
        $db = Database::getInstance()->getConnection();

        // Verifies product existence before update
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sku = :sku");
        $stmt->execute([':sku' => $this->getSku()]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Product with SKU {$this->getSku()} does not exist.");
        }

        $db->beginTransaction(); // Begins transaction

        try {
            // Updates product information in the products table
            $stmt = $db->prepare("UPDATE products SET name = :name, price = :price WHERE sku = :sku");
            $stmt->execute([
                ':sku' => $this->getSku(),
                ':name' => $this->getName(),
                ':price' => $this->getPrice()
            ]);

            $this->updateSpecificAttributes($db); // Updates DVD-specific attributes

            $db->commit(); // Commits the transaction
            echo "Updated DVD Product successfully.\n";
        } catch (Exception $e) {
            $db->rollBack(); // Rolls back transaction on error
            throw $e; // Re-throws the exception for further handling
        }
    }
}
