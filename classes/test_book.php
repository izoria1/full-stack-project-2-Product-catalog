<?php

require_once 'Database.php'; // Adjust the path as needed
require_once 'BookProduct.php'; // Adjust the path as needed

$db = Database::getInstance()->getConnection();

// Helper function for testing saves
function createBookProduct($sku, $name, $price, $weight) {
    try {
        $book = new BookProduct($sku, $name, $price, $weight);
        $book->save();
        echo "Create: Book product '{$sku}' saved successfully.\n";
    } catch (Exception $e) {
        echo "Create: Failed to save '{$sku}'. Error: " . $e->getMessage() . "\n";
    }
}

// Helper function for fetching and displaying book products
function fetchAllBooks() {
    echo "Fetch: Displaying all book products.\n";
    BookProduct::fetchAll();
}

// Helper function for testing updates
function updateBookProduct($sku, $name, $price, $weight) {
    try {
        $book = new BookProduct($sku, $name, $price, $weight);
        $book->update();
        echo "Update: Book product '{$sku}' updated successfully.\n";
    } catch (Exception $e) {
        echo "Update: Failed to update '{$sku}'. Error: " . $e->getMessage() . "\n";
    }
}

// Helper function for testing deletes
function deleteBookProduct($sku) {
    try {
        $book = new BookProduct($sku, "", 0, 0); // Name, price, and weight are irrelevant for deletion
        $book->delete();
        echo "Delete: Book product '{$sku}' deleted successfully.\n";
    } catch (Exception $e) {
        echo "Delete: Failed to delete '{$sku}'. Error: " . $e->getMessage() . "\n";
    }
}

// Test creating a book product with complete and correct data
createBookProduct("BOOK555", "The Great Book", 25.99, 1.2);

// Test creating a book product with missing data
createBookProduct("BOOK101", "", -25.99, -1.2); // Intentionally incorrect to trigger errors

// Test updating a book product
updateBookProduct("BOOK555", "The Greatest Book", 27.99, 1.5);

// Fetch and display all books to see the changes
fetchAllBooks();

// Test deleting a book product
deleteBookProduct("BOOK555");

// Attempt to fetch and display all books after deletion
fetchAllBooks();

// Note: Adjust SKU values and parameters as needed based on your database state.
