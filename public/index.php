<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <h1>Product Catalog</h1>
        <a href="add-product.php" class="button">ADD</a>
        <button id="delete-product-btn" class="button">MASS DELETE</button>
    </header>
    <form id="product-list" method="post">
        <?php
        require_once '../classes/Database.php';
        require_once '../classes/DVDProduct.php';
        require_once '../classes/BookProduct.php';
        require_once '../classes/FurnitureProduct.php';

        // Fetch all products
        $allProducts = array_merge(DVDProduct::fetchAll(), BookProduct::fetchAll(), FurnitureProduct::fetchAll());
        // Sort products by SKU
        usort($allProducts, function ($a, $b) { return $a['sku'] <=> $b['sku']; });

        foreach ($allProducts as $product) {
            echo "<div class='product-item'>";
            echo "<input type='checkbox' class='delete-checkbox' name='delete-checkbox[]' value='{$product['sku']}' />";
            echo "<h3>{$product['name']}</h3><p>SKU: {$product['sku']}</p><p>Price: \${$product['price']}</p>";
            if (isset($product['size'])) {
                echo "<p>Size: {$product['size']} MB</p>";
            } elseif (isset($product['weight'])) {
                echo "<p>Weight: {$product['weight']} Kg</p>";
            } elseif (isset($product['height']) && isset($product['width']) && isset($product['length'])) {
                echo "<p>Dimensions: {$product['height']}x{$product['width']}x{$product['length']} cm</p>";
            }
            echo "</div>";
        }        
        ?>
    </form>
    <footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
