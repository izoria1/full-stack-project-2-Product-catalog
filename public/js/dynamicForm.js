document.addEventListener("DOMContentLoaded", function() {
    const productTypeSelector = document.getElementById("productType");
    const specificFieldsContainer = document.getElementById("specificFields");

    // Dynamically update form fields based on the selected product type
    function updateFormFields() {
        const type = productTypeSelector.value;
        specificFieldsContainer.innerHTML = ''; // Clear previous fields

        // Object holding the HTML for each product type's specific fields
        const typeFields = {
            "DVD": `
                <label for="size">Size (MB):</label>
                <input type="number" id="size" name="size" required>`,
            "Book": `
                <label for="weight">Weight (KG):</label>
                <input type="number" id="weight" name="weight" required>`,
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

    // Bind event listener to the product type selector
    productTypeSelector.addEventListener("change", updateFormFields);

    // Initial update to ensure form matches the default or initial selection
    updateFormFields();
});
