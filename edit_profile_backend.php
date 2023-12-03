<?php
require_once "config_database.php";
require_once("utilities.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $conn = connect_to_database();
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
}



if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
session_start();
$userId = $_SESSION['id'];

if(!empty($firstName) || !empty($lastName) || !empty($username) || !empty($address)){
    $query = "UPDATE users SET ";
    if(!empty($firstName)){
        $query.="firstName = '$firstName', ";
    }
    if(!empty($lastName)){
        $query.="lastName = '$lastName', ";
    }
    if(!empty($username)){
        $query.="userName = '$username', ";
    }
    if(!empty($address)){
        $query.="address = '$address', ";
    }
    $query = rtrim($query, ', ');
    $query .= " WHERE userId = $userId";
    $result = mysqli_query($conn, $query);
    if($result){
        echo "<div style='text-align: center;'><br><h5>User profile updated successfully</h5></div>";
        header("refresh:2;user_homepage.php");
    }else{
        echo "<div style='text-align: center;'><br><h5>Failed to update</h5></div>";
        header("refresh:2;user_homepage.php");
    }
} else {
    echo "<div style='text-align: center;'><br><h5>No changes made</h5></div>";
    header("refresh:2;edit_profile.php");
}
?>