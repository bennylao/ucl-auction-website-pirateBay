<?php

include_once("header.php");
require_once("utilities.php");
require_once("config_database.php");
?>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Get info from the URL:
$item_id = $_GET['item_id'];
//$end_time = null;
//$reserve_price = null;

// TODO: Use item_id to make a query to the database.


    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

// SQL to fetch data
    $query = "SELECT i.itemId, i.itemTitle, i.description, i.startingPrice,
       i.endDateTime, MAX(b.bidPrice), COUNT(b.itemId), i.reservedPrice, c.category, con.condDescript
    FROM items i
        INNER JOIN category c ON i.category = c.cateId
        INNER JOIN conditions con ON i.conditions = con.conditionId
         LEFT JOIN bidHistory b ON i.itemId = b.itemId
    WHERE i.itemId = $item_id";

    $result = mysqli_query($connection, $query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = $row["itemTitle"];
            $description = $row["description"];
            $starting_price = $row["startingPrice"];
            $current_price = ($row["MAX(b.bidPrice)"] !== null) ? $row["MAX(b.bidPrice)"]: 0;
            $num_bids = $row["COUNT(b.itemId)"];
            $end_time = new DateTime($row["endDateTime"]);
            $reserve_price = $row["reservedPrice"];
            $category = $row["category"];
            $condition = $row["condDescript"];
        }
    }
    else {
        echo "No results found.";
    }
if ($current_price <= $starting_price){
    $highest_price = $starting_price;
}
else {
    $highest_price = $current_price;
}
// Retrieve the values from the form


// TODO: Note: Auctions that have ended may pull a different set of data,
//       like whether the auction ended in a sale or was cancelled due
//       to lack of high-enough bids. Or maybe not.

// Calculate time to auction end:
$now = new DateTime();

if ($now < $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining= ' (in ' . display_time_remaining($time_to_end) . ')';
    global $time_remaining;
}

// TODO: If the user has a session, use it to make a query to the database
//       to determine if the user is already watching this item.
//       For now, this is hardcoded.
$watching = false;
if (isset($_SESSION['logged_in']))
{
    $currentUserId = $_SESSION['id'];

    $query = "SELECT itemId, userId, COUNT(itemId)
            FROM wishList 
            WHERE userId = $currentUserId AND itemId = $item_id
            GROUP BY itemId, userId";

    $result = mysqli_query($connection, $query);

    if ($result->num_rows > 0)
    {
        $watching = true;
    }
}

mysqli_close($connection);
?>


