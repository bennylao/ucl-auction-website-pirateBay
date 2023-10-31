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
    echo "Database created successfully";
} else {
    echo "Error creating database: ". mysqli_error($conn);
}
mysqli_close($conn);

// Create the table
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

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
    echo "Table created successfully";
} else {
    echo "Error creating table: ". mysqli_error($conn);
}

// Create records
$sql = "INSERT INTO Users (username, password, email, first_name, last_name, address, create_date)
VALUES ('admin', 'admin123','email','fn','ln','addr','2017-06-05'), 
       ('user1', 'user123','email1','fn1','ln1','addr1','2017-06-05'),
       ('user2', 'user234','email2','fn2','ln2','addr2','2017-06-05')
       ('victor', 'victorpw','victor2263@gmail.com','victor','chan','123 Oxford Street','2023-10-31');";

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
