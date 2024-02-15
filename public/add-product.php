<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Add Product</h1>
    </header>
    <main> <!-- Use main for the primary content -->
        <section class="form-section"> <!-- Added class for specific styling -->
            <form id="product-form" action="../actions/product_action.php" method="post" class="product-form">
                <input type="hidden" name="action" value="create">
                <div class="form-group"> <!-- Grouping each input field -->
                    <label for="sku">SKU:</label>
                    <input type="text" id="sku" name="sku" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="price">Price ($):</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="productType">Type Switcher:</label>
                    <select id="productType" name="type">
                        <option value="">Select Type</option>
                        <option value="DVD">DVD</option>
                        <option value="Book">Book</option>
                        <option value="Furniture">Furniture</option>
                    </select>
                </div>
                <div id="specificFields"></div>
                <div class="form-actions"> <!-- For buttons -->
                    <button type="submit" class="btn-save">Save</button>
                    <a href="index.php" class="button cancel">Cancel</a>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <p>Scandiweb Test Assignment</p>
    </footer>
    <script src="js/dynamicForm.js"></script>
</body>
</html>