<div class="container">

  <div class="row"> <!-- Row #1 with auction title + watch button -->
    <div class="col-sm-8"> <!-- Left col -->
      <h2 class="my-3"><?php echo($title); ?></h2>
    </div>
    <div class="col-sm-4 align-self-center"> <!-- Right col -->
        <?php
        /* The following watchlist functionality uses JavaScript, but could
           just as easily use PHP as in other places in the code */
        if ($now < $end_time):
            ?>
        <div id="watch_nowatch" <?php if ((isset($_SESSION['logged_in']) && $watching) or !isset($_SESSION['logged_in'])
            or ($_SESSION['account_type'] == 'seller')) echo('style="display: none"');?> >
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to wishlist</button>
            </div>
            <div id="watch_watching" <?php if (!isset($_SESSION['logged_in']) or !$watching) echo('style="display: none"');?> >
                <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove from wishlist</button>
            </div>
        <?php endif /* Print nothing otherwise */ ?>

    </div>
  </div>

  <div class="row"> <!-- Row #2 with auction description + bidding info -->
    <div class="col-sm-8"> <!-- Left col with item info -->

      <div class="itemDescription">
          <?php echo $description ; ?>
      </div>
        <div class="itemDescription">
            Category: <?php echo $category ; ?>
        </div>
        <div class="itemDescription">
            Condition: <?php echo $condition ; ?>
        </div>


    </div>

    <div class="col-sm-4"> <!-- Right col with bidding info -->

      <p>
          <?php if ($now > $end_time):
              $mysqli = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
              $query = "SELECT userId, MAX(bidPrice) as maxPrice FROM bidHistory WHERE itemId = ? GROUP BY userId ORDER BY maxPrice DESC LIMIT 1";
              $stmt = $mysqli->prepare($query);
              $stmt->bind_param('i', $item_id);
              $stmt->execute();
              $result = $stmt->get_result();
              $highestBid = $result->fetch_assoc();
              if ($highestBid && $highestBid['maxPrice'] > $starting_price) {

                  $updateQuery = "UPDATE items SET ownerId = ? WHERE itemId = ?";
                  $updateStmt = $mysqli->prepare($updateQuery);
                  $updateStmt->bind_param('ii', $highestBid['userId'], $item_id);
                  $updateStmt->execute();
                  mysqli_close($mysqli);
                  echo "This auction ended: " . date_format($end_time, 'j M H:i');
                  echo "<br>The winner is user with ID: " . $highestBid['userId'];
          }
              endif;
              ?>
              <?php
          if (($current_price < $reserve_price || $current_price < $starting_price || $current_price === 0) && $now > $end_time){
              echo ("Bidding price lower than reserve price, bidding failed.");
          }
              ?>
            <!-- TODO: Print the result of the auction here? -->
          <?php if ($now < $end_time): ?>
        <p>Auction ends <?php echo date_format($end_time, 'j M H:i') . ' ' . $time_remaining; ?></p>
        <p class="lead">Starting price: £<?php echo number_format($starting_price, 2); ?></p>
        <p class="lead">Current bid: £<?php echo number_format($current_price, 2); ?></p>
        <p class="lead">Total bids: <?php echo number_format($num_bids); ?></p>
        <?php endif; ?>
      <!-- Bidding form -->
        <?php
        if ($now < $end_time) {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {  #if logged in, print the form
            echo '<form method="POST" action="place_bid.php">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">£</span>
          </div>
          <input type="number" step="0.01" value="0.00" class="form-control" id="bid" name="bid">
        </div>
        <button type="submit" class="btn btn-primary form-control">Place bid</button>
          <input type="hidden" name="item_id" value = '.htmlspecialchars($item_id).'>
          <input type="hidden" name="highest_price" value = '.htmlspecialchars($highest_price).'>
      </form>';
        }else{
            echo 'Please log in to place bid.';
        }}?>


    </div> <!-- End of right col with bidding info -->

  </div> <!-- End of row #2 -->


    <?php include_once("footer.php") ?>


  <script>
      // JavaScript functions: addToWatchlist and removeFromWatchlist.

      function addToWatchlist(button) {
          console.log("These print statements are helpful for debugging btw");

          // This performs an asynchronous call to a PHP function using POST method.
          // Sends item ID as an argument to that function.
          $.ajax('watchlist_funcs.php', {
              type: "POST",
              data: {functionname: 'add_to_watchlist', arguments: <?php echo json_encode($item_id);?>},

              success:
                  function (obj, textstatus) {
                      // Callback function for when call is successful and returns obj
                      console.log("Success");
                      var objT = obj.trim();

                      if (objT == "success") {
                          $("#watch_nowatch").hide();
                          $("#watch_watching").show();
                      }
                      else {
                          var mydiv = document.getElementById("watch_nowatch");
                          mydiv.appendChild(document.createElement("br"));
                          mydiv.appendChild(document.createTextNode(objT));
                      }
                  },

              error:
                  function (obj, textstatus) {
                      console.log("Error");
                  }
          }); // End of AJAX call

      } // End of addToWatchlist func

      function removeFromWatchlist(button) {
          // This performs an asynchronous call to a PHP function using POST method.
          // Sends item ID as an argument to that function.
          $.ajax('watchlist_funcs.php', {
              type: "POST",
              data: {functionname: 'remove_from_watchlist', arguments: <?php echo json_encode($item_id);?>},

              success:
                  function (obj, textstatus) {
                      // Callback function for when call is successful and returns obj
                      console.log("success");
                      var objT = obj.trim();

                      if (objT == "success") {
                          $("#watch_watching").hide();
                          $("#watch_nowatch").show();
                      } else {
                          var mydiv = document.getElementById("watch_watching");
                          mydiv.appendChild(document.createElement("br"));
                          mydiv.appendChild(document.createTextNode(objT));
                      }
                  },

              error:
                  function (obj, textstatus) {
                      console.log("Error");
                  }
          }); // End of AJAX call

      } // End of addToWatchlist func
  </script>