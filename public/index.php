<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <!-- Link to external CSS for styling -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Import jQuery library for easier DOM manipulation -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <h1>Product Catalog</h1>
        <div class="container">
            <!-- Button to navigate to the product addition page -->
            <a href="add-product.php">
                <button id="add-product-btn">
                    <!-- Icon representing the addition action -->
                    <img src="images/plus.png" class="plus-icon" alt="add button" />
                </button>
            </a>
    
            <!-- Button for triggering the deletion of selected products -->
            <button id="delete-product-btn">
                <!-- Icon representing the deletion action -->
                <img src="images/delete.png" class="delete-icon" alt="delete button" />
            </button>
        </div>
    </header>

    <!-- Background layers for aesthetic purposes -->
    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>

    <!-- Form for listing products with deletion checkboxes -->
    <form id="product-list" method="post">
        <?php
        // Include necessary class files
        require_once '../classes/Database.php';
        require_once '../classes/DVDProduct.php';
        require_once '../classes/BookProduct.php';
        require_once '../classes/FurnitureProduct.php';

        // Aggregate all products from different categories
        $allProducts = array_merge(DVDProduct::fetchAll(), BookProduct::fetchAll(), FurnitureProduct::fetchAll());
        // Sort the aggregated products by SKU for display
        usort($allProducts, function ($a, $b) {
            return $a['sku'] <=> $b['sku'];
        });

        // Display each product with relevant information and a deletion checkbox
        foreach ($allProducts as $product) {
            echo "<div class='product-item'>";
            // Checkbox for selecting the product for deletion
            echo "<input type='checkbox' class='delete-checkbox' name='delete-checkbox[]' value='{$product['sku']}' />";
            // Display the SKU, name, and price of the product
            echo "<p>{$product['sku']}</p>"; // Display the SKU
            echo "<h3>{$product['name']}</h3><p>Price: \${$product['price']}</p>"; // Display the name and price
            // Conditionally display product-specific attributes
            if (isset($product['size'])) {
                echo "<p>Size: {$product['size']} MB</p>"; // For DVDs
            } elseif (isset($product['weight'])) {
                echo "<p>Weight: {$product['weight']} Kg</p>"; // For books
            } elseif (isset($product['height']) && isset($product['width']) && isset($product['length'])) {
                // For furniture, display dimensions
                echo "<p>Dimensions: {$product['height']}x{$product['width']}x{$product['length']} cm</p>";
            }
            echo "</div>";
        }
        ?>
    </form>
    
    <footer>
        <!-- Placeholder for footer content -->
        <p>Test Assignment</p>
    </footer>
    <!-- Link to external JavaScript file for additional interactivity -->
    <script src="js/script.js"></script>
</body>
</html>
