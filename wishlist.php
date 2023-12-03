<?php
$title = "Auction Wishlist";
include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php require_once("config_database.php") ?>


<?php
// Create database connection
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>

  <div class="container">

    <h2 class="my-3">Wishlist</h2>

    <form action="wishlist.php" id="filter_bar" method="GET">
      <div class="row">

        <!-- Searching Input -->
        <div class="col-md-5 pr-0">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <label class="input-group-text" for="search_keyword"><i class="fa fa-search"></i></label>
              </div>
              <input type="text" id="search_keyword" name="search_keyword" class="form-control border-left-0"
                     title="search for anything you want"
                     placeholder="Search for anything" <?php if (isset($_GET['search_keyword'])) echo "value='" . "$_GET[search_keyword]"."'"; ?>>
            </div>

          </div>
        </div>
        <!-- Searching Input End-->

        <!-- Category Selection Dropdown -->
        <div class="col-md-4 pr-0">
          <div class="form-group">
            <label for="category" class="sr-only">Search within:</label>
            <select class="form-control" id="category" name="category">
              <option <?php if (isset($_GET['category']) && $_GET['category'] == 0) echo "selected"; ?> value=0>
                All categories
              </option>
                <?php
                $find_categories_query = "SELECT * FROM categories";
                // SQL to fetch data
                $result = mysqli_query($connection, $find_categories_query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $category = $row['category'];
                    $categoryId = $row['cateId'];
                    if (isset($_GET['category']) && $_GET['category'] == $categoryId) {
                        $isSelected = "selected";
                    } else {
                        $isSelected = "";
                    }

                    echo "<option value=$categoryId $isSelected> $category </option>";
                }
                ?>
            </select>
          </div>
        </div>
        <!-- Category Selection Dropdown End-->

        <!-- Sorting Dropdown -->
        <div class="col-md-2 pr-0">
          <div class="form-group">
            <label class="sr-only" for="sort_by">Sort by:</label>
            <select class="form-control" id="sort_by" name="sort_by">
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "COUNT(b.itemId) DESC") echo "selected"; ?>
                  value="COUNT(b.itemId) DESC"> Popularity
              </option>
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "GREATEST(COALESCE(MAX(b.bidPrice), 0), i.startingPrice)") echo "selected"; ?>
                  value="GREATEST(COALESCE(MAX(b.bidPrice), 0), i.startingPrice)">Price (low to high)
              </option>
              <option <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == "GREATEST(COALESCE(MAX(b.bidPrice), 0), i.startingPrice) DESC") echo "selected"; ?>
                  value="GREATEST(COALESCE(MAX(b.bidPrice), 0), i.startingPrice) DESC">Price (high to low)
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
        <!-- Sorting Dropdown End -->

        <!-- Submit Button -->
        <div class="col-md-1">
          <button type="submit" value="Submit" class="btn btn-primary">Search</button>
        </div>
        <!-- Submit Button End -->
      </div>

      <div class="row">

        <!-- Conditions Checkbox -->
        <label class="col-sm-1">Conditions: </label>
        <div class="col-md-11 pr-0">
            <?php
            $find_conditions_query = "SELECT * FROM conditions";
            // SQL to fetch data
            $result = mysqli_query($connection, $find_conditions_query);
            while ($row = mysqli_fetch_assoc($result)) {
                $conditionDescription = $row['condDescript'];
                $conditionId = $row['conditionId'];
                if (!isset($_GET['conditions']) || strpos($_GET['conditions'], (string)$conditionId) !== false) {
                    $isChecked = "checked";
                } else {
                    $isChecked = "";
                }

                echo "<div class='form-check form-check-inline'>
            <input class='form-check-input' type='checkbox' id='$conditionDescription' name='conditions' value=$conditionId $isChecked>
            <label class='form-check-label' for='$conditionDescription'>$conditionDescription</label>
          </div>";
            }
            ?>
        </div>
        <!-- Conditions Checkbox End -->
      </div>

    </form>
  <!-- end search specs bar -->

<?php
// Retrieve these from the URL
if (!isset($_GET['conditions'])) {
    $conditions_query = "";
} else {
    $conditions = $_GET['conditions'];
    $conditionsString = implode(", ", str_split($conditions));
    $conditions_query = " AND i.conditions IN ($conditionsString)";
}

// Retrieve these from the URL
if (!isset($_GET['search_keyword']) or empty($_GET['search_keyword'])) {
    $keyword = "%";
} else {
    $escapedString = mysqli_real_escape_string($connection, $_GET['search_keyword']);
    $keyword = "%$escapedString%";
}

