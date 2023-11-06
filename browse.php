<?php
$title = "Auction Browse";
include_once ("header.php");
require ("utilities.php");
require_once ("config_database.php")
?>


<div class="container">


<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-4 pr-0">
      <div class="form-group">
        <label for="search_keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
            <!-- search engine-->
          <input type="text" class="form-control border-left-0" id="search_keyword" placeholder="Search for anything">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="category" class="sr-only">Search within:</label>
        <select class="form-control" id="category">
          <option selected value="all">All categories</option>
          <option value="fill">Fill me in</option>
          <option value="with">with options</option>
          <option value="populated">populated from a database?</option>
        </select>
      </div>
    </div>
    <div class="col-md-2 pr-0">
      <div class="form-group">
        <label class="sr-only" for="sort_by">Sort by:</label>
        <select class="form-control" id="sort_by">
          <option selected value="price_low">Price (low to high)</option>
          <option value="price_high">Price (high to low)</option>
          <option value="end_soon">Ending soonest</option>
            <option value="list_new">Newly listed</option>
        </select>
      </div>
    </div>
    <div class="col-md-2 pr-0">
        <div class="form-group">
            <label class="sr-only" for="items_per_page">Number of items:</label>
            <select class="form-control" id="items_per_page">
                <option selected value=5>Number of items: 5</option>
                <option value=10>Number of items: 10</option>
                <option value=25>Number of items: 25</option>
                <option value=50>Number of items: 50</option>
            </select>
        </div>
    </div>
    <div class="col-md-1 pr-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->


</div>

<?php
// Retrieve these from the URL
if (!isset($_GET['search_keyword'])) {
    // TODO: Define behavior if a keyword has not been specified.
    $keyword = "";
}
else {
    $keyword = $_GET['search_keyword'];
}

if (!isset($_GET['category'])) {
    // TODO: Define behavior if a category has not been specified.
    $category = "all";
}
else {
    $category = $_GET['category'];
}

if (!isset($_GET['sort_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
}
else {
    $ordering = $_GET['sort_by'];
}

if (!isset($_GET['items_per_page'])) {
    // TODO: Define behavior if an order_by value has not been specified.
    $items_per_page = 10;
}
else {
    $items_per_page = $_GET['items_per_page'];
}

if (!isset($_GET['page'])) {
$curr_page = 1;
}
else {
$curr_page = $_GET['page'];
}

/* TODO: Use above values to construct a query. Use this query to
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
  
/* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
$num_results = 96; // TODO: Calculate me for real
$max_page = ceil($num_results / $items_per_page);
?>

<div class="container mt-5">

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

<ul class="list-group">

<!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->

<?php

// Create database connection
$connection = connect_to_database()
or die('Error connecting to MySQL server.' . mysqli_error());;

// SQL to fetch data
    $query = "SELECT itemId, itemTitle, category, description, currentPrice, numBids, endDateTime FROM items";
    $result = mysqli_query($connection,$query);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $itemId = $row["itemId"];
        $itemTitle = $row["itemTitle"];
        $category = $row["category"];
        $description = $row["description"];
        $currentPrice = $row["currentPrice"];
        $numBids = $row["numBids"];
        $endDateTime = new DateTime($row["endDateTime"]);
        // This uses a function defined in utilities.php
        print_listing_li($itemId, $itemTitle, $category, $description, $currentPrice, $numBids, $endDateTime);
    }
} else {
    echo "No results found.";
}
mysqli_close($connection);
?>


</ul>

<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>