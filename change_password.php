<?php
include_once("header.php");
require_once("utilities.php");
require_once ("config_database.php");
?>

<!--page for all user to change their password-->
<div class="container">
    <div style="max-width: 800px; margin: 10px auto">
        <h2 class="my-3">Change password</h2>
        <div class="card">
            <div class="card-body">
                <form method="post" action="change_password_backend.php">
                    <div class="form-group row">
                        <label for="currentPw" class="col-sm-2 col-form-label text-right">Current password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="currentPw" name="currentPw">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="newPw" class="col-sm-2 col-form-label text-right">New password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="newPw" name="newPw">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirmPw" class="col-sm-2 col-form-label text-right">Confirm new password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="confirmPw" name="confirmPw">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary form-control">Save changes</button><br><br>
                    <a href="user_homepage.php" class="btn btn-secondary form-control">Discard changes</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once("footer.php") ?>
