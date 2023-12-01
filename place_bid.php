<?php

global $now, $end_time, $reserve_price;
require_once("config_database.php");
require_once("utilities.php");
require_once("header.php");

// set dummy value for variables in case user accessed this page unexpectedly
$user_id = $_SESSION['id'];
$bidDateTime = new DateTime();

$bid_amount = 0;
$highest_price = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bid_amount_str = filter_input(INPUT_POST, 'bid', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $item_id = $_POST['item_id'];
    $highest_price_str = $_POST['highest_price'];
    $highest_price = floatval($highest_price_str);
    $bid_amount = floatval($bid_amount_str);
}

if (!is_numeric($bid_amount) || $bid_amount <= 0){
    echo("<h2>Invalid bid amount</h2><br>
    <p>You will be redirected to the item page shortly.</p>");
    header("refresh:2;url=listing.php?item_id=$item_id");
} else {
    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

    $bidDateTime = $bidDateTime->format('Y-m-d H:i:s');
    if ($bid_amount > $highest_price) {
        $query = $connection->prepare("INSERT INTO bidHistory (itemId, userId, bidPrice, bidDateTime) VALUES (?,?,?,?)");
        $query->bind_param('iids', $item_id, $user_id, $bid_amount, $bidDateTime);
        if ($query->execute()) {
            echo "<h2>Bid placed successfully!</h2><br>
        <p>You will be redirected to the item page shortly.</p>";
            //header("refresh:3;url=listing.php?item_id=$item_id");
        } else {
            echo "Error placing bid: " . $query->error;
        }
    }
    else{
        echo("<h2>Bid lower than current price.</h2><br>
        <p>You will be redirected to the item page shortly.</p>");
        header("refresh:3;url=listing.php?item_id=$item_id");
    }

    $connection->close();
}

// Notify user of success/failure and redirect/give navigation options.
require_once("footer.php");
?>

