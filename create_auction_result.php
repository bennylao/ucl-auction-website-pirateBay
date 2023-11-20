<?php
require_once "config_database.php";
//include_once "login_result.php";
// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation
// options.


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve the values from the form
    $auctionTitle = $_POST['auctionTitle'];
    $auctionBrand = $_POST['auctionBrand'];
    $auctionDetails = $_POST['auctionDetails'];
    $auctionCategory = $_POST['auctionCategory'];
    $conditions = $_POST['conditions'];
    $auctionStartPrice = $_POST['auctionStartPrice'];
    $auctionReservePrice = $_POST['auctionReservePrice'];
    $auctionEndDate = $_POST['auctionEndDate'];

    if (empty($auctionReservePrice)) {
        $auctionReservePrice = NULL;
    }

    try {
        $conn = connect_to_database();
        require_once "includes/create_auction_model.inc.php";
        require_once "includes/create_auction_contr.inc.php";
        //require_once getcwd()."/login_result.php";

        // ERROR HANDLERS
        $errors = [];

        if (is_create_auction_input_empty($auctionTitle, $auctionBrand, $auctionCategory, $auctionStartPrice, $auctionEndDate, $conditions)) {
            $errors["empty_input"] = "Fill in all required fields";
        }

        require_once 'header.php';

        if ($errors) {
            $_SESSION["errors_create_auction"] = $errors;
            echo 'Please fill in all the required information';
            header("refresh:5;url=create_auction.php");
            die();
        }

        $userid = $_SESSION['id'];

        create_auction($conn, $userid, $auctionTitle, $auctionBrand, $auctionDetails, $auctionCategory, $conditions, $auctionStartPrice, $auctionReservePrice, $auctionEndDate);

        $result = mysqli_query($conn, "SELECT itemId FROM items ORDER BY startDateTime DESC LIMIT 1");
        mysqli_close($conn);
        $row = $result->fetch_assoc();
        $itemId = $row['itemId'];
        echo 'Created auction successfully!';
//        header("refresh:3;url=index.php?signup=success");
        header("refresh:3;url=listing.php?item_id=$itemId");



    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../register.php");
    die();
}
?>

