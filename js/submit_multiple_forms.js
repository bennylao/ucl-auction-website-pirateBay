// Function to handle the submission of the dropdowns and text input
function handleSubmit(event) {
    // Prevent the default form submission if this function is called in response to the form's submit event
    if (event) event.preventDefault();

    // Get the value from the text input
    var searchKeyword = document.getElementById('search_keyword').value;
    // Get the value from the first dropdown
    var category = document.getElementById('category').value;

    var c1 = document.getElementById('Brand new');
    var c2= document.getElementById('Like new');
    var c3 = document.getElementById('Very good');
    var c4 = document.getElementById('Good');
    var c5 = document.getElementById('Acceptable');
    var c6 = document.getElementById('Certified Refurbished');
    var c7 = document.getElementById('For Parts or Not Working');

    var c1_value = c1.checked ? c1.value : "";
    var c2_value = c2.checked ? c2.value : "";
    var c3_value = c3.checked ? c3.value : "";
    var c4_value = c4.checked ? c4.value : "";
    var c5_value = c5.checked ? c5.value : "";
    var c6_value = c6.checked ? c6.value : "";
    var c7_value = c7.checked ? c7.value : "";

    var sortBy = document.getElementById('sort_by').value;
    // Get the value from the second dropdown
    var itemsPerPage = document.getElementById('items_per_page').value;

    // Redirect the browser to browse.php with the query parameters
    window.location.href = 'browse.php?search_keyword=' + encodeURIComponent(searchKeyword) +
        '&category=' + encodeURIComponent(category) +
        '&condition=' + encodeURIComponent(c1_value) + encodeURIComponent(c2_value) + encodeURIComponent(c3_value) +
        encodeURIComponent(c4_value) + encodeURIComponent(c5_value) + encodeURIComponent(c6_value) + encodeURIComponent(c7_value) +
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
