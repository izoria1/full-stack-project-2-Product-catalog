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

    public function setPrice($price) {
        if ($price < 0) {
            throw new Exception("Price cannot be negative.");
        }
        $this->price = $price;
    }
    
    
    public function save() {
        // Additional validation to ensure data integrity
        if ($this->height < 0 || $this->width < 0 || $this->length < 0) {
            throw new Exception("Dimensions cannot be negative.");
        }

        $db = Database::getInstance()->getConnection();

        // Check for SKU uniqueness
        $skuCheckQuery = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($skuCheckQuery);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("SKU already exists.");
        }

        $db->beginTransaction();

        try {
            // Insert into products table
            $productInsertQuery = "INSERT INTO products (sku, name, price) VALUES (:sku, :name, :price)";
            $stmt = $db->prepare($productInsertQuery);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->execute();

            // Call to insert specific attributes
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

    public function saveSpecificAttributes($db) {
        // Ensure this is only called after validation checks in save()
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

