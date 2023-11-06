<?php

# The file responsible for containing the auction creation functions that do not involve changing or interacting with the database.
declare(strict_types=1);


#Will return false if any of the required input fields are not filled in.
function is_create_auction_input_empty(string $auctionTitle, string $auctionBrand, string $auctionCategory, float $auctionStartPrice, float $auctionReservePrice, string $auctionEndDate, string $condition)
{
    if (empty($auctionTitle) || empty($auctionBrand) || empty($auctionCategory) || empty($auctionStartPrice) || empty($auctionReservePrice) || empty($auctionEndDate) || empty($condition)) {
        return true;
    } else {
        return false;
    }
}

#Creates an auction by taking in the database connection and the input variables and querying the database via the set_auction function in the signup_model.inc.php file.
function create_auction(object $conn, int $userid, string $auctionTitle, string $auctionBrand, string $auctionDetails, string $auctionCategory, string $condition,
                        float $auctionStartPrice, float $auctionReservePrice, string $auctionEndDate)
{
    set_auction($conn, $userid, $auctionTitle, $auctionBrand, $auctionDetails, $auctionCategory, $auctionStartPrice,
        $auctionReservePrice, $auctionEndDate, $condition);
}
