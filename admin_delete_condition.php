<?php
include_once("header.php");
$connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());
?>

<!--page for admin to remove condition-->
    <div class="container">
        <div style="max-width: 800px; margin: 10px auto">
            <h2 class="my-3">Remove Condition</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="manage_condition_backend.php">
                        <div class="form-group row">
                            <!-- Condition Selection Dropdown -->
                            <label for="conditionInfo" class="col-sm-4 col-form-label text-right">Condition to be deleted:</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="conditionInfo" name="conditionInfo">
                                    <?php
                                    $find_conditions_query = "SELECT * FROM conditions";
                                    // SQL to fetch data
                                    $result = mysqli_query($connection, $find_conditions_query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $conditionId = $row['conditionId'];
                                        $condDescript = $row['condDescript'];
                                        echo "<option value='$conditionId|$condDescript'> $condDescript </option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Condition Selection Dropdown End-->
                        </div>
                        <input type="hidden" name="actionType" value='DeleteCondition'>
                        <button type="submit" class="btn btn-danger form-control">Delete Condition</button>
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