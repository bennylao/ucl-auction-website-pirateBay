<?php
include_once("header.php");
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>

    <div class="container">
        <div style="max-width: 800px; margin: 10px auto">
            <h2 class="my-3">Edit Condition</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="manage_condition_backend.php">
                        <div class="form-group row">
                            <!-- Condition Selection Dropdown -->
                            <label for="condition" class="col-sm-4 col-form-label text-right">Condition to be renamed:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="condition" name="condition">
                                    <?php
                                    $find_conditions_query = "SELECT * FROM conditions";
                                    // SQL to fetch data
                                    $result = mysqli_query($connection, $find_conditions_query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $condDescript = $row['condDescript'];
                                        echo "<option value='$condDescript'> $condDescript </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Condition Selection Dropdown End-->
                        </div>
                        <div class="form-group row">
                            <label for="newConditionName" class="col-sm-4 col-form-label text-right">Rename to</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="newConditionName" name="newConditionName">
                            </div>
                            <input type="hidden" name="actionType" value='EditCondition'>
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