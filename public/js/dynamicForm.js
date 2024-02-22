document.addEventListener("DOMContentLoaded", function () {
    const productTypeSelector = document.getElementById("productType");
    const specificFieldsContainer = document.getElementById("specificFields");
    const form = document.getElementById("product-form");
    const typeError = document.getElementById("typeError");
    const priceInput = document.getElementById("price");
    const priceError = document.getElementById("priceError");

    // Hides the error message for product type if one is selected
    function hideErrorMessage() {
        if (productTypeSelector.value !== "") {
            typeError.style.display = 'none';
        }
    }

    // Updates form fields based on selected product type
    function updateFormFields() {
        const type = productTypeSelector.value;
        specificFieldsContainer.innerHTML = ''; // Clears previous specific fields
        hideErrorMessage(); // Hides error message if product type is selected

        let specificFieldsHTML = '';
        switch (type) {
            case 'DVD':
                specificFieldsHTML = `
                    <div>
                        <label for="size">Size(MB):</label>
                        <input type="number" id="size" name="size" min="0.01" step="0.01" required>
                        <div id="sizeError" class="error-message" style="display: none; color: red;">Size must be greater than 0.</div>
                    </div>`;
                break;
            case 'Book':
                specificFieldsHTML = `
                    <div>
                        <label for="weight">Weight(KG):</label>
                        <input type="number" id="weight" name="weight" min="0.01" step="0.01" required>
                        <div id="weightError" class="error-message" style="display: none; color: red;">Weight must be greater than 0.</div>
                    </div>`;
                break;
            case 'Furniture':
                specificFieldsHTML = `
                    <div>
                        <label for="height">Height(CM):</label>
                        <input type="number" id="height" name="height" min="0.01" step="0.01" required>
                        <label for="width">Width(CM):</label>
                        <input type="number" id="width" name="width" min="0.01" step="0.01" required>
                        <label for="length">Length(CM):</label>
                        <input type="number" id="length" name="length" min="0.01" step="0.01" required>
                        <div id="dimensionsError" class="error-message" style="display: none; color: red;">All dimensions must be greater than 0.</div>
                    </div>`;
                break;
        }

        specificFieldsContainer.innerHTML = specificFieldsHTML; // Inserts new specific fields based on product type
    }

    // Validates the entire form on submission
    function validateForm(event) {
        let isValid = true;

        // Validates product type selection
        if (productTypeSelector.value === "") {
            typeError.style.display = 'block'; // Shows error if product type is not selected
            isValid = false;
        } else {
            typeError.style.display = 'none';
        }

        // Validates price input
        if (parseFloat(priceInput.value) <= 0) {
            priceError.style.display = 'block'; // Shows error if price is not valid
            isValid = false;
        } else {
            priceError.style.display = 'none';
        }

        // Validates specific attributes based on product type
        const sizeInput = document.getElementById("size");
        const sizeError = document.getElementById("sizeError");
        const weightInput = document.getElementById("weight");
        const weightError = document.getElementById("weightError");
        const dimensionsError = document.getElementById("dimensionsError");
        const heightInput = document.getElementById("height");
        const widthInput = document.getElementById("width");
        const lengthInput = document.getElementById("length");

        if (productTypeSelector.value === 'DVD' && sizeInput && parseFloat(sizeInput.value) <= 0) {
            sizeError.style.display = 'block'; // Shows error if DVD size is not valid
            isValid = false;
        } else if (sizeError) {
            sizeError.style.display = 'none';
        }

        if (productTypeSelector.value === 'Book' && weightInput && parseFloat(weightInput.value) <= 0) {
            weightError.style.display = 'block'; // Shows error if book weight is not valid
            isValid = false;
        } else if (weightError) {
            weightError.style.display = 'none';
        }

        if (productTypeSelector.value === 'Furniture' && (parseFloat(heightInput.value) <= 0 || parseFloat(widthInput.value) <= 0 || parseFloat(lengthInput.value) <= 0)) {
            dimensionsError.style.display = 'block'; // Shows error if any furniture dimension is not valid
            isValid = false;
        } else if (dimensionsError) {
            dimensionsError.style.display = 'none';
        }

        if (!isValid) {
            event.preventDefault(); // Prevents form submission if validation fails
        }
    }

    productTypeSelector.addEventListener("change", updateFormFields); // Listens for change on product type selector to update form
    form.addEventListener("submit", validateForm); // Validates form on submission
    updateFormFields(); // Initial call to set up form fields based on default or previously selected product type
});
