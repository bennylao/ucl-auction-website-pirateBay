<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_name = 'auctionDataBase';

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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

// Create the table
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

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
        VALUES ('1', '1111'),
               ('2', '1111')";

if (mysqli_query($conn, $sql)) {
    echo "Admin records created successfully. \n";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the first table Users
$sql = "CREATE TABLE Users (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
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
$sql = "INSERT INTO Users (username, password, email, first_name, last_name, address, create_date)
VALUES ('admin', 'admin123','email','fn','ln','addr','2017-06-05'), 
       ('user1', 'user123','email1','fn1','ln1','addr1','2017-06-05'),
       ('user2', 'user234','email2','fn2','ln2','addr2','2017-06-05'),
       ('victor', 'victorpw','victor2263@gmail.com','victor','chan','123 Oxford Street','2023-10-31')";

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
starting_price DECIMAL NOT NULL,
reserve_price DECIMAL,
current_price DECIMAL NOT NULL,
seller_id INT NOT NULL,
end_date date,
end_time time,
delivery_status VARCHAR(255))";

if (mysqli_query($conn, $sql)) {
    echo "Table Item created successfully. ";
} else {
    echo "Error creating table Item: ". mysqli_error($conn);
}

// Create records for users
$sql = "INSERT INTO Items (id, name, category, description, starting_price,
                   reserve_price, current_price, seller_id, end_date, end_time, delivery_status)
        VALUES ('1', 'abc', 'books', 'new', '10', '200', '10', '54', '2023-10-31', '23:59:59', 'unsold'),
               ('2', 'cbd', 'car', 'new', '20000', '40000', '24000', '23', '2023-10-31', '23:00:00', 'sold'),
               ('3', 'sdf', 'home', 'used', '1', '10', '10', '634', '2023-10-31', '23:00:00', 'dispatched')";

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
        VALUES ('1', 'Art and Collectibles'),
               ('2', 'Antiques'),
               ('3', 'Automobiles and Vehicles'),
               ('4', 'Jewelry and Watches'),
               ('5', 'Electronics and Technology'),
               ('6', 'Fashion and Apparel'),
               ('7', 'Sports and Memorabilia'),
               ('8', 'Wine and Spirits'),
               ('9', 'Furniture and Home Decor'),
               ('10', 'Real Estate')";

if (mysqli_query($conn, $sql)) {
    echo "Category options created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create the bid history table
$sql = "CREATE TABLE bid_history (
itemID INT NOT NULL PRIMARY KEY,
starting_price DECIMAL NOT NULL,
current_price DECIMAL NOT NULL,
end_date date,
end_time time
)";

if (mysqli_query($conn, $sql)) {
    echo "Bid history Table created successfully. ";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Create records for bid_history
$sql = "INSERT INTO bid_history(itemID, starting_price, current_price, end_date, end_time)
VALUES ('232', '100', '120', '2023-11-01', '12:00:00'),
       ('123', '400', '500', '2023-11-03', '12:00:00')";

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
VALUES ('234', '422', '323'),
       ('49439', '2314', '86754')";

if (mysqli_query($conn, $sql)){
    echo "New wishlist records created successfully. ";
}else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}



mysqli_close($conn);
?>
