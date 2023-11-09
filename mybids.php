<?php
$title = "My Listings";
include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php require_once("config_database.php") ?>

  <div class="container">

  <h2 class="my-3">My Bids</h2>

<?php
// Retrieve data (userid from the session)
$currentUserId = $_SESSION['id'];

// Create database connection
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());


// SQL to fetch data
$query = "SELECT i.itemId, i.itemTitle, i.category, i.description, i.currentPrice,
       i.numBids, i.endDateTime
    FROM items i, bidHistory b
    WHERE i.itemId = b.itemId AND
          b.userId = $currentUserId";
$result = mysqli_query($connection, $query);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
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

<?php include_once("footer.php") ?>