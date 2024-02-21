<?php

require_once 'Product.php'; // Include the base Product class
require_once 'Database.php'; // Include the Database connection class

class BookProduct extends Product
{
    private $weight; // Holds the weight of the book
    
    // Initializes a new instance of the BookProduct class
    public function __construct($sku, $name, $price, $weight)
    {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight; // Set the weight during object construction
    }
    
    // Returns the weight of the book
    public function getWeight()
    {
        return $this->weight;
    }
    
    // Sets the weight of the book, ensuring it's a positive number
    public function setWeight($weight)
    {
        if (!is_numeric($weight) || $weight <= 0) {
            throw new InvalidArgumentException("Weight must be a positive number.");
        }
        $this->weight = $weight;
    }

    // Sets the price of the book, ensuring it's not negative
    public function setPrice($price)
    {
        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException("Price cannot be negative.");
        }
        $this->price = $price;
    }

    // Retrieves all book products from the database
    public static function fetchAll()
    {
        $db = Database::getInstance()->getConnection();
        $query = "SELECT p.sku, p.name, p.price, b.weight FROM products p JOIN book_products b ON p.sku = b.sku WHERE p.type = 'book'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Deletes a book product by SKU
    public static function delete($sku)
    {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        try {
            $deleteSpecificQuery = "DELETE FROM book_products WHERE sku = :sku";
            $stmt = $db->prepare($deleteSpecificQuery);
            $stmt->bindValue(':sku', $sku);
            $stmt->execute();

            $deleteProductQuery = "DELETE FROM products WHERE sku = :sku";
            $stmt = $db->prepare($deleteProductQuery);
            $stmt->bindValue(':sku', $sku);
            $stmt->execute();

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    // Saves the book product to the database
    public function save()
    {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        try {
            if (empty($this->getSku()) || empty($this->getName()) || !is_numeric($this->getPrice()) || !is_numeric($this->getWeight())) {
                throw new Exception("All fields are required and must be valid. Please check the SKU, name, price, and weight.");
            }

            if (!$this->isSkuUnique($db, $this->getSku())) {
                throw new Exception("The SKU '{$this->getSku()}' already exists. Please use a different SKU.");
            }
    
            $query = "INSERT INTO products (sku, name, price, type) VALUES (:sku, :name, :price, 'book')";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->execute();
    
            $this->saveSpecificAttributes($db);
    
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    // Updates the book product in the database
    public function update()
    {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();

        try {
            $productUpdateQuery = "UPDATE products SET name = :name, price = :price WHERE sku = :sku";
            $stmt = $db->prepare($productUpdateQuery);
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->execute();
    
            $this->updateSpecificAttributes($db);
    
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            echo "Error: Failed to update product. " . $e->getMessage() . "\n";
        }
    }
    
    // Displays the book product details
    public function display()
    {
        echo "Displaying Book Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Weight: " . $this->getWeight() . " Kg\n";
    }

    // Checks if the SKU is unique in the database
    private function isSkuUnique($db, $sku)
    {
        $query = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $sku);
        $stmt->execute();
        return $stmt->fetchColumn() == 0;
    }

    // Saves book-specific attributes to the database
    public function saveSpecificAttributes($db)
    {
        $query = "INSERT INTO book_products (sku, weight) VALUES (:sku, :weight)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':weight', $this->getWeight());
        $stmt->execute();
    }

    // Updates book-specific attributes in the database
    public function updateSpecificAttributes($db)
    {
        $query = "UPDATE book_products SET weight = :weight WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':weight', $this->getWeight());
        $stmt->execute();
    }

    // Deletes book-specific attributes from the database
    protected function deleteSpecificAttributes($db, $validate = true)
    {
        if ($validate && (!is_numeric($this->weight) || $this->weight <= 0)) {
            throw new InvalidArgumentException("Weight must be valid for deletion.");
        }
        $query = "DELETE FROM book_products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->sku);
        $stmt->execute();
    }
}
