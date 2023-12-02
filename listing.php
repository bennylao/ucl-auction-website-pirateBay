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
$query = "SELECT i.itemId, i.itemTitle, i.description, i.sellerId, i.startingPrice, 
       i.endDateTime, MAX(b.bidPrice), COUNT(b.itemId), i.reservedPrice, c.category, con.condDescript, i.brand
    FROM items i
        INNER JOIN categories c ON i.category = c.cateId
        INNER JOIN conditions con ON i.conditions = con.conditionId
        LEFT JOIN bidHistory b ON i.itemId = b.itemId
    WHERE i.itemId = $item_id
    GROUP BY i.itemId";

    $result = mysqli_query($connection, $query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = $row["itemTitle"];
            $description = $row["description"];
            $seller_id = $row["sellerId"];
            $starting_price = $row["startingPrice"];
            $current_price = ($row["MAX(b.bidPrice)"] !== null) ? $row["MAX(b.bidPrice)"]: 0;
            $num_bids = $row["COUNT(b.itemId)"];
            $end_time = new DateTime($row["endDateTime"]);
            $reserve_price = $row["reservedPrice"];
            $category = $row["category"];
            $condition = $row["condDescript"];
            $brand = $row["brand"];
        }
    }
    else {
        echo "No results found.";
    }

