<?php
$title = "Auction Browse";
include_once("header.php");
require("utilities.php");
require_once("config_database.php")
?>


  <div class="container">

    <h2 class="my-3">Browse listings</h2>

    <form action="browse.php" id="filter_bar" method="GET">
      <div class="row">
        <div class="col-md-5 pr-0">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <label class="input-group-text" for="search_keyword"><i class="fa fa-search"></i></label>
              </div>
              <input type="text" id="search_keyword" name="search_keyword" class="form-control border-left-0"
                     placeholder="Search for anything" <?php if (isset($_GET['search_keyword'])) echo "value=" . $_GET['search_keyword']; ?>>
            </div>

          </div>
        </div>

        <div class="col-md-4 pr-0">
          <div class="form-group">
            <label for="category" class="sr-only">Search within:</label>
            <select class="form-control" id="category" name="category">
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "All") echo "selected"; ?> value="All">
                All categories
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Art And Collectibles") echo "selected"; ?>
                  value="Art And Collectibles"> Art and Collectibles
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Antiques") echo "selected"; ?>
                  value="Antiques"> Antiques
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Automobiles And Vehicles") echo "selected"; ?>
                  value="Automobiles And Vehicles"> Automobiles and Vehicles
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Jewelry And Watches") {
                  echo "selected='selected'";
              } ?> value="Jewelry And Watches"> Jewelry and Watches
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Electronics And Technology") {
                  echo "selected='selected'";
              } ?> value="Electronics And Technology"> Electronics and Technology
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Fashion And Apparel") echo "selected"; ?>
                  value="Fashion And Apparel"> Fashion and Apparel
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Sports And Memorabilia") echo "selected"; ?>
                  value="Sports And Memorabilia"> Sports and Memorabilia
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Wine And Spirits") echo "selected"; ?>
                  value="Wine And Spirits"> Wine and Spirits
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Furniture And HomeDecor") echo "selected"; ?>
                  value="Furniture And HomeDecor"> Furniture and Home Decor
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Real Estate") echo "selected"; ?>
                  value="Real Estate"> Real Estate
              </option>
              <option <?php if (isset($_GET['category']) && $_GET['category'] == "Others") echo "selected"; ?>
                  value="Others"> Others
              </option>
            </select>
          </div>
        </div>

        <div class="col-md-2 pr-0">
          <div class="form-group">
            <label class="sr-only" for="sort_by">Sort by:</label>
            <select class="form-control" id="sort_by" name="sort_by">
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "numBids DESC") echo "selected"; ?>
                  value="numBids DESC"> Popularity
              </option>
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "currentPrice") echo "selected"; ?>
                  value="currentPrice">Price (low to high)
              </option>
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "currentPrice DESC") echo "selected"; ?>
                  value="currentPrice DESC">Price (high to low)
              </option>
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "endDateTime") echo "selected"; ?>
                  value="endDateTime">Ending soonest
              </option>
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "endDateTime DESC") echo "selected"; ?>
                  value="endDateTime DESC">Newly listed
              </option>

            </select>
          </div>
        </div>

        <div class="col-md-1">
          <button type="submit" value="Submit" class="btn btn-primary">Search</button>
        </div>

      </div>

      <div class="col-md-3 pr-0">

      </div>

    </form>
  </div>
  <!-- end search specs bar -->


<?php


// Retrieve these from the URL
if (!isset($_GET['search_keyword']) or empty($_GET['search_keyword'])) {
    // TODO: Define behavior if a keyword has not been specified.
    $keyword = "%";
} else {
    $keyword = "%" . $_GET['search_keyword'] . "%";
}

// Only do filtering if category is selected and the category is not "all"
// Otherwise do nothing
if (isset($_GET['category']) and $_GET['category'] != 'All') {
    // TODO: Define behavior if a category has not been specified.
    $category = $_GET['category'];
    $category_query = " AND category = '$category'";
} else {
    $category_query = "";
}

