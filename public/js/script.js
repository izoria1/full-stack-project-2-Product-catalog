$(document).ready(function () {
    $('#delete-product-btn').click(function () {
        const confirmed = confirm('Are you sure you want to delete the selected products?');
        if (confirmed) {
            const skusToDelete = $('.delete-checkbox:checked').map(function () {
                return this.value;
            }).get();
            
            $.ajax({
                url: '../actions/delete_product_action.php', // Path to your delete action
                type: 'POST',
                data: {skus: skusToDelete},
                success: function(response) {
                    // Reload the page to reflect the changes
                    location.reload();
                },
                error: function() {
                    alert('Error deleting products.');
                }
            });
        }
    });
});
