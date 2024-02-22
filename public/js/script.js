$(document).ready(function () {
    // Listen for click events on the 'delete-product-btn' button
    $('#delete-product-btn').click(function () {
        // Confirm with the user before proceeding with deletion
        const confirmed = confirm('Are you sure you want to delete the selected products?');
        if (confirmed) {
            // Collect all checked checkboxes' values (SKUs) to delete
            const skusToDelete = $('.delete-checkbox:checked').map(function () {
                return this.value;
            }).get();
            
            // Send an AJAX POST request to delete selected products
            $.ajax({
                url: '../actions/delete_product_action.php', // Specifies the URL to send the request to
                type: 'POST', // Specifies the request type
                data: {skus: skusToDelete}, // Data to be sent to the server
                success: function (response) {
                    // Refresh the page to show the updated product list after deletion
                    location.reload();
                },
                error: function () {
                    // Notify the user if there was an error during the deletion process
                    alert('Error deleting products.');
                }
            });
        }
    });
});