// Retrieve the ordering method
if (!isset($_GET['sort_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
    $ordering = "numBids DESC";
} else {
    $ordering = $_GET['sort_by'];
}

if (!isset($_GET['items_per_page'])) {
    $items_per_page = 5;
} else {
    $items_per_page = $_GET['items_per_page'];
}

if (!isset($_GET['page'])) {
    $curr_page = 1;
} else {
    $curr_page = $_GET['page'];
}

$offset = (($curr_page - 1) * $items_per_page);

/* TODO: Use above values to construct a query. Use this query to
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
$item_query = "SELECT itemId, itemTitle, category, description, currentPrice, numBids, endDateTime FROM items 
            WHERE (itemTitle LIKE '$keyword' or category LIKE '$keyword' or description LIKE '$keyword' or brand LIKE '$keyword') 
            AND endDateTime > NOW()
            $category_query
            ORDER BY  $ordering, itemTitle
            LIMIT $items_per_page OFFSET $offset;";

$count_item_query = "SELECT COUNT(*) FROM items 
            WHERE (itemTitle LIKE '$keyword' or category LIKE '$keyword' or description LIKE '$keyword' or brand LIKE '$keyword') 
            AND items.endDateTime > NOW()
            $category_query
            ORDER BY  $ordering, itemTitle;";
/* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */

?>

  <div class="container mt-5">

    <!-- TODO: If result set is empty, print an informative message. Otherwise... -->

    <ul class="list-group">

      <!-- TODO: Use a while loop to print a list item for each auction listing
           retrieved from the query -->


        <?php

        // Create database connection
        $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

        // SQL to fetch data
        $result = mysqli_query($connection, $item_query);

        // Calculate the number of rows that meet the query criteria
        $count_item_result = mysqli_query($connection, $count_item_query);
        $num_item = mysqli_fetch_array($count_item_result)[0];

        $max_page = ceil($num_item / $items_per_page);


        if (isset($_GET['search_keyword']) and isset($_GET['category'])) {
            echo "<div class='text-muted'> Found $num_item results. Showing page $curr_page of $max_page.</div>";
        }


        if ($num_item > 0) {
            // Output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
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
      <div class="row">
        <div class="col">
          <ul class="pagination justify-content-center">
              <?php
              // PHP code to generate pagination links
              // Ensure your PHP code here generates the pagination items.

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
                  echo('<li class="page-item">
            <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
            <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
            <span class="sr-only">Previous</span>
            </a>
            </li>');
              }

              for ($i = $low_page; $i <= $high_page; $i++) {
                  if ($i == $curr_page) {
                      // Highlight the link
                      echo('<li class="page-item active">');
                  } else {
                      // Non-highlighted link
                      echo('<li class="page-item">');
                  }

                  // Do this in any case
                  echo('<a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>');
              }

              if ($curr_page < $max_page) {
                  echo('<li class="page-item">
            <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
            <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
            <span class="sr-only">Next</span>
            </a>
            </li>');
              }
              ?>
          </ul>
        </div>

        <div class="col-auto ml-auto">
          <form class="form-inline" method="GET" action="browse.php">
            <label for="items_per_page" class="mb-2 mr-2">Showing</label>
            <select class="custom-select mb-2 mr-2" id="items_per_page" name="items_per_page"
                    onchange="this.form.submit()">
              <option
                  value="5" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == "5") echo "selected"; ?>>
                5
              </option>
              <option
                  value="10" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == "10") echo "selected"; ?>>
                10
              </option>
              <option
                  value="25" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == "25") echo "selected"; ?>>
                25
              </option>
              <option
                  value="50" <?php if (isset($_GET['items_per_page']) && $_GET['items_per_page'] == "50") echo "selected"; ?>>
                50
              </option>
            </select>
            <label for="items_per_page" class="mb-2 mr-2">items per page</label>
          </form>
        </div>
      </div>
    </nav>

  </div>

  <!-- Link to JavaScript file -->
  <script src="js/submit_multiple_forms.js"></script>
<?php include_once("footer.php") ?>