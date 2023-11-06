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
adminID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
Password VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Admin Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create admin records
$sql = "INSERT INTO admin (adminID, Password)
        VALUES (1, '1111'),
               (2, '1111')";

if (mysqli_query($conn, $sql)) {
    echo "Admin records created successfully. \n";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the first table Users
$sql = "CREATE TABLE Users (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
accountType VARCHAR(255) NOT NULL,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
first_name VARCHAR(255) NOT NULL,
last_name VARCHAR(255) NOT NULL,
address VARCHAR(255),
create_date date)";

if (mysqli_query($conn, $sql)) {
    echo "Table User created successfully. ";
} else {
    echo "Error creating table User: ". mysqli_error($conn);
}

// Create records for users
$sql = "INSERT INTO Users (accountType, username, password, email, first_name, last_name, address, create_date)
VALUES ('buyer', 'admin', '$2y$10$4e6ITty1JFx53RnOPaVXH.orr9GSNL6nsg1h2z0CAHxzclN9rFhtG','email','fn','ln','addr','2017-06-05'), 
       ('seller', 'user1', '$2y$10\$Cej7YR5.IYEpd93WwBWQyO/tgFqn.QDC6La5oiwq.LAkX9R78RHMe','email1','fn1','ln1','addr1','2017-06-05'),
       ('buyerseller', 'user2', '$2y$10\$xkBU7pYHLKP6ETnXp9R/eOsrHsEmORfTYvq5bqtzkx1RpO4ghDe5y','email2','fn2','ln2','addr2','2017-06-05'),
       ('seller', 'victor', '$2y$10\$x/oH2Gy1hdAHcoOoO4YNtOLJrCPW8PE25Mmi1tuTiFJ2MKxBdaYYq','victor2263@gmail.com','victor','chan','123 Oxford Street','2023-10-31')";

if (mysqli_query($conn, $sql)) {
    echo "New User records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the second table Items
$sql = "CREATE TABLE Items (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
category VARCHAR(255) NOT NULL,
description VARCHAR(255) NOT NULL,
seller_id INT NOT NULL,
num_bids INT NOT NULL,
current_winner INT,
starting_price DECIMAL NOT NULL,
current_price DECIMAL NOT NULL,
end_datetime TIMESTAMP NOT NULL,
brand VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Table Item created successfully. ";
} else {
    echo "Error creating table Item: ". mysqli_error($conn);
}

// Create records for users
$sql = "INSERT INTO Items (id, name, category, description, seller_id, num_bids, current_winner, starting_price, current_price,
                   end_datetime, brand)
        VALUES (1, 'sdf', 'Antiques', 'new', 1312, 3, 422, 200, 210, '2023-10-11', 'aaa'),
               (2, 'cbd', 'Wine and Spirits', 'new', 1113, 4, 422, 20000, 40000, '2023-10-31 23:00:00', 'sold'),
               (3, 'MacBook Pro M3 with M3 Max', 'Electronics and Technology', 'new', 1, 3, 2, 20000, 35975, '2023-12-31 23:00:00', 'apple')";

if (mysqli_query($conn, $sql)) {
    echo "New Item records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the category table
$sql = "CREATE TABLE Category (
cateID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
Category VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Category Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create category options for users
$sql = "INSERT INTO Category (cateID, Category)
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
$sql = "CREATE TABLE bid_history (
bid_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
item_id VARCHAR(255) NOT NULL,
user_id INT NOT NULL,
bid_price INT NOT NULL ,
bid_datetime TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Bid history Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create records for bid_history
$sql = "INSERT INTO bid_history(bid_id, item_id, user_id, bid_price, bid_datetime)
VALUES (1, 121, 5203, 100, '2020-10-10'),
       (2, 524, 1231, 420, '2022-12-12')";

if (mysqli_query($conn, $sql)) {
    echo "New bid_history records created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the wishlist table
$sql = "CREATE TABLE wishlist (
    listID INT NOT NULL PRIMARY KEY ,
    itemID INT NOT NULL,
    userID INT NOT NULL
)";

if (mysqli_query($conn, $sql)){
    echo "Table wishlist created successfully. ";
}else{
    echo "Error: ".$sql."<br>".mysqli_error($conn);
}

// Create the records for the wishlist table
$sql = "INSERT INTO wishlist (listID, itemID, userID)
VALUES (234, 422, 323),
       (49439, 2314, 86754),
       (123123, 12421, 1242)";

if (mysqli_query($conn, $sql)){
    echo "New wishlist records created successfully. ";
}else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the table for
$sql = "CREATE TABLE conditions (
    conditionID INT NOT NULL ,
    condDescript VARCHAR(255) NOT NULL 
)";

if (mysqli_query($conn, $sql)){
    echo "Table conditions created successfully. ";
} else {
    echo "Error: ".$sql."<br>".mysqli_error($conn);
}

// Create records for the conditions table
$sql = "INSERT INTO conditions (conditionID, condDescript)
VALUES (1, 'Brand new'),
       (2, 'Like new'),
       (3, 'Very good'),
       (4, 'Good'),
       (5, 'Acceptable')";

if (mysqli_query($conn, $sql)){
    echo  "New conditions records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the wishlist table
$sql = "CREATE TABLE Wishlist (
userID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
itemID INT NOT NULL)";

mysqli_close($conn);
?>
