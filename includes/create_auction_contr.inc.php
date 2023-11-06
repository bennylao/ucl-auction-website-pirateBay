<?php


# The file responsible for containing the auction creation functions that do not involve changing or interacting with the database.
declare(strict_types=1);

#Will return false if any of the required input fields are not filled in.
function is_create_auction_input_empty(string $auctionTitle, string $auctionCategory, float $auctionStartPrice, float $auctionReservePrice, string $auctionEndDate)
{
    if (empty($auctionTitle) || empty($auctionCategory) || empty($auctionStartPrice) || empty($auctionReservePrice) || empty($auctionEndDate)) {
        return true;
    } else {
        return false;
    }
}

#Creates an auction by taking in the database connection and the input variables and querying the database via the set_auction function in the signup_model.inc.php file.
function create_auction(object $conn, string $auctionTitle, string $auctionDetails, string $auctionCategory, float $auctionStartPrice, float $auctionReservePrice, string $auctionEndDate)
{
    set_auction($conn, $auctionTitle, $auctionDetails, $auctionCategory, $auctionStartPrice, $auctionReservePrice, $auctionEndDate);
}
