<?php


declare(strict_types=1);

#Queries database to retrieve the provided username so to ensure it is not already taken.
/*function get_username(mysqli $conn, string $username)
{
    $query = "SELECT username FROM auctionDatabase.Users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result;
}*/


#Inserts the new item into the Items table within the AuctionDatabase, though it does not create a date as of yet.
function set_auction(mysqli $conn, int $userid, string $auctionTitle, string $auctionDetails, string $auctionCategory,
                     float $auctionStartingPrice, float $auctionReservePrice, string $auctionEndDate){
    $query = "INSERT INTO auctionDatabase.Items(name, category, description, seller_id, num_bids, current_winner,
                                  starting_price, current_price, end_datetime) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    /*$options = [
        'cost' => 12
    ];*/
    $numBids = 0;
    $currentWinner = null;
    $stmt->bind_param("sssiiidds", $auctionTitle, $auctionCategory, $auctionDetails, $userid, $numBids,
       $currentWinner, $auctionStartingPrice, $auctionReservePrice, $auctionEndDate);
    $stmt->execute();
}