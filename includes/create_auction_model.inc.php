<?php


declare(strict_types=1);

// Inserts the new item into the Items table within the AuctionDatabase, though it does not create a date as of yet.
function set_auction(mysqli $conn, $userid, $auctionTitle, $auctionBrand, $auctionDetails, $auctionCategory, $auctionStartingPrice, $auctionReservePrice, $auctionEndDate, $condition)
{
    $query = "INSERT INTO Items(itemTitle, category, conditions, description, sellerId, ownerId, 
                  startingPrice, reservedPrice, startDateTime, endDateTime, brand) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $auctionTitle = mysqli_real_escape_string($conn, $auctionTitle);
    $auctionDetails = mysqli_real_escape_string($conn, $auctionDetails);

    $auctionStartDate = date("Y-m-d H:i:s");
    $stmt->bind_param("sissiiiisss", $auctionTitle, $auctionCategory, $condition, $auctionDetails, $userid, $userid, $auctionStartingPrice, $auctionReservePrice, $auctionStartDate, $auctionEndDate, $auctionBrand);
    $stmt->execute();
}