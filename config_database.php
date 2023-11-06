<?php

function connect_to_db(){
// Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "auctionDataBase";

    // Create database connection
    // For MAMP, default database password is "root"
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // If connection fails, the reason might be the password is wrong
    // Since WAMP uses "" as default password, try to establish connection again
    // with the password ""
    if (!$conn) {
        $password = '';
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // if connection fails again, alert the user to check if the server is running
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    return $conn;
}
?>