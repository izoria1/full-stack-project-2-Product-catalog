<?php

require_once 'Product.php'; // Ensure this path is correct
require_once 'Database.php'; // Ensure the path to Database.php is correct

class BookProduct extends Product {
    // Additional property specific to BookProduct
    private $weight;
    
    // Constructor
    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price);
        $this->setWeight($weight); // Ensure validation is applied through the setter
    }
    
    // Getter and Setter for Weight
    public function getWeight() {
        return $this->weight;
    }
    
    public function setWeight($weight) {
        if (!is_numeric($weight) || $weight <= 0) {
            throw new InvalidArgumentException("Weight must be a positive number.");
        }
        $this->weight = $weight;
    }

    // Override the setPrice method from Product to add validation
    public function setPrice($price) {
        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException("Price cannot be negative.");
        }
        $this->price = $price;
    }

    // Static method to fetch all book products
    public static function fetchAll() {
        $db = Database::getInstance()->getConnection();
        $query = "SELECT p.sku, p.name, p.price, b.weight 
                  FROM products p 
                  JOIN book_products b ON p.sku = b.sku 
                  WHERE p.type = 'book'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($books as $book) {
            echo "SKU: " . $book['sku'] . "\n";
            echo "Name: " . $book['name'] . "\n";
            echo "Price: $" . $book['price'] . "\n";
            echo "Weight: " . $book['weight'] . " Kg\n\n";
        }
    }

    // Method to delete a product
    public function delete() {
        $db = Database::getInstance()->getConnection();

        // Start transaction to ensure data integrity
        $db->beginTransaction();

        try {
            // First, delete the product's specific attributes from the book_products table
            $this->deleteSpecificAttributes($db);

            // Then, delete the product from the products table
            $query = "DELETE FROM products WHERE sku = :sku";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->execute();

            // If everything is fine, commit the transaction
            $db->commit();
            echo "Product deleted successfully.\n";
        } catch (Exception $e) {
            // If an error occurs, roll back the transaction and report the error
            $db->rollBack();
            echo "Error: Failed to delete product. " . $e->getMessage() . "\n";
        }
    }
    
    // Implement abstract methods
    public function save() {
        $db = Database::getInstance()->getConnection();
    
        try {
            $db->beginTransaction();
    
            // Check for missing or invalid data explicitly
            if (empty($this->getSku()) || empty($this->getName()) || !is_numeric($this->getPrice()) || !is_numeric($this->getWeight())) {
                throw new Exception("Please ensure all fields are filled correctly. SKU, Name, Price, and Weight are required.");
            }
    
            if (!$this->isSkuUnique($db, $this->getSku())) {
                throw new Exception("The SKU '{$this->getSku()}' already exists. Please use a unique SKU.");
            }
    
            $query = "INSERT INTO products (sku, name, price, type) VALUES (:sku, :name, :price, 'book')";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->execute();
    
            $this->saveSpecificAttributes($db);
    
            $db->commit();
            echo "Book Product saved successfully.\n";
        } catch (Exception $e) {
            $db->rollBack();
            echo "Error saving product: " . $e->getMessage() . "\n";
        }
    }    

    // New update method
    public function update() {
        $db = Database::getInstance()->getConnection();
    
        // Start transaction
        $db->beginTransaction();
    
        try {
            // Assuming the product already exists and is identified by SKU
            // Update the base product information in the products table
            $productUpdateQuery = "UPDATE products SET name = :name, price = :price WHERE sku = :sku";
            $stmt = $db->prepare($productUpdateQuery);
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->execute();
    
            // Update the specific attributes in the book_products table
            $this->updateSpecificAttributes($db);
    
            // Commit transaction
            $db->commit();
            echo "Book Product updated successfully.\n";
        } catch (Exception $e) {
            // Rollback transaction if any step fails
            $db->rollBack();
            echo "Error: Failed to update product. " . $e->getMessage() . "\n";
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


