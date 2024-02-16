document.addEventListener("DOMContentLoaded", function() {
    const productTypeSelector = document.getElementById("productType");
    const specificFieldsContainer = document.getElementById("specificFields");
    const form = document.getElementById("product-form");
    const typeError = document.getElementById("typeError");
    const priceInput = document.getElementById("price");
    const priceError = document.getElementById("priceError");


    function hideErrorMessage() {
        if (productTypeSelector.value !== "") {
            typeError.style.display = 'none'; // Hide the error message if a product type is selected
        }
    }

    // Function to dynamically update form fields based on the selected product type
    function updateFormFields() {
        const type = productTypeSelector.value;
        specificFieldsContainer.innerHTML = ''; // Clear previous fields
        hideErrorMessage(); // Hide the product type error message when a valid type is selected


        let specificFieldsHTML = '';
        switch (type) {
            case 'DVD':
                specificFieldsHTML = `
                    <div>
                        <label for="size">Size (MB):</label>
                        <input type="number" id="size" name="size" min="0.01" step="0.01" required>
                        <div id="sizeError" class="error-message" style="display: none; color: red;">Size must be greater than 0.</div>
                    </div>`;
                break;
            case 'Book':
                specificFieldsHTML = `
                    <div>
                        <label for="weight">Weight (KG):</label>
                        <input type="number" id="weight" name="weight" min="0.01" step="0.01" required>
                        <div id="weightError" class="error-message" style="display: none; color: red;">Weight must be greater than 0.</div>
                    </div>`;
                break;
            case 'Furniture':
                specificFieldsHTML = `
                    <div>
                        <label for="height">Height (CM):</label>
                        <input type="number" id="height" name="height" min="0.01" step="0.01" required>
                        <label for="width">Width (CM):</label>
                        <input type="number" id="width" name="width" min="0.01" step="0.01" required>
                        <label for="length">Length (CM):</label>
                        <input type="number" id="length" name="length" min="0.01" step="0.01" required>
                        <div id="dimensionsError" class="error-message" style="display: none; color: red;">All dimensions must be greater than 0.</div>
                    </div>`;
                break;
        }

        specificFieldsContainer.innerHTML = specificFieldsHTML;
    }

    // Validate the form on submission
    function validateForm(event) {
        let isValid = true;

        // Validate product type selection
        if (productTypeSelector.value === "") {
            typeError.style.display = 'block';
            isValid = false;
        } else {
            typeError.style.display = 'none';
        }

        // Validate price
        if (parseFloat(priceInput.value) <= 0) {
            priceError.style.display = 'block';
            isValid = false;
        } else {
            priceError.style.display = 'none';
        }

        // Validate specific attributes
        const sizeInput = document.getElementById("size");
        const sizeError = document.getElementById("sizeError");
        const weightInput = document.getElementById("weight");
        const weightError = document.getElementById("weightError");
        const dimensionsError = document.getElementById("dimensionsError");
        const heightInput = document.getElementById("height");
        const widthInput = document.getElementById("width");
        const lengthInput = document.getElementById("length");

        if (productTypeSelector.value === 'DVD' && sizeInput && parseFloat(sizeInput.value) <= 0) {
            sizeError.style.display = 'block';
            isValid = false;
        } else if (sizeError) {
            sizeError.style.display = 'none';
        }

        if (productTypeSelector.value === 'Book' && weightInput && parseFloat(weightInput.value) <= 0) {
            weightError.style.display = 'block';
            isValid = false;
        } else if (weightError) {
            weightError.style.display = 'none';
        }

        if (productTypeSelector.value === 'Furniture' && (parseFloat(heightInput.value) <= 0 || parseFloat(widthInput.value) <= 0 || parseFloat(lengthInput.value) <= 0)) {
            dimensionsError.style.display = 'block';
            isValid = false;
        } else if (dimensionsError) {
            dimensionsError.style.display = 'none';
        }

        if (!isValid) {
            event.preventDefault();
        }
    }

    productTypeSelector.addEventListener("change", updateFormFields);
    form.addEventListener("submit", validateForm);
    updateFormFields();
});
