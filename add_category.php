<?php
include_once("header.php");
?>

    <div class="container">
        <div style="max-width: 800px; margin: 10px auto">
            <h2 class="my-3">Add Category</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="manage_category_backend.php">
                        <div class="form-group row">
                            <label for="lastName" class="col-sm-2 col-form-label text-right">New Category</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="newCategory" name="newCategory">
                            </div>
                            <input type="hidden" name="$actionType" value = 'CreateNewCategory'>
                        </div>
                        <button type="submit" class="btn btn-primary form-control">Save changes</button><br><br>
                        <a href="admin_management.php" class="btn btn-secondary form-control">Discard changes</a>
                    </form>
                </div>
            </div>
        </div>
    </div>



<?php include_once("footer.php") ?>