document.addEventListener("DOMContentLoaded", function() {
    const productTypeSelector = document.getElementById("productType");
    const specificFieldsContainer = document.getElementById("specificFields");
    const form = document.getElementById("product_form");

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

    // Validate the form before submission
    function validateForm(event) {
        const type = productTypeSelector.value;
        if (!type) {
            alert("Please select a product type.");
            event.preventDefault();
            return false;
        }
        // Additional validation logic as needed...
    }

    // AJAX form submission
    function submitForm(event) {
        event.preventDefault(); // Prevent default submission

        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                window.location.href = 'index.php'; // Redirect on success
            } else {
                alert(data.message); // Show error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    // Bind event listeners
    productTypeSelector.addEventListener("change", updateFormFields);
    form.addEventListener("submit", function(event) {
        validateForm(event);
        submitForm(event);
    });

    // Initial update to ensure form matches the default or initial selection
    updateFormFields();
});
