<?php
require_once("listing.php");
require_once("config_database.php");
require_once("utilities.php");


$user_id = $_SESSION['user_id'];
$item_id = $_GET['item_id'];
$bidDateTime = new DateTime();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid_amount = filter_input(INPUT_POST, 'bid', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}
if (!is_numeric($bid_amount) || $bid_amount <= 0){
    die("Invalid bid amount: ");

}
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

$query = $connection->prepare("INSERT INTO bidhistory (itemId, userId, bidPrice, bidDateTime) VALUES (?, ?, ?)");
$query->bind_param('iids', $item_id, $user_id, $bid_amount, $bidDateTime);

if ($query->execute()) {
    echo "Bid placed successfully!";
} else {
    echo "Error placing bid: " . $query->error;
}

$query->close();
$connection->close();
// Notify user of success/failure and redirect/give navigation options.

?>