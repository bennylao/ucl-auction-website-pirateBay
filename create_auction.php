<?php
include_once("header.php");
require_once 'includes/create_auction_view.inc.php';

$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>



  <div class="container">

    <!-- Create auction form -->
    <div style="max-width: 800px; margin: 10px auto">
      <h2 class="my-3">Create new auction</h2>
      <div class="card">
        <div class="card-body">
          <!-- Note: This form does not do any dynamic / client-side /
          JavaScript-based validation of data. It only performs checking after
          the form has been submitted, and only allows users to try once. You
          can make this fancier using JavaScript to alert users of invalid data
          before they try to send it, but that kind of functionality should be
          extremely low-priority / only done after all database functions are
          complete. -->
          <form method="post" action="create_auction_result.php" enctype="multipart/form-data">
            <div class="form-group row">
              <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="auctionTitle" name="auctionTitle"
                       placeholder="e.g. Black mountain bike">
                <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short
                  description of the item you're selling, which will display in listings.</small>
              </div>
            </div>
            <div class="form-group row">
              <label for="auctionBrand" class="col-sm-2 col-form-label text-right">Brand of auction</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="auctionBrand" name="auctionBrand" placeholder="e.g. Apple">
                <small id="titlhelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
              </div>
            </div>
            <div class="form-group row">
              <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="auctionDetails" name="auctionDetails" rows="4"></textarea>
                <small id="detailsHelp" class="form-text text-muted">Full details of the listing to help bidders decide
                  if it's what they're looking for.</small>
              </div>
            </div>
            <div class="form-group row">
              <label for="auctionCategory" class="col-sm-2 col-form-label text-right">Category</label>
              <div class="col-sm-10">
                <select class="form-control" id="auctionCategory" name="auctionCategory">
                    <?php
                    $find_categories_query = "SELECT * FROM categories";
                    // SQL to fetch data
                    $result = mysqli_query($connection, $find_categories_query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $category = $row['category'];
                        $categoryId = $row['cateId'];

                        echo "<option value=$categoryId> $category </option>";
                    }
                    ?>
                </select>
                <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span>
                  Select a category for this item.</small>
              </div>
              <div class="form-group row">
                <label for="auctionConditions" class="col-sm-2 col-form-label text-right">Condition</label>
                <div class="col-sm-10">
                  <select class="form-control" id="condition" name="conditions">
                    <?php
                    $find_conditions_query = "SELECT * FROM conditions";
                    // SQL to fetch data
                    $result = mysqli_query($connection, $find_conditions_query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $conditionDescription = $row['condDescript'];
                        $conditionId = $row['conditionId'];

                        echo "<option value=$conditionId > $conditionDescription </option>";
                    }
                    ?>
                  </select>
                  <small id="conditionHelp" class="form-text text-muted"><span class="text-danger">* Required.</span>
                    Select a condition for this item.</small>
                </div>

              </div>
              <div class="form-group row">
                <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">£</span>
                    </div>
                    <input type="number" class="form-control" id="auctionStartPrice" name="auctionStartPrice">
                  </div>
                  <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span>
                    Initial bid amount.</small>
                </div>
              </div>
              <div class="form-group row">
                <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">£</span>
                    </div>
                    <input type="number" class="form-control" id="auctionReservePrice" name="auctionReservePrice">
                  </div>
                  <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price
                    will not go through. This value is not displayed in the auction listing.</small>
                </div>
              </div>
              <div class="form-group row">
                  <label for="auctionImages" class="col-sm-2 col-form-label text-right">Upload Images</label>
                  <div class="col-sm-10">
                      <input type="file" class="form-control-file" id="auctionImages" name="auctionImages[]" accept="image/*" multiple>
                      <small id="imagesHelp" class="form-text text-muted">9 images maximum</small>
                  </div>
              </div>
              <div class="form-group row">
                <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
                <div class="col-sm-10">
                  <input type="datetime-local" class="form-control" id="auctionEndDate" name="auctionEndDate">
                  <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day
                    for the auction to end.</small>
                </div>
              </div>
              <button type="submit" class="btn btn-primary form-control">Create Auction</button>
          </form>
        </div>
      </div>
    </div>

  </div>


<?php
mysqli_close($connection);
include_once("footer.php")
?>

