<?php
include_once("header.php");
?>

<!--page for admin to add new condition-->
    <div class="container">
        <div style="max-width: 800px; margin: 10px auto">
            <h2 class="my-3">Add Condition</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="manage_condition_backend.php">
                        <div class="form-group row">
                            <label for="newCondition" class="col-sm-4 col-form-label text-right">New Condition</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="newCondition" name="newCondition">
                            </div>
                            <input type="hidden" name="actionType" value ='CreateNewCondition'>
                        </div>
                        <button type="submit" class="btn btn-primary form-control">Save changes</button><br><br>
                        <a href="admin_management.php" class="btn btn-secondary form-control">Discard changes</a>
                    </form>
                </div>
            </div>
        </div>
    </div>



<?php include_once("footer.php") ?>