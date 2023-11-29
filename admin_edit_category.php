<?php
include_once("header.php");
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>

  <div class="container">
    <div style="max-width: 800px; margin: 10px auto">
      <h2 class="my-3">Edit Category</h2>
      <div class="card">
        <div class="card-body">
          <form method="post" action="manage_category_backend.php">
            <div class="form-group row">
              <!-- Category Selection Dropdown -->
              <label for="$category" class="col-sm-4 col-form-label text-right">Category to be renamed:</label>
              <div class="col-sm-8">
                <select class="form-control" id="$category" name="$category">
                  <option  value=0>
                    All categories
                  </option>
                    <?php
                    $find_categories_query = "SELECT * FROM categories";
                    // SQL to fetch data
                    $result = mysqli_query($connection, $find_categories_query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $category = $row['category'];
                        $categoryId = $row['cateId'];

                        echo "<option value='$categoryId|$category'> $category </option>";
                    }
                    ?>
                </select>
              </div>
              <!-- Category Selection Dropdown End-->
            </div>
            <div class="form-group row">
              <label for="newCategoryName" class="col-sm-4 col-form-label text-right">Rename to</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="newCategoryName" name="newCategoryName">
              </div>
              <input type="hidden" name="$actionType" value='EditCategory'>
            </div>
            <button type="submit" class="btn btn-primary form-control">Save changes</button>
            <br><br>
            <a href="admin_management.php" class="btn btn-secondary form-control">Discard changes</a>
          </form>
        </div>
      </div>
    </div>
  </div>


<?php
mysqli_close($connection);
include_once("footer.php")
?>