// Only do filtering if category is selected and the category is not "all"
// Otherwise do nothing
if (isset($_GET['category']) and $_GET['category'] != 0) {
    $category = $_GET['category'];
    $category_query = " AND i.category = '$category'";
} else {
    $category_query = "";
}

// Retrieve the ordering method
if (!isset($_GET['sort_by'])) {
    $ordering = "COUNT(b.itemId) DESC";
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

// Retrieve data (userid from the session)
$currentUserId = $_SESSION['id'];

// SQL to fetch data
$query = "SELECT i.itemId, i.itemTitle, i.category, i.description, MAX(b.bidPrice), COUNT(b.itemId),
            i.startingPrice, i.endDateTime 
            FROM wishlist w
            INNER JOIN items i ON w.itemId = i.itemId
            LEFT JOIN bidHistory b ON i.itemId = b.itemId
            WHERE w.userId = '$currentUserId'
            AND (i.itemTitle LIKE '$keyword' or i.category LIKE '$keyword' or i.description LIKE '$keyword' or i.brand LIKE '$keyword')
            AND i.endDateTime > NOW()
            $category_query
            $conditions_query
            GROUP BY i.itemId, i.itemTitle, i.category, i.description, i.startingPrice, i.endDateTime
            ORDER BY  $ordering, i.itemTitle
            LIMIT $items_per_page OFFSET $offset;";

$count_item_query = "SELECT COUNT(*) 
            FROM wishlist w
            INNER JOIN items i ON w.itemId = i.itemId
            WHERE w.userId = '$currentUserId'
            AND (i.itemTitle LIKE '$keyword' or i.category LIKE '$keyword' or i.description LIKE '$keyword' or i.brand LIKE '$keyword')
            AND i.endDateTime > NOW()
            $category_query
            $conditions_query;";

$result = mysqli_query($connection, $query);

// Calculate the number of rows that meet the query criteria
$count_item_result = mysqli_query($connection, $count_item_query);
if ($count_item_result) {
    $num_item = mysqli_fetch_array($count_item_result)[0];
} else {
    $num_item = 0;
}

$max_page = ceil($num_item / $items_per_page);

if (isset($_GET['search_keyword']) and isset($_GET['category'])) {
    echo "<div class='text-muted'> Found $num_item results. Showing page $curr_page of $max_page.</div>";
}

if ($result) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $itemId = $row["itemId"];
        $itemTitle = $row["itemTitle"];
        $category = $row["category"];
        $description = $row["description"];
        $currentPrice = ($row["MAX(b.bidPrice)"] !== null) ? $row["MAX(b.bidPrice)"]: $row["startingPrice"];
        $numBids = $row["COUNT(b.itemId)"];
        $endDateTime = new DateTime($row["endDateTime"]);
        // This uses a function defined in utilities.php
        print_listing_li($itemId, $itemTitle, $category, $description, $currentPrice, $numBids, $endDateTime);
    }
} else {
    echo "No results found.";
}
mysqli_close($connection);
?>

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
        <a class="page-link" href="wishlist.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
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
              echo('<a class="page-link" href="wishlist.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>');
          }

          if ($curr_page < $max_page) {
              echo('<li class="page-item">
        <a class="page-link" href="wishlist.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
        </a>
        </li>');
          }
          ?>
      </ul>
    </div>

    <div class="col-auto ml-auto">
      <form class="form-inline" method="GET" action="wishlist.php">
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

<script>
    function handleSubmit(event) {
        // Prevent the default form submission if this function is called in response to the form's submit event
        if (event) event.preventDefault();

        // Get the value from the text input
        var searchKeyword = document.getElementById('search_keyword').value;
        // Get the value from the first dropdown
        var category = document.getElementById('category').value;

        var checkedValues = [];
        var checkboxes = document.querySelectorAll('input[name="conditions"]:checked');
        checkboxes.forEach(function(checkbox) {
            checkedValues.push(checkbox.value);
        });
        var checkedValuesString = checkedValues.join("");

        var sortBy = document.getElementById('sort_by').value;
        // Get the value from the second dropdown
        var itemsPerPage = document.getElementById('items_per_page').value;

        // Redirect the browser to browse.php with the query parameters
        window.location.href = 'wishlist.php?search_keyword=' + encodeURIComponent(searchKeyword) +
            '&category=' + encodeURIComponent(category) +
            '&conditions=' + encodeURIComponent(checkedValuesString) +
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
</script>

<?php include_once("footer.php") ?>