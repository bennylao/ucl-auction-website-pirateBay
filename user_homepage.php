<?php

include_once("header.php");
require_once("utilities.php");
require_once("config_database.php");

$conn = connect_to_database();

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$id = $_SESSION['id'];

$query = "SELECT password, accountType, email, firstName, lastName, address, createDate, userName FROM users WHERE userId = $id;";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $userName = $row['userName'];
    $typeId = $row['accountType'];
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

$query = "SELECT accountType FROM accountTypes WHERE typeId = $typeId";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $accountType = $row['accountType'];
} else {
    echo 'User profile not found!';
    header("refresh:5;url=browse.php");
}



?>


<div class="container">
    <div class="col-sm-8">
        <h2 class="my-3">Hello! <?php echo($userName); ?> (<?php echo("userID: $id")?>)</h2>
        <h3 class="my-3">User Profile</h3>
    </div>
    <div class="row">
        <div class="col">First name: <?php echo ($firstName)?></div>
        <div class="col">Last name: <?php echo ($lastName)?></div>

        <div class="w-100"></div>

        <div class="col">Account type: <?php echo ($accountType)?></div>
        <div class="col">Join date: <?php echo ($createDate)?></div>
    </div>
    <a href="edit_profile.php">Edit profile</a>

    <div class="col-sm-8">
        <h3 class="my-3">Delivery information</h3>
    </div>
    <div class="row">
        <div class="col">Address: <?php echo ($address)?></div>
    </div>
    <div class="col-sm-8">
        <?php
        if ($accountType == 'buyer') {
            echo '<h3 class="my-3">My bids</h3>';
            $query = "SELECT DISTINCT(itemId) FROM bidHistory WHERE userId = $id";
            $result = mysqli_query($conn, $query);
            $bidHisSize = mysqli_num_rows($result);
            echo "<div class='col'>You are now bidding on $bidHisSize items</div>";
            echo "<a href='mybids.php'>Click for more in your bidding history</a><br>";
            echo "<a href='browse.php'>Explore current active biddings</a>";
            echo '<h3 class="my-3">My wishlist</h3>';
            $query = "SELECT w.itemId FROM wishList w INNER JOIN items i ON i.itemId = w.itemId 
                WHERE userId = $id AND i.endDateTime > NOW()";
            $result = mysqli_query($conn, $query);
            $wishListSize = mysqli_num_rows($result);
            echo "<div class='col'>$wishListSize items in wishlist</div>";
            echo "<a href='wishlist.php'>Click for more in wishlist</a>";
        } elseif ($accountType == 'seller') {
            echo '<h3 class="my-3">My listings</h3>';
            $query = "SELECT itemId FROM items WHERE sellerId = $id";
            $result = mysqli_query($conn, $query);
            $listSize = mysqli_num_rows($result);
            echo "<div class='col'>You have $listSize items listing</div>";
            echo "<a href='mylistings.php'>Click for more in your listings</a><br>";
            echo "<a href='create_auction.php'>Create a new listing</a>";
        } elseif ($accountType == 'buyerseller') {
            echo '<h3 class="my-3">My bids</h3>';
            $query = "SELECT DISTINCT(itemId) FROM bidHistory WHERE userId = $id";
            $result = mysqli_query($conn, $query);
            $bidHisSize = mysqli_num_rows($result);
            echo "<div class='col'>You are now bidding on $bidHisSize items</div>";
            echo "<a href='mybids.php'>Click for more in your bidding history</a><br>";
            echo "<a href='browse.php'>Explore current active biddings</a>";
            echo '<h3 class="my-3">My wishlist</h3>';
            $query = "SELECT w.itemId FROM wishList w INNER JOIN items i ON i.itemId = w.itemId 
                WHERE userId = $id AND i.endDateTime > NOW()";
            $result = mysqli_query($conn, $query);
            $wishListSize = mysqli_num_rows($result);
            echo "<div class='col'>$wishListSize items in wishlist</div>";
            echo "<a href='wishlist.php'>Click for more in wishlist</a>";
            echo '<h3 class="my-3">My listings</h3>';
            $query = "SELECT itemId FROM items WHERE sellerId = $id";
            $result = mysqli_query($conn, $query);
            $listSize = mysqli_num_rows($result);
            echo "<div class='col'>You have $listSize items listing</div>";
            echo "<a href='mylistings.php'>Click for more in your listings</a><br>";
            echo "<a href='create_auction.php'>Create a new listing</a>";
        }
        ?>
    </div>
    <div class="col-sm-8">
        <h3 class="my-3">Privacy and security</h3>
        <a href="change_password.php">Change password</a>
    </div>
</div>
<br><br><br>
<?php include_once("footer.php") ?>