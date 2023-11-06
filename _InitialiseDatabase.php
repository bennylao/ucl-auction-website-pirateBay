<?php
// This is the script that initialise the database
// For internally development and use only

require_once("config_database.php");

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "auctionDataBase";

// Create connection
$conn = mysqli_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    $password = '';
    $conn = mysqli_connect($servername, $username, $password);
    // if connection fails again, alert the user to check if the server is running
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
// Drop the database if it exists
$sql = "DROP DATABASE IF EXISTS auctionDataBase";
mysqli_query($conn, $sql);

// Create database
$sql = "CREATE DATABASE auctionDataBase";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully. ";
} else {
    echo "Error creating database: ". mysqli_error($conn);
}
mysqli_close($conn);

$conn = connect_to_database();

// Create the admin table
$sql = "CREATE TABLE admin (
adminId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
password VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Admin Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create admin records
$sql = "INSERT INTO admin (adminId, password)
        VALUES (1, '1111'),
               (2, '1111')";

if (mysqli_query($conn, $sql)) {
    echo "Admin records created successfully. \n";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the first table users
$sql = "CREATE TABLE users (
userId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
accountType VARCHAR(255) NOT NULL,
userName VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
firstName VARCHAR(255) NOT NULL,
lastName VARCHAR(255) NOT NULL,
address VARCHAR(255),
createDate date)";

if (mysqli_query($conn, $sql)) {
    echo "Table User created successfully. ";
} else {
    echo "Error creating table User: ". mysqli_error($conn);
}

// Create records for users
$sql = "INSERT INTO users (accountType, userName, password, email, firstName, lastName, address, createDate)
VALUES ('buyer', 'admin', '$2y$10$4e6ITty1JFx53RnOPaVXH.orr9GSNL6nsg1h2z0CAHxzclN9rFhtG','email','fn','ln','addr','2017-06-05'), 
       ('seller', 'user1', '$2y$10\$Cej7YR5.IYEpd93WwBWQyO/tgFqn.QDC6La5oiwq.LAkX9R78RHMe','email1','fn1','ln1','addr1','2017-06-05'),
       ('buyerseller', 'user2', '$2y$10\$xkBU7pYHLKP6ETnXp9R/eOsrHsEmORfTYvq5bqtzkx1RpO4ghDe5y','email2','fn2','ln2','addr2','2017-06-05'),
       ('seller', 'victor', '$2y$10\$x/oH2Gy1hdAHcoOoO4YNtOLJrCPW8PE25Mmi1tuTiFJ2MKxBdaYYq','victor2263@gmail.com','victor','chan','123 Oxford Street','2023-10-31')";

if (mysqli_query($conn, $sql)) {
    echo "New User records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the second table items
$sql = "CREATE TABLE items (
itemId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
category VARCHAR(255) NOT NULL,
description VARCHAR(255) NOT NULL,
sellerId INT NOT NULL,
numBids INT NOT NULL,
currentWinner INT,
startingPrice DECIMAL NOT NULL,
currentPrice DECIMAL NOT NULL,
endDateTime TIMESTAMP NOT NULL,
brand VARCHAR(255) NOT NULL,
FOREIGN KEY (sellerId) REFERENCES users(userId)
)";

if (mysqli_query($conn, $sql)) {
    echo "Table Item created successfully. ";
} else {
    echo "Error creating table Item: ". mysqli_error($conn);
}

// Create records for items
$sql = "INSERT INTO items (itemId, title, category, description, sellerId, numBids, currentWinner, startingPrice, currentPrice,
                   endDateTime, brand)
        VALUES (1, 'sdf', 'Antiques', 'new', 2, 3, 422, 200, 210, '2023-10-11', 'aaa'),
               (2, 'cbd', 'Wine and Spirits', 'new', 3, 4, 422, 20000, 40000, '2023-10-31 23:00:00', 'sold'),
               (3, 'MacBook Pro M3 with M3 Max', 'Electronics and Technology', 'new', 4, 3, 2, 20000, 35975, '2023-12-31 23:00:00', 'apple')";

if (mysqli_query($conn, $sql)) {
    echo "New Item records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the category table
$sql = "CREATE TABLE category (
cateId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
category VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Category Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create category options for users
$sql = "INSERT INTO category (cateId, category)
        VALUES (1, 'Art and Collectibles'),
               (2, 'Antiques'),
               (3, 'Automobiles and Vehicles'),
               (4, 'Jewelry and Watches'),
               (5, 'Electronics and Technology'),
               (6, 'Fashion and Apparel'),
               (7, 'Sports and Memorabilia'),
               (8, 'Wine and Spirits'),
               (9, 'Furniture and Home Decor'),
               (10, 'Real Estate')";

if (mysqli_query($conn, $sql)) {
    echo "Category options created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the bid history table
$sql = "CREATE TABLE bidHistory (
bidId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
itemId VARCHAR(255) NOT NULL,
userId INT NOT NULL,
bidPrice INT NOT NULL ,
bidDateTime TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Bid history table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create records for bidHistory
$sql = "INSERT INTO bidHistory(bidId, itemId, userId, bidPrice, bidDateTime)
VALUES (1, 121, 5203, 100, '2020-10-10'),
       (2, 524, 1231, 420, '2022-12-12')";

if (mysqli_query($conn, $sql)) {
    echo "New bid history records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the wishList table
$sql = "CREATE TABLE wishList (
    listId INT NOT NULL PRIMARY KEY ,
    itemId INT NOT NULL,
    userId INT NOT NULL,
    FOREIGN KEY (itemId) REFERENCES items(itemId),
    FOREIGN KEY (userId) REFERENCES users(userId)
)";

if (mysqli_query($conn, $sql)){
    echo "Table wishlist created successfully. ";
}else{
    echo "Error: ".$sql."<br>".mysqli_error($conn);
}

// Create the records for the wishList table
$sql = "INSERT INTO wishList (listId, itemId, userId)
VALUES (1, 1, 1),
       (2, 1, 2),
       (3, 1, 3)";

if (mysqli_query($conn, $sql)){
    echo "New wishlist records created successfully. ";
}else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the table for
$sql = "CREATE TABLE conditions (
    conditionId INT NOT NULL ,
    condDescript VARCHAR(255) NOT NULL 
)";

if (mysqli_query($conn, $sql)){
    echo "Table conditions created successfully. ";
} else {
    echo "Error: ".$sql."<br>".mysqli_error($conn);
}

// Create records for the conditions table
$sql = "INSERT INTO conditions (conditionId, condDescript)
VALUES (1, 'Brand new'),
       (2, 'Like new'),
       (3, 'Very good'),
       (4, 'Good'),
       (5, 'Acceptable')";

mysqli_close($conn);
?>
