<?php

require_once 'Product.php'; // Include the base Product class
require_once 'Database.php'; // Include the Database connection class

class FurnitureProduct extends Product
{
    protected $height; // Height of the furniture in centimeters
    protected $width; // Width of the furniture in centimeters
    protected $length; // Length of the furniture in centimeters

    // Constructor initializes the furniture product with its specific attributes
    public function __construct($sku, $name, $price, $height, $width, $length)
    {
        parent::__construct($sku, $name, $price); // Call to parent constructor of Product class
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
    }

    // Returns the height of the furniture
    public function getHeight()
    {
        return $this->height;
    }

    // Validates and sets the height attribute
    public function setHeight($height)
    {
        if (!is_numeric($height) || $height <= 0) {
            throw new InvalidArgumentException("Height must be a positive number.");
        }
        $this->height = $height;
    }

    // Returns the width of the furniture
    public function getWidth()
    {
        return $this->width;
    }

    // Validates and sets the width attribute
    public function setWidth($width)
    {
        if (!is_numeric($width) || $width <= 0) {
            throw new InvalidArgumentException("Width must be a positive number.");
        }
        $this->width = $width;
    }

    // Returns the length of the furniture
    public function getLength()
    {
        return $this->length;
    }

    // Validates and sets the length attribute
    public function setLength($length)
    {
        if (!is_numeric($length) || $length <= 0) {
            throw new InvalidArgumentException("Length must be a positive number.");
        }
        $this->length = $length;
    }

    // Validates and sets the price attribute
    public function setPrice($price)
    {
        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException("Price cannot be negative.");
        }
        $this->price = $price;
    }

    // Fetches all furniture products from the database
    public static function fetchAll()
    {
        $db = Database::getInstance()->getConnection(); // Get database connection
        $query = "SELECT p.sku, p.name, p.price, f.height, f.width, f.length FROM products p INNER JOIN furniture_products f ON p.sku = f.sku WHERE p.type = 'furniture'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch and return all matching records
    }

    // Deletes a furniture product identified by SKU
    public static function delete($sku)
    {
        $db = Database::getInstance()->getConnection(); // Get database connection

        $db->beginTransaction(); // Start transaction for data integrity

        try {
            // Delete specific attributes of the furniture product
            $deleteSpecificQuery = "DELETE FROM furniture_products WHERE sku = :sku";
            $stmt = $db->prepare($deleteSpecificQuery);
            $stmt->bindValue(':sku', $sku);
            $stmt->execute();

            // Delete the general product record
            $deleteProductQuery = "DELETE FROM products WHERE sku = :sku";
            $stmt = $db->prepare($deleteProductQuery);
            $stmt->bindValue(':sku', $sku);
            $stmt->execute();

            $db->commit(); // Commit the transaction
            return true; // Indicate successful deletion
        } catch (Exception $e) {
            $db->rollBack(); // Roll back the transaction in case of an error
            throw $e;
        }
    }

    public function save()
    {
        $db = Database::getInstance()->getConnection(); // Get database connection

        try {
            $db->beginTransaction(); // Start transaction for data integrity

            // Validate mandatory fields are correctly filled
            if (empty($this->getSku()) || empty($this->getName()) || !is_numeric($this->getPrice()) ||
                !is_numeric($this->getHeight()) || !is_numeric($this->getWidth()) || !is_numeric($this->getLength())) {
                throw new Exception("Please ensure all fields are filled correctly with valid numeric values.");
            }

            // Check for SKU uniqueness
            if (!$this->isSkuUnique($db, $this->getSku())) {
                throw new Exception("The SKU '{$this->getSku()}' already exists. Please use a unique SKU.");
            }

            // Insert product record into the database
            $query = "INSERT INTO products (sku, name, price, type) VALUES (:sku, :name, :price, 'furniture')";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->execute();

            // Insert specific furniture attributes into the database
            $this->saveSpecificAttributes($db);

            $db->commit(); // Commit the transaction
            return true; // Indicate successful save
        } catch (Exception $e) {
            $db->rollBack(); // Roll back the transaction in case of an error
            return false; // Indicate failure to save
        }
    }
    
    public function display()
    {
        // Output the details of the furniture product
        echo "Displaying Furniture Product:\n";
        echo "SKU: " . $this->getSku() . "\n";
        echo "Name: " . $this->getName() . "\n";
        echo "Price: $" . $this->getPrice() . "\n";
        echo "Dimensions: " . $this->getHeight() . "x" . $this->getWidth() . "x" . $this->getLength() . "\n";
    }
    
    private function isSkuUnique($db, $sku)
    {
        // Check if the provided SKU already exists in the database
        $query = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $sku);
        $stmt->execute();
        return $stmt->fetchColumn() == 0; // True if SKU is unique
    }
    
    public function update()
    {
        // Connect to the database
        $db = Database::getInstance()->getConnection();
    
        // Verify existence of the product by SKU before updating
        $existCheckQuery = "SELECT COUNT(*) FROM products WHERE sku = :sku";
        $stmt = $db->prepare($existCheckQuery);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();
    
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Product with SKU: {$this->getSku()} does not exist.");
        }
    
        // Begin transaction for updating product details
        $db->beginTransaction();
    
        try {
            // Update generic product information
            $productUpdateQuery = "UPDATE products SET name = :name, price = :price WHERE sku = :sku";
            $stmt = $db->prepare($productUpdateQuery);
            $stmt->bindValue(':name', $this->getName());
            $stmt->bindValue(':price', $this->getPrice());
            $stmt->bindValue(':sku', $this->getSku());
            $stmt->execute();
    
            // Update specific furniture attributes
            $this->updateSpecificAttributes($db);
    
            // Commit the transaction after successful update
            $db->commit();
            echo "Furniture Product updated successfully.\n";
        } catch (Exception $e) {
            // Rollback transaction in case of errors
            $db->rollBack();
            throw $e;
        }
    }
    
    public function saveSpecificAttributes($db)
    {
        // Insert furniture-specific attributes into the database
        $query = "INSERT INTO furniture_products (sku, height, width, length) VALUES (:sku, :height, :width, :length)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':height', $this->getHeight());
        $stmt->bindValue(':width', $this->getWidth());
        $stmt->bindValue(':length', $this->getLength());
        $stmt->execute();
    }
    
    public function updateSpecificAttributes($db)
    {
        // Update furniture-specific attributes in the database
        $query = "UPDATE furniture_products SET height = :height, width = :width, length = :length WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->bindValue(':height', $this->getHeight());
        $stmt->bindValue(':width', $this->getWidth());
        $stmt->bindValue(':length', $this->getLength());
        $stmt->execute();
    }
    
    public function deleteSpecificAttributes($db)
    {
        // Delete furniture-specific attributes from the database
        $query = "DELETE FROM furniture_products WHERE sku = :sku";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':sku', $this->getSku());
        $stmt->execute();
    }
    
}
