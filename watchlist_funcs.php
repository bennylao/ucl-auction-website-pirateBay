<?php
require("header_clean.php");
require_once("utilities.php");
require_once("config_database.php");


if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
    echo "No function name or arguments provided";
    return;
}

$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

// Extract arguments from the POST variables:
$currentUserId = $_SESSION['id'];
$item_id = $_POST['arguments'];

if ($_POST['functionname'] == "add_to_watchlist") {
    // TODO: Update database and return success/failure.
    $query = "INSERT INTO wishList(itemId, userId) VALUES (?, ?)";
    $query = $connection->prepare($query);
    $query->bind_param('ii', $item_id, $currentUserId);
    if ($query->execute()) {
        $res = "success";
}
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
    // TODO: Update database and return success/failure.
    $query = "DELETE FROM wishList WHERE itemId = ? AND userId = ?";
    $query = $connection->prepare($query);
    $query->bind_param('ii', $item_id, $currentUserId);
    if ($query->execute()) {
        $res = "success";
    }
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
    echo $res;
mysqli_close($connection);

?>
