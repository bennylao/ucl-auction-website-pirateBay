<?php
$title = "My Listings";
include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php require_once("config_database.php") ?>

<?php
// Create database connection
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>

  <div class="container">

  <h2 class="my-3">My listings</h2>

      <!-- Searching Input -->
      <div class="col-md-5 pr-0">
          <div class="form-group">
              <div class="input-group">
                  <div class="input-group-prepend">
                      <label class="input-group-text" for="search_keyword"><i class="fa fa-search"></i></label>
                  </div>
                  <input type="text" id="search_keyword" name="search_keyword" class="form-control border-left-0"
                         title="
Use '&' to search for items that match all the keywords.
e.g. 'Apple&Mac'
Use '|' to search for items that match any of the keywords.
e.g. 'Apple|Samsung'"
                         placeholder="Search for anything" <?php if (isset($_GET['search_keyword'])) echo "value=" . $_GET['search_keyword']; ?>>
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
    </div>
    <!-- end search specs bar -->

<?php
// Retrieve data (userid from the session)
$currentUserId = $_SESSION['id'];

// Create database connection
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());


// SQL to fetch data
$query = "SELECT i.itemId, i.itemTitle, i.category, i.description, i.startingPrice,
       i.endDateTime, MAX(b.bidPrice), COUNT(b.itemId), i.reservedPrice
    FROM items i
         LEFT JOIN bidHistory b ON i.itemId = b.itemId
    WHERE i.sellerId = '$currentUserId'
        GROUP BY i.itemId, i.itemTitle, i.category, i.description, i.startingPrice, i.endDateTime
";

$result = mysqli_query($connection, $query);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $itemId = $row["itemId"];
        $itemTitle = $row["itemTitle"];
        $category = $row["category"];
        $description = $row["description"];
        $currentPrice = ($row["MAX(b.bidPrice)"] !== null) ? $row["MAX(b.bidPrice)"]: 0;
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

<?php include_once("footer.php") ?>