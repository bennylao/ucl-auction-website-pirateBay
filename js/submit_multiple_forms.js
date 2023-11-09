// Function to handle the submission of the dropdowns and text input
function handleSubmit(event) {
    // Prevent the default form submission if this function is called in response to the form's submit event
    if (event) event.preventDefault();

    // Get the value from the text input
    var searchKeyword = document.getElementById('search_keyword').value;
    // Get the value from the first dropdown
    var category = document.getElementById('category').value;
    var sortBy = document.getElementById('sort_by').value;
    // Get the value from the second dropdown
    var itemsPerPage = document.getElementById('items_per_page').value;

    // Redirect the browser to browse.php with the query parameters
    window.location.href = 'browse.php?search_keyword=' + encodeURIComponent(searchKeyword) +
        '&category=' + encodeURIComponent(category) +
        '&sort_by=' + encodeURIComponent(sortBy) +
        '&items_per_page=' + encodeURIComponent(itemsPerPage);
}

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Event listener for the form submit
    document.getElementById('filter_bar').addEventListener('submit', handleSubmit);

    // Event listener for the onchange event of the second dropdown
    document.getElementById('items_per_page').addEventListener('change', handleSubmit);
});
