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

    <!-- Background layers for aesthetic enhancement -->
    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>

    <main>
        <!-- Section containing the product addition form -->
        <section class="form-section">
            <!-- Product form for data submission -->
            <form id="product-form" action="../actions/product_action.php" method="post" class="product-form">
                <!-- Hidden input to define the form action type -->
                <input type="hidden" name="action" value="create">
                <!-- SKU input field -->
                <div class="form-group">
                    <label for="sku">SKU:</label>
                    <input type="text" id="sku" name="sku" required>
                </div>
                <!-- Product name input field -->
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <!-- Price input field with validation message placeholder -->
                <div class="form-group">
                    <label for="price">Price ($):</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                    <div id="priceError" class="error-message" style="display: none; color: red;">Price must be greater than 0.</div>
                </div>
                <!-- Product type selection dropdown -->
                <div class="form-group">
                    <label for="productType">Type Switcher:</label>
                    <select id="productType" name="type">
                        <option value="">Select Type</option>
                        <option value="DVD">DVD</option>
                        <option value="Book">Book</option>
                        <option value="Furniture">Furniture</option>
                    </select>
                    <div id="typeError" class="error-message" style="display: none; color: red;">Please select a product type.</div>
                </div>
                <!-- Placeholder for dynamically added product-specific fields -->
                <div id="specificFields"></div>
                <!-- Form submission and cancellation actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-save">Save</button>
                    <a href="index.php" class="button cancel">Cancel</a>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <p>Test Assignment</p>
    </footer>
    <!-- Script for handling dynamic form behavior -->
    <script src="js/dynamicForm.js"></script>
</body>
</html>
