<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_name = 'testingDB';

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Drop the database if it exists
$sql = "DROP DATABASE IF EXISTS testingDB";
mysqli_query($conn, $sql);

// Create database
$sql = "CREATE DATABASE testingDB";
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
password VARCHAR(255) NOT NULL)";

if (mysqli_query($conn, $sql)) {
    echo "Table created successfully";
} else {
    echo "Error creating table: ". mysqli_error($conn);
}

// Create records
$sql = "INSERT INTO Users (username, password) VALUES ('admin', 'admin123'), ('user1', 'user123')";
if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
