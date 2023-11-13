<?php

require_once("config_database.php");
require_once("utilities.php");
require_once("header.php");

$user_id = $_SESSION['id'];
$bidDateTime = new DateTime();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid_amount_str = filter_input(INPUT_POST, 'bid', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $item_id = $_POST['item_id'];
    $current_price_str = $_POST['current_price'];
    $current_price = floatval($current_price_str);

}
$bid_amount = floatval($bid_amount_str);

if (!is_numeric($bid_amount) || $bid_amount <= 0){
    die("Invalid bid amount: ");

}
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

$bidDateTime = $bidDateTime->format('Y-m-d H:i:s');
if ($bid_amount > $current_price) {
    $query = $connection->prepare("INSERT INTO bidhistory (itemId, userId, bidPrice, bidDateTime) VALUES (?,?, ?, ?)");
    $query->bind_param('iids', $item_id, $user_id, $bid_amount, $bidDateTime);
    if ($query->execute()) {
        echo "Bid placed successfully!";
        header("refresh:3;url=listing.php?item_id=$item_id");
        $item_query = "UPDATE items SET numBids = numBids + 1 WHERE itemId = ?";
        $stmt = mysqli_prepare($connection, $item_query);
        mysqli_stmt_bind_param($stmt, 'i', $item_id);

    } else {
        echo "Error placing bid: " . $query->error;
    }
}
else{
    echo("Bid lower than current price.");
    header("refresh:2;url=listing.php?item_id=$item_id");
}

$connection->close();
// Notify user of success/failure and redirect/give navigation options.
require_once("footer.php");
?>

