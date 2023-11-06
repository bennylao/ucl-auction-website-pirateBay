<?php
$title = "My Listings";
include_once ("header.php")?>
<?php require ("utilities.php")?>
<?php require_once ("config_database.php")?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
// Retrieve data (userid from the session)
$currentUserid = $_SESSION['id'];

// Create database connection
$connection = connect_to_database()
or die('Error connecting to MySQL server.' . mysqli_error());;


// SQL to fetch data
$query = "SELECT i.itemID, i.name, i.category, i.description, i.current_price,
       i.num_bids, i.end_datetime 
    FROM Items i
    WHERE i.seller_id = '$currentUserid'";
$result = mysqli_query($connection,$query);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $item_id = $row["itemID"];
        $title = $row["name"];
        $category = $row["category"];
        $description = $row["description"];
        $current_price = $row["current_price"];
        $num_bids = $row["num_bids"];
        $end_datetime = new DateTime($row["end_datetime"]);
        // This uses a function defined in utilities.php
        print_listing_li($item_id, $title, $category, $description, $current_price, $num_bids, $end_datetime);
    }
} else {
    echo "No results found.";
}
mysqli_close($connection);
?>

<?php include_once("footer.php")?>