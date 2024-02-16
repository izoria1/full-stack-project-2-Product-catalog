document.addEventListener("DOMContentLoaded", function() {
    const productTypeSelector = document.getElementById("productType");
    const specificFieldsContainer = document.getElementById("specificFields");
    const form = document.getElementById("product-form");
    const typeError = document.getElementById("typeError"); // Get the error message container
    const priceInput = document.getElementById("price");
    const priceError = document.getElementById("priceError"); // Get the price error message container

    // Function to hide error message when a valid product type is selected
    function hideErrorMessage() {
        if (productTypeSelector.value !== "") {
            typeError.style.display = 'none'; // Hide the error message
        }
    }

    // Dynamically update form fields based on the selected product type
    function updateFormFields() {
        const type = productTypeSelector.value;
        specificFieldsContainer.innerHTML = ''; // Clear previous fields

        // Hide error message when changing product type
        hideErrorMessage();

        // Object holding the HTML for each product type's specific fields
        const typeFields = {
            "DVD": `
                <label for="size">Size (MB):</label>
                <input type="number" id="size" name="size" required>`,
            "Book": `
                <label for="weight">Weight (KG):</label>
                <input type="number" id="weight" name="weight" step="0.01" required>`,
            "Furniture": `
                <label for="height">Height (CM):</label>
                <input type="number" id="height" name="height" required>
                <label for="width">Width (CM):</label>
                <input type="number" id="width" name="width" required>
                <label for="length">Length (CM):</label>
                <input type="number" id="length" name="length" required>`
        };

        specificFieldsContainer.innerHTML = typeFields[type] || '';
    }

    // Bind event listener to the product type selector to update form fields and hide the error message
    productTypeSelector.addEventListener("change", updateFormFields);

    // Enhance validateForm function to include price validation
    function validateForm(event) {
        let isValid = true;

        // Check if the product type is selected
        if (productTypeSelector.value === "") {
            typeError.style.display = 'block'; // Show the error message
            isValid = false;
        } else {
            typeError.style.display = 'none'; // Hide the error message
        }

        // Validate price is greater than 0
        const priceValue = parseFloat(priceInput.value);
        if (isNaN(priceValue) || priceValue <= 0) {
            priceError.style.display = 'block'; // Show the price error message
            isValid = false;
        } else {
            priceError.style.display = 'none'; // Hide the price error message
        }

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    }

    // Bind the validateForm function to form submission event
    form.addEventListener("submit", validateForm);

    // Initial update to ensure form matches the default or initial selection
    updateFormFields();
});
