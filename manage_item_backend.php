<?php

include_once("header.php");
require_once "config_database.php";
require_once("utilities.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['actionType'];
    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

    if (isset($actionType) && $actionType == "editItem") {
        $item_id = $_POST['itemId'];
        $title = $_POST['itemTitle'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $condition = $_POST['condDescript'];
        $brand = $_POST['brand'];

        $query = "UPDATE items 
                    SET itemTitle = '$title', 
                    category = $category, 
                    conditions = $condition, 
                    description = '$description', 
                    brand = '$brand'
                    WHERE itemId = $item_id;";

        $result = mysqli_query($connection, $query);
        mysqli_close($connection);
        if ($result) {
            echo "<div style='text-align: center;'><br><h5>Auction detail for '$title' is updated</div>";
            header("refresh:3;listing.php?item_id=$item_id");
        } else {
            echo "<div style='text-align: center;'><br><h5>Failed to update auction detail for '$title'";
            echo "<br><br><a href='listing.php?item_id=$item_id' class='btn btn-outline-info'>Return</a></div>";
        }
    }
}
