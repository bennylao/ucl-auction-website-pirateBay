<?php

include_once("header.php");
require_once("utilities.php");
require_once("config_database.php");

$conn = connect_to_database();

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$id = $_SESSION['id'];
$userName = $_SESSION['user_name'];


$query = "SELECT password, accountType, email, firstName, lastName, address, createDate FROM users WHERE userId = $id;";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $accountType = $row['accountType'];
    $password = $row['password'];
    $email = $row['email'];
    $firstName = $row['firstName'];
    $lastName = $row['lastName'];
    $address = $row['address'];
    $createDate = $row['createDate'];
} else {
    echo 'User profile not found!';
    header("refresh:5;url=browse.php");
}




?>


<div class="container">
    <div class="col-sm-8">
        <h2 class="my-3">Hello! <?php echo($userName); ?> (<?php echo($id)?>)</h2>
        <h3 class="my-3">User Profile</h3>
    </div>
    <div class="row">
        <div class="col">First name: <?php echo ($firstName)?></div>
        <div class="col">Last name: <?php echo ($lastName)?></div>

        <div class="w-100"></div>

        <div class="col">Account type: <?php echo ($accountType)?></div>
        <div class="col">Join date: <?php echo ($createDate)?></div>
    </div>

    <div class="col-sm-8">
        <h3 class="my-3">Delivery information</h3>
    </div>
    <div class="row">
        <div class="col">Address: <?php echo ($address)?></div>
    </div>
</div>

