<?php

require_once 'Product.php'; // Ensure this path is correct
require_once 'Database.php'; // Ensure this path is correct

class FurnitureProduct extends Product {
    protected $height;
    protected $width;
    protected $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        parent::__construct($sku, $name, $price);
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
        $this->setPrice($price); // Ensure price is set through the setter to apply validation
    }

    public function getHeight() {
        return $this->height;
    }
    
    public function setHeight($height) {
        if ($height < 0) {
            throw new Exception("Height cannot be negative.");
        }
        $this->height = $height;
    }
    
    public function getWidth() {
        return $this->width;
    }
    
    public function setWidth($width) {
        if ($width < 0) {
            throw new Exception("Width cannot be negative.");
        }
        $this->width = $width;
    }
    
    public function getLength() {
        return $this->length;
    }
    
    public function setLength($length) {
        if ($length < 0) {
            throw new Exception("Length cannot be negative.");
        }
        $this->length = $length;
    }

    // Override the setPrice method to include validation for negative price
    public function setPrice($price) {
        if ($price < 0) {
            throw new Exception("Price cannot be negative.");
        }
        $this->price = $price;
    }
    
    // New static method for fetching all furniture products
    public static function fetchAll() {
        $db = Database::getInstance()->getConnection();

        // SQL query to join base product table with the furniture-specific attributes table
        $query = "SELECT p.sku, p.name, p.price, f.height, f.width, f.length FROM products p INNER JOIN furniture_products f ON p.sku = f.sku WHERE p.type = 'furniture'";

        $stmt = $db->prepare($query);
        $stmt->execute();

        // Fetch all matching records
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($products) {
            echo "Furniture Products Retrieved Successfully:\n";
            foreach ($products as $product) {
                echo "SKU: " . $product['sku'] . ", ";
                echo "Name: " . $product['name'] . ", ";
                echo "Price: $" . $product['price'] . ", ";
                echo "Dimensions: " . $product['height'] . "x" . $product['width'] . "x" . $product['length'] . "\n";
            }
        } else {
            echo "No furniture products found.\n";
        }
    }

    // Method to delete a furniture product by its SKU
    public static function delete($sku) {
        $db = Database::getInstance()->getConnection();

        // Begin transaction to ensure data integrity
        $db->beginTransaction();

        try {
            // First, delete the specific attributes in the furniture_products table
            $deleteSpecificQuery = "DELETE FROM furniture_products WHERE sku = :sku";
            $stmt = $db->prepare($deleteSpecificQuery);
            $stmt->bindValue(':sku', $sku);
            $stmt->execute();

            // Then, delete the base product record in the products table
            $deleteProductQuery = "DELETE FROM products WHERE sku = :sku";
            $stmt = $db->prepare($deleteProductQuery);
            $stmt->bindValue(':sku', $sku);
            $stmt->execute();

            // Commit the transaction
            $db->commit();
            echo "Furniture Product with SKU $sku deleted successfully.\n";
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $db->rollBack();
            throw new Exception("Failed to delete Furniture Product with SKU $sku: " . $e->getMessage());
        }
    }

    public function save() {
        $db = Database::getInstance()->getConnection();

        // SKU uniqueness check
        $skuCheckQuery = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($skuCheckQuery);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("SKU already exists.");
        }

        $db->beginTransaction();

        try {
            // Insert into products table with type
            $productInsertQuery = "INSERT INTO products (sku, name, price, type) VALUES (:sku, :name, :price, 'furniture')";
            $stmt = $db->prepare($productInsertQuery);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->execute();

            // Insert specific attributes for furniture
            $this->saveSpecificAttributes($db);

            $db->commit();
            echo "Furniture Product saved successfully.\n";
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    public function display() {
        echo "Displaying Furniture Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Dimensions: " . $this->getHeight() . "x" . $this->getWidth() . "x" . $this->getLength() . "\n";
    }

    public function update() {
        $db = Database::getInstance()->getConnection();

        // Check if the product exists
        $existCheckQuery = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($existCheckQuery);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Product with SKU: {$this->getSku()} does not exist.");
        }

        $db->beginTransaction();

        try {
            // Update the base product details
            $productUpdateQuery = "UPDATE products SET name = :name, price = :price WHERE sku = :sku";
            $stmt = $db->prepare($productUpdateQuery);
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->execute();

            // Update the specific product attributes
            $this->updateSpecificAttributes($db);

            $db->commit();
            echo "Furniture Product updated successfully.\n";
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function saveSpecificAttributes($db) {
        // Insert into furniture_products table
        $query = "INSERT INTO furniture_products (sku, height, width, length) VALUES (:sku, :height, :width, :length)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':height', $this->getHeight());
        $stmt->bindValue(':width', $this->getWidth());
        $stmt->bindValue(':length', $this->getLength());
        $stmt->execute();
    }

    // New method to update specific attributes
    public function updateSpecificAttributes($db) {
        $query = "UPDATE furniture_products SET height = :height, width = :width, length = :length WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':height', $this->getHeight());
        $stmt->bindValue(':width', $this->getWidth());
        $stmt->bindValue(':length', $this->getLength());
        $stmt->execute();
    }

    // New method to delete specific attributes
    public function deleteSpecificAttributes($db) {
        $query = "DELETE FROM furniture_products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();
    }
}

