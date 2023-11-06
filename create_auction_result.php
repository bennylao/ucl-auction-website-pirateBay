<?php
require_once "config_database.php";
//include_once "login_result.php";
// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation
// options.
//session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve the values from the form
    $auctionTitle = $_POST['auctionTitle'];
    $auctionDetails = $_POST['auctionDetails'];
    $auctionCategory = $_POST['auctionCategory'];
    $auctionStartPrice = $_POST['auctionStartPrice'];
    $auctionReservePrice = $_POST['auctionReservePrice'];
    $auctionEndDate = $_POST['auctionEndDate'];

    try {
        $conn = connect_to_database();
        require_once getcwd()."/includes/create_auction_model.inc.php";
        require_once getcwd()."/includes/create_auction_contr.inc.php";

        // ERROR HANDLERS
        $errors = [];

        if (is_create_auction_input_empty($auctionTitle, $auctionCategory, $auctionStartPrice, $auctionReservePrice, $auctionEndDate)) {
            $errors["empty_input"] = "Fill in all fields";
        }

        require_once 'header.php';

        if ($errors) {
            $_SESSION["errors_create_auction"] = $errors;
            header("Location: ../create_auction.php");
            die();
        }

        $userid = $_SESSION['id'];

        create_auction($conn, $userid, $auctionTitle, $auctionDetails, $auctionCategory, $auctionStartPrice,
            $auctionReservePrice, $auctionEndDate);
        header("Location: ../index.php?signup=success");
        mysqli_close($conn);


    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../register.php");
    die();
}
?>
