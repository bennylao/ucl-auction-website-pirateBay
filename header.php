
<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_only_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'localhost',
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);
session_start();

if (!isset($_SESSION["last_regeneration"])) {
    regenerate_session_id();
} else {
    $interval = 60 * 30;
    if (time() - $_SESSION["last_regeneration"] >= $interval) {
        regenerate_session_id();
    }
}


function regenerate_session_id()
{
    session_regenerate_id();
    $_SESSION["last_regeneration"] = time();
}


?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">
  <title>
      <?php
      if (isset($title)) {
          echo $title;
      } else {
          echo "Auction";
      }
      ?>
  </title>
</head>


<body>

<!-- Navbars -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
  <a class="navbar-brand" href="browse.php">Site Name</a>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">

        <?php
        // Displays either login or logout on the right, depending on user's
        // current status (session).
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            echo '<div class="d-flex align-items-center">';
            echo '<a class="btn nav-link" href="user_homepage.php">My account</a>';
            echo '<a class="btn nav-link" href="logout.php">Logout</a>';
            echo '</div>';
        } else {
            echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login</button>';
        }
        ?>
    </li>
  </ul>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <ul class="navbar-nav align-middle">
    <li class="nav-item mx-1">
      <a class="nav-link" href="browse.php">Browse</a>
    </li>
      <?php
      if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {   //this is buyer type account
          echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mybids.php">My Bids</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link" href="recommendations.php">Recommended</a>
    </li>
    <li class="nav-item mx-1">
      <a class="nav-link" href="wishlist.php">Wishlist</a>
    </li>');
      }
      if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {  //this is seller type account
          echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mylistings.php">My Listings</a>
    </li>
	<li class="nav-item ml-3">
      <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
    </li>');
      }
      if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyerseller') {   //this is buyerseller type account
          echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mybids.php">My Bids</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link" href="recommendations.php">Recommended</a>
    </li>
    <li class="nav-item mx-1">
      <a class="nav-link" href="wishlist.php">Wishlist</a>
    </li>
    <li class="nav-item mx-1">
      <a class="nav-link" href="mylistings.php">My Listings</a>
    </li>
	<li class="nav-item ml-3">
      <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
    </li>');
      }
      ?>
  </ul>
</nav>

<!-- Login modal -->
<div class="modal fade" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="login_result.php">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-primary form-control">Sign in</button>
        </form>
        <div class="text-center">or <a href="register.php">create an account</a></div>
      </div>

    </div>
  </div>
</div> <!-- End modal -->

<?php //Only shows up if logged in
require("config_database.php");

if (isset($_SESSION['logged_in'])) {   //Only shows up if logged in

// Retrieve data (userid from the session)
    $currentUserId = $_SESSION['id'];

    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

// SQL to fetch data
    $query = "SELECT 
    i.itemId, 
    i.itemTitle, 
    i.sellerId,
    i.ownerId, 
    userBids.userId, 
    i.endDateTime, 
    userBids.maxUserBid AS bidPrice,
    CASE 
        WHEN userBids.maxUserBid IS NULL THEN 'No Bids'
        WHEN userBids.maxUserBid = maxBid.maxBidPrice THEN 'Winner'
        ELSE 'Not Highest Bidder'
    END AS BidStatus
FROM 
    items i 
LEFT JOIN (
    SELECT 
        b.itemId, 
        b.userId,
        MAX(b.bidPrice) as maxUserBid
    FROM 
        bidHistory b
    GROUP BY 
        b.itemId, b.userId
) AS userBids ON i.itemId = userBids.itemId
LEFT JOIN (
    SELECT 
        itemId, 
        MAX(bidPrice) AS maxBidPrice
    FROM 
        bidHistory
    GROUP BY 
        itemId
) AS maxBid ON i.itemId = maxBid.itemId
WHERE     (userBids.userId = $currentUserId OR i.sellerId = $currentUserId) AND i.endDateTime < NOW()
ORDER BY 
    i.itemId, userBids.maxUserBid DESC;;";

    $result = mysqli_query($connection, $query);

    if ($result->num_rows > 0) {
// Output data of each row
        while ($row = $result->fetch_assoc()) {
            $itemId = $row["itemId"];
            $itemTitle = $row["itemTitle"];
            $sellerId = $row["sellerId"];
            $ownerId = $row["ownerId"];
            $userId = $row["userId"];
            $bidPrice = $row["bidPrice"];
            $endDateTime = $row["endDateTime"];
            $bidStatus = $row["BidStatus"];

            if ($sellerId == $ownerId) {    // item didn't sell
                if ($sellerId == $currentUserId){  // to inform the seller
                    echo "</nav>
<div class='alert_red'>
    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
        Sorry your item $itemTitle didn't sell.
</div>";}
                else if ($bidStatus == 'Not Highest Bidder'){   // to inform the bidder
                    echo "<div class='alert_red'>
    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
    Sorry you didn't win $itemTitle.
</div>";}}
            else if ($sellerId != $ownerId){    // the item sold
                if ($sellerId == $currentUserId){   // to inform the seller
                    echo "</nav>
<div class='alert_green'>
    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
        Congrats your item $itemTitle sold.
</div>";}
                else if ($bidStatus == 'Winner'){   //to inform the winner
                    echo "</nav>
<div class='alert_green'>
    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
        Congrats you won the item $itemTitle!
</div>";}
                else if ($bidStatus == 'Not Highest Bidder'){   //to inform the losers
                    echo "</nav>
<div class='alert_red'>
    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
        Sorry you didn't win the auction.
</div>";}
            }
        }
        }
    }

//if ($result->num_rows > 0) {
//if ($sellerId == $ownerId) {    // item didn't sell
//    if ($sellerId == $currentUserId){  // to inform the seller
//        echo "</nav>
//<div class='alert_red'>
//    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
//        Sorry your item $itemTitle didn't sell.
//</div>";}
//    else if ($bidStatus == 'Not Highest Bidder'){   // to inform the bidder
//        echo "<div class='alert_red'>
//    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
//    Sorry you didn't win $itemTitle.
//</div>";}}
//else if ($sellerId != $ownerId){    // the item sold
//    if ($sellerId == $currentUserId){   // to inform the seller
//        echo "</nav>
//<div class='alert_green'>
//    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
//        Congrats your item $itemTitle sold.
//</div>";}
//    else if ($bidStatus == 'Winner'){   //to inform the winner
//        echo "</nav>
//<div class='alert_green'>
//    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
//        Congrats you won the item $itemTitle!
//</div>";}
//    else if ($bidStatus == 'Not Highest Bidder'){   //to inform the losers
//        echo "</nav>
//<div class='alert_red'>
//    <span class='closebtn' onclick='this.parentElement.style.display= &#39;none &#39;';>&times;</span>
//        Sorry you didn't win the auction.
//</div>";}
//}
//}
//}
?>

<!--Notification map:-->

<!--    if sellerId == ownerId:-->
<!--        if sellerId == $currentUserId:-->
<!--            "Your item didn't sell."-->
<!--        if userId not highest bidder:-->
<!--            "The item didn't sell. Your bid didn't win."-->

<!--    if sellerId != ownerId:-->
<!--        if sellerId = userId:-->
<!--            "Your item sold"-->
<!--        else if userId == highestBidder:-->
<!--            "Congrats you won!"-->
<!--        else if userId != highestBidder:-->
<!--            "Sorry you didn't win the auction."-->