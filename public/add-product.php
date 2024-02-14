<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Link to CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Add Product</h1>
    </header>
    <section>
        <form id="product-form" action="../actions/product_action.php" method="post">
            <!-- Hidden input to specify the action -->
            <input type="hidden" name="action" value="create">

            <label for="sku">SKU:</label>
            <input type="text" id="sku" name="sku" required>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="price">Price ($):</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="productType">Type Switcher:</label>
            <select id="productType" name="type">
                <option value="">Select Type</option> <!-- Default option -->
                <option value="DVD">DVD</option>
                <option value="Book">Book</option>
                <option value="Furniture">Furniture</option>
            </select>
            
            <!-- Additional fields will be inserted here based on the selected product type -->
            <div id="specificFields"></div>
            
            <button type="submit">Save</button>
            <a href="index.php" class="button">Cancel</a>
        </form>
    </section>
    <footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
    <!-- Link to JavaScript for dynamic form handling -->
    <script src="dynamicForm.js"></script>
</body>
</html>