$query = "SELECT imagePath FROM images WHERE itemID = $item_id";
    $result = mysqli_query($connection, $query);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $images[] = $row['imagePath'];
        }
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
            or ($_SESSION['account_type'] == 'seller') or ($_SESSION['account_type'] == 'admin')) echo('style="display: none"');?> >
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to wishlist</button>
            </div>
            <div id="watch_watching" <?php if (!isset($_SESSION['logged_in']) or ($_SESSION['account_type'] == 'admin')or !$watching) echo('style="display: none"');?> >
                <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove from wishlist</button>
            </div>
        <?php endif /* Print nothing otherwise */ ?>

    </div>
  </div>

  <div class="row"> <!-- Row #2 with auction description + bidding info -->
    <div class="col-sm-8"> <!-- Left col with item info -->

          <?php
          echo "<h6>Brand: $brand</h6>";
          echo "<h6>Item Description: $description</h6>";
          echo "<h6>Category: $category</h6>";
          echo "<h6>Condition: $condition</h6>"; ?>

        <?php
        if (!empty($images)) {
            echo '<div class="itemDescription">';
            foreach ($images as $path) {
                echo '<img src="' . htmlspecialchars($path) . '" alt="Item Image" style="max-width: 100%; height: auto;">';
            }
            echo '</div>';
        } else {
            // Fallback if no images are found
            echo '<div class="itemDescription">';
            echo '<img src="auction_image/Image_not_available.png" alt="Item Image" width="900">';
            echo '</div>';
        }
        ?>
        <?php
        $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
        if (isset($_SESSION['logged_in'])) {

        $currentUserName = $_SESSION['user_name'];
        $historyQuery = "SELECT u.userName, b.bidPrice, b.bidDateTime, b.userId
                    FROM bidHistory b LEFT JOIN users u
                    ON b.userId = u.userId
                    WHERE b.itemId = $item_id;";
        $historyResult = mysqli_query($connection, $historyQuery);
        if ($historyResult->num_rows > 0) {
          echo "<br><h5>Auction History</h5>";
        // Output data of each row
            while ($row = $historyResult->fetch_assoc()) {
            $userName = ($row["userName"] == $currentUserName) ? "<b>You</b>" : $row["userName"];
            $bidPrice = $row["bidPrice"];
            $bidDateTime = $row["bidDateTime"];

            echo ("<p>$userName made a bid for $bidPrice at $bidDateTime.</p>");}   }

            else {
                echo "No one had made a bid on this item.";
            }}
        if (!isset($_SESSION['logged_in'])) {
            echo "Please log in to see bidding history.";
        }





        ?>




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

              if ($highestBid && $highestBid['maxPrice']>= $reserve_price && $_SESSION['id'] == $highestBid['userId']) {
                  $updateQuery = "UPDATE items SET ownerId = ? WHERE itemId = ?";
                  $updateStmt = $mysqli->prepare($updateQuery);
                  $updateStmt->bind_param('ii', $highestBid['userId'], $item_id);
                  $updateStmt->execute();
                  mysqli_close($mysqli);
                  echo "This auction ended: " . date_format($end_time, 'j M H:i');
                  echo "<br> Congratulations, your bid of £" . $highestBid['maxPrice'] . " was successful";
                  echo "<p>Pay securely here: \n</p>";
                  echo '<button type="submit" class="btn btn-primary form-control"> Pay now </button>';

//                     <!-- Payment form --

           echo '
         <form method="post" action="create_auction_result.php">
            <div Now securely here </div>
            <div class="form-group row">
                <label for="Fullname" class="col-sm-2 col-form-label text-right">Full name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="Fullname"
                           placeholder="e.g. Vin Diesel">
                    <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
                </div>
            </div>
            <div class="form-group row">
                <label for="Cardnumber" class="col-sm-2 col-form-label text-right">Card number</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="auctionBrand">
                    <small id="titlhelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
                </div>
            </div>
            <div class="form-group row">
                <label for="Address" class="col-sm-2 col-form-label text-right">Address</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="Address" rows="4"></textarea>
                    <small id="detailsHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
                </div>
            </div>
              <div class="form-group row">
                  <label for="Securitycode" class="col-sm-2 col-form-label text-right">Security code</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="Securitycode">
                      <small id="titlhelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
                  </div>
              </div>
                <div class="form-group row">
                    <label for="expirydate" class="col-sm-2 col-form-label text-right">Expiry Date</label>
                    <div class="col-sm-10">
                        <input type="month" class="form-control" id="expirydate" name="expirydate">
                        <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
                    </div>
                </div>
        </form>'

                  ;
              }

              if (isset($highestBid) && is_array($highestBid) && array_key_exists('userId', $highestBid)) {
                  if ($_SESSION['id'] != $highestBid['userId']) {
                      $userBidQuery = "SELECT EXISTS(SELECT 1 FROM bidHistory WHERE itemId = ? AND userId = ?)";
                      $userBidStmt = $mysqli->prepare($userBidQuery);
                      $userBidStmt->bind_param('ii', $item_id, $_SESSION['id']);
                      $userBidStmt->execute();
                      $userBidResult = $userBidStmt->get_result()->fetch_row();

                      if ($userBidResult[0]) {
                          echo "<br>Your bid was unsuccessful.";
                          echo "<br>This auction ended: " . date_format($end_time, 'j M H:i');
                          echo "<br>The winner is user with ID: " . $highestBid['userId'];
                      }
                  }
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
            if ($seller_id == $currentUserId){
                echo 'You are the item seller.';
            } else if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'admin'){
                echo "
                <br><br>
                <h5>You are admin</h5>
                <p>If you think the item title, brand, description, category or condition is inappropiate,
                you can edit the item information.</p>
                <form method='post' action='admin_edit_item.php'>
                <button type='submit' class='btn btn-outline-warning form-control'>Edit Auction</button>
                <input type='hidden' name='itemId' value=$item_id>
                <input type='hidden' name='itemTitle' value='$title'>
                <input type='hidden' name='description' value='$description'>
                <input type='hidden' name='category' value='$category'>
                <input type='hidden' name='condDescript' value='$condition'>
                <input type='hidden' name='brand' value='$brand'> 
                </form>
                <br><br>
                <p>If you believe the auction is inappropiate, you can remove it from the auction site.</p>
                <button type='button' class='btn btn-outline-danger form-control' data-toggle='modal' data-target='#removeAuction'>Remove Auction</button>";

            } else if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller'){
                echo 'Please register a buyer or a buyer-seller account to start bidding';
            }else{
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
        }
        }else{
            echo 'Please log in to place bid.';
        }}?>

    </div> <!-- End of right col with bidding info -->

  </div> <!-- End of row #2 -->

  <!-- removeAuction modal -->
  <div class="modal fade" id="removeAuction">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Remove Auction</h4>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <h6>Are you sure you want to remove this auction for '<?php echo $title;?>'?</h6>
          <form method='post' action='manage_item_backend.php'>
            <div class="row justify-content-between">
              <div class="col-6">
                <button type='submit' class='btn btn-outline-danger form-control'>Remove</button>
              </div>
              <div class="col-6">
                <a href="listing.php?item_id=<?php echo $item_id;?>" class="btn btn-outline-primary form-control">Cancel</a>
              </div>
            </div>
            <input type='hidden' name='itemId' value=<?php echo $item_id;?>>
            <input type='hidden' name='itemTitle' value='<?php echo $title;?>'>
            <input type='hidden' name='actionType' value='removeItem'>
          </form>
        </div>

      </div>
    </div>
  </div> <!-- End modal -->

    <?php include_once("footer.php") ?>

    <script>
        var end_time = new Date('<?php echo $end_time->format(DateTime::ATOM); ?>').getTime();

        var refresh_interval = setInterval(function() {
            var now_time = new Date().getTime();
            var time_left = end_time - now_time;

            if (time_left <= 10000 && time_left > 0) {
                clearInterval(refresh_interval);
                setTimeout(function() {
                    window.location.reload();
                }, time_left);
            }
        }, 1000);

        setTimeout(function() {
            clearInterval(refresh_interval);
        }, end_time - new Date().getTime());
    </script>

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