<?php
/*require_once "config_database.php";
// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation
// options.


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $auctionTitle = isset($_POST["auctionTitle"]) ? $_POST["auctionTitle"] : null;
    $auctionDetails = isset($_POST["auctionDetails"]) ? $_POST["auctionDetails"] : null;
    $auctionCategory = isset($_POST["auctionCategory"]) ? $_POST["auctionCategory"] : null;
    $condition = isset($_POST["condition"]) ? $_POST["condition"] : null;
    $auctionStartPrice = isset($_POST["auctionStartPrice"]) ? $_POST["auctionStartPrice"] : null;
    $auctionReservePrice = isset($_POST["auctionReservePrice"]) ? $_POST["auctionReservePrice"] : null;
    $auctionEndDate = isset($_POST["auctionEndDate"]) ? $_POST["auctionEndDate"] : null;


    try {
        $conn = connect_to_database();
        require_once getcwd() . "/includes/signup_model.inc.php";
        require_once getcwd() . "/includes/signup_contr.inc.php";

        // ERROR HANDLERS
        $errors = [];

        if (is_create_auction_input_empty($auctionTitle, $auctionCategory, $condition, $auctionStartPrice, $auctionReservePrice, $auctionEndDate)) {
            $errors["empty_input"] = "Fill in all fields";
        }

        require_once 'header.php';

        if ($errors) {
            $_SESSION["errors_signup"] = $errors;
            header("Location: ../create_auction.php");
            die();
        }
        create_auction($conn, $auctionTitle, $userid, $auctionDetails, $auctionCategory, $condition, $auctionStartPrice, $auctionReservePrice, $auctionEndDate);
        #    header("Location: ../index.php?signup=success"); - will need to alter this to a 'create_auction_success option
        mysqli_close($conn);


    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../create_auction.php");
    die();
}
?>