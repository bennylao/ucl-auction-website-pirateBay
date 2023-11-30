<?php
include_once("header.php");
require_once 'includes/create_auction_view.inc.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
    $item_id = $_POST['itemId'];
    $title = $_POST['itemTitle'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $condition = $_POST['condDescript'];
    $brand = $_POST['brand'];
}

?>

<div class="container">

  <!-- Create auction form -->
  <div style="max-width: 800px; margin: 10px auto">
    <h2 class="my-3">Edit auction</h2>
    <div class="card">
      <div class="card-body">
        <!-- Note: This form does not do any dynamic / client-side /
        JavaScript-based validation of data. It only performs checking after
        the form has been submitted, and only allows users to try once. You
        can make this fancier using JavaScript to alert users of invalid data
        before they try to send it, but that kind of functionality should be
        extremely low-priority / only done after all database functions are
        complete. -->
        <form method="post" action="manage_item_backend.php" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="itemTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="itemTitle" name="itemTitle"
                     value="<?php echo $title; ?>">
              <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short
                description of the item you're selling, which will display in listings.</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="brand" class="col-sm-2 col-form-label text-right">Brand of auction</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $brand; ?>">
              <small id="titlhelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
          </div>
          <div class="form-group row">
            <label for="description" class="col-sm-2 col-form-label text-right">Description</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="description" name="description" rows="4"><?php echo $description;?></textarea>
              <small id="detailsHelp" class="form-text text-muted">Full details of the listing to help bidders decide
                if it's what they're looking for.</small>
            </div>
          </div>
          <div class="form-group row">
            <label for="category" class="col-sm-2 col-form-label text-right">Category</label>
            <div class="col-sm-10">
              <select class="form-control" id="category" name="category">
                  <?php
                  $find_categories_query = "SELECT * FROM categories";
                  // SQL to fetch data
                  $result = mysqli_query($connection, $find_categories_query);
                  while ($row = mysqli_fetch_assoc($result)) {
                      $cat = $row['category'];
                      $categoryId = $row['cateId'];
                      if ($category == $cat) {
                          $isSelected = "selected";
                      } else {
                          $isSelected = "";
                      }

                      echo "<option value=$categoryId $isSelected> $cat </option>";
                  }
                  ?>
              </select>
              <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span>
                Select a category for this item.</small>
            </div>
            <div class="form-group row">
              <label for="condDescript" class="col-sm-2 col-form-label text-right">Condition</label>
              <div class="col-sm-10">
                <select class="form-control" id="condDescript" name="condDescript">
                    <?php
                    $find_conditions_query = "SELECT * FROM conditions";
                    // SQL to fetch data
                    $result = mysqli_query($connection, $find_conditions_query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $conditionDescription = $row['condDescript'];
                        $conditionId = $row['conditionId'];
                        if ($condition == $conditionDescription) {
                            $isSelected = "selected";
                        } else {
                            $isSelected = "";
                        }
                        echo "<option value=$conditionId $isSelected> $conditionDescription </option>";
                    }
                    ?>
                </select>
                <small id="conditionHelp" class="form-text text-muted"><span class="text-danger">* Required.</span>
                  Select a condition for this item.</small>
              </div>
            </div>
            <input type='hidden' name='actionType' value='editItem'>
            <input type='hidden' name='itemId' value=<?php echo $item_id;?>>
            <button type="submit" class="btn btn-primary form-control">Edit Auction</button>
            <br><br>
            <a href="listing.php?item_id=<?php echo $item_id;?>" class="btn btn-secondary form-control">Discard changes</a>
        </form>
      </div>
    </div>
  </div>

</div>


<?php
mysqli_close($connection);
include_once("footer.php")
?>

