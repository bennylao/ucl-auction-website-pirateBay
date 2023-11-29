<?php
$title = "Admin Management";
include_once("header.php");
require_once("config_database.php")
?>

<?php
// Create database connection
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>

<div class="container" style="max-width: 80%;">

  <h2 class="my-3">Browse listings</h2>
  <h2>Admin Management Page</h2>
  <h5>
    This is the page for admin to manage the available options for Categories and Conditions that Users can choose from
  </h5>
  <br>

  <div class="row">

    <div class="col-sm">
      <h3>Existing Categories</h3>
        <?php
        $find_categories_query = "SELECT * FROM categories";
        // SQL to fetch data
        $result = mysqli_query($connection, $find_categories_query);
        while ($row = mysqli_fetch_assoc($result)) {
            $category = $row['category'];
            $categoryId = $row['cateId'];
            echo "<i class='bi bi-tag'></i> $category<br>";
        }
        ?>
      <br>
      <h5 class="font-weight-bold">Edit Categories:</h5>
      <button type="button" class="btn btn-outline-success" onclick="window.location.href='admin_add_category.php';">Add Category</button>
      <button type="button" class="btn btn-outline-warning" onclick="window.location.href='admin_edit_category.php';">Edit Category</button>
      <button type="button" class="btn btn-outline-danger">Remove Category</button>
    </div>
    <div class="col-sm">
      <h3>Existing Conditions</h3>

        <?php
        $find_conditions_query = "SELECT * FROM conditions";
        // SQL to fetch data
        $result = mysqli_query($connection, $find_conditions_query);
        while ($row = mysqli_fetch_assoc($result)) {
            $conditionDescription = $row['condDescript'];
            $conditionId = $row['conditionId'];
            echo "<i class='bi bi-tag-fill'></i> $conditionDescription<br>";
        }
        ?>

      <br>
      <h5 class="font-weight-bold">Edit Conditions:</h5>
      <button type="button" class="btn btn-outline-success">Add Condition</button>
      <button type="button" class="btn btn-outline-warning">Edit Condition</button>
      <button type="button" class="btn btn-outline-danger">Remove Condition</button>
    </div>
  </div>
</div>

<br><br>
