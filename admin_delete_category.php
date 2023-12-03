<?php
include_once("header.php");
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>

<!--page for admin to remove category-->
    <div class="container">
        <div style="max-width: 800px; margin: 10px auto">
            <h2 class="my-3">Remove Category</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="manage_category_backend.php">
                        <div class="form-group row">
                            <!-- Category Selection Dropdown -->
                            <label for="categoryInfo" class="col-sm-4 col-form-label text-right">Category to be deleted:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="categoryInfo" name="categoryInfo">
                                    <?php
                                    $find_categories_query = "SELECT * FROM categories";
                                    // SQL to fetch data
                                    $result = mysqli_query($connection, $find_categories_query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $categoryId = $row['cateId'];
                                        $categoryName = $row['category'];
                                        echo "<option value='$categoryId|$categoryName'> $categoryName </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Category Selection Dropdown End-->
                        </div>
                        <input type="hidden" name="actionType" value='DeleteCategory'>
                        <button type="submit" class="btn btn-danger form-control">Delete Category</button>
                        <br><br>
                        <a href="admin_management.php" class="btn btn-secondary form-control">Return</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php
mysqli_close($connection);
include_once("footer.php")
?>