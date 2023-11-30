<?php
require_once("config_database.php");
$conn = connect_to_database();

$itemId = $_POST['itemId'];
$userId = $_POST['userId'];
$type = $_POST['type']; // Retrieve the type from POST data


if ($type == 'seller') {
    // Update the items table for seller notifications
    $query = "UPDATE items SET isRead = 1 WHERE itemId = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $itemId);
} else if ($type == 'bidder') {
    // Update the bidHistory table for bidder notifications
    $query = "UPDATE bidHistory SET isRead = 1 
                WHERE itemId = ?
                AND userId = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $itemId, $userId); // Assuming bidId is same as itemId
}

mysqli_stmt_execute($stmt);
mysqli_close($conn);

echo "Update successful";
?>
