<?php
// This is the script that initialise the database
// For internal development and use only

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
               ('seller', 'victor', '$2y$10\$x/oH2Gy1hdAHcoOoO4YNtOLJrCPW8PE25Mmi1tuTiFJ2MKxBdaYYq','victor2263@gmail.com','victor','chan','123 Oxford Street','2023-10-31'),
               ('buyer', 'buyer2', 'dd','buyer2','John','Chan','321 Euston Road','2023-11-05')";

if (mysqli_query($conn, $sql)) {
    echo "New User records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the second table items
$sql = "CREATE TABLE items (
itemId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
itemTitle VARCHAR(255) NOT NULL,
category VARCHAR(255) NOT NULL,
conditions VARCHAR(255) NOT NULL,
description VARCHAR(255) NOT NULL,
sellerId INT NOT NULL,
numBids INT NOT NULL,
currentWinner INT,
startingPrice DECIMAL NOT NULL,
currentPrice DECIMAL NOT NULL,
startDateTime DATETIME NOT NULL,
endDateTime DATETIME NOT NULL,
brand VARCHAR(255),
reservedPrice DECIMAL,
FOREIGN KEY (sellerId) REFERENCES users(userId)
)";

if (mysqli_query($conn, $sql)) {
    echo "Table Item created successfully. ";
} else {
    echo "Error creating table Item: ". mysqli_error($conn);
}

// Create records for items
$sql = "INSERT INTO items (itemId, itemTitle, category, conditions, description, sellerId, numBids, currentWinner,
                   startingPrice, currentPrice, startDateTime, endDateTime, brand)
        VALUES  (1, 'Expired Item 1', 'Others', 'new', 'golden apple', 2, 3, 1, 2, 5, '2023-05-20 00:00:00','2023-10-30 00:00:00', 'expiry'),
                (2, 'Expired Item 2', 'Others', 'new', 'golden apple', 2, 3, 1, 2, 5, '2023-07-30 00:00:00','2023-11-02 00:00:00', 'expiry'),
                (3, 'An apple11', 'Others', 'new', 'golden apple', 2, 3, 1, 2, 5, '2023-09-30 00:00:00', '2024-02-11 11:00:00', 'apple banana'),
                (4, 'Sony A7m3', 'Electronics and Technology', 'new', 'new A7m3', 3, 8, 1, 500, 890, '2023-10-01 05:00:00', '2024-03-31 23:00:00', 'Sony'),
                (5, 'MacBook Pro M3 with M3 Max', 'Electronics and Technology', 'new', 'new', 4, 20, 2, 20000, 7700, '2023-10-01 18:30:00', '2024-04-22 23:00:00', 'Apple'),
                (6, 'iPhone 17 Pro', 'Electronics and Technology', 'new', 'new', 4, 4, 2, 1000, 1200, '2023-10-03 15:30:00', '2024-01-20 01:00:00', 'Apple'),
                (7, 'Samsung Galaxy S87 Ultra', 'Electronics and Technology', 'new', 'new', 4, 3, 2, 1000, 1050, '2023-10-05 20:30:00', '2024-01-03 17:30:00', 'Samsung'),
                (8, 'My Brain', 'Others', 'new', 'new', 4, 11, 2, 1000, 1100, '2023-10-05 22:30:00', '2024-03-01 00:00:00', 'Me'),
                (9, 'iMac', 'Electronics and Technology', 'new', 'new', 2, 10, 2, 5000, 7800, '2023-10-06 00:30:00', '2024-02-01 00:00:00', 'Apple'),
                (10, 'Apple Vision Pro', 'Electronics and Technology', 'new', 'new', 2, 3, 2, 800, 1050, '2023-10-06 12:30:00', '2024-01-03 00:00:00', 'Apple'),
                (11, 'UCL Premium Study Space', 'Others', 'new', 'new', 2, 1000, 1, 10, 200, '2023-10-07 12:30:00', '2024-02-04 00:00:00', 'UCL'),
                (12, 'iPad Pro 12.9', 'Electronics and Technology', 'new', 'new', 2, 15, 2, 400, 780, '2023-10-07 21:30:00', '2024-01-30 00:00:00', 'Apple'),
                (13, 'AirPods', 'Electronics and Technology', 'new', 'new', 2, 4, 2, 50, 80, '2023-10-08 00:30:00', '2024-02-10 00:00:00', 'Apple')";

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
               (10, 'Real Estate'),
               (11, 'Others')";

if (mysqli_query($conn, $sql)) {
    echo "Category options created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the bid history table
$sql = "CREATE TABLE bidHistory (
bidId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
itemId INT NOT NULL,
userId INT NOT NULL,
bidPrice INT NOT NULL ,
bidDateTime DATETIME NOT NULL,
FOREIGN KEY (itemId) REFERENCES items(itemId),
FOREIGN KEY (userId) REFERENCES users(userId)
)";

if (mysqli_query($conn, $sql)) {
    echo "Bid history table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create records for bidHistory
$sql = "INSERT INTO bidHistory(bidId, itemId, userId, bidPrice, bidDateTime)
VALUES (1, 1, 1, 100, '2020-10-10'),
       (2, 1, 3, 420, '2022-12-12')";

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
VALUES (1, 8, 1),
       (2, 8, 2),
       (3, 8, 3)";

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
