<?php

include_once("header.php");
require_once("utilities.php");
?>

<div class="container">
    <div style="max-width: 800px; margin: 10px auto">
        <h2 class="my-3">Edit Profile</h2>
        <div class="card">
            <div class="card-body">
                <form method="post" action="edit_profile_backend.php">
                    <div class="form-group row">
                        <label for="firstName" class="col-sm-2 col-form-label text-right">First name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="firstName" name="firstName">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lastName" class="col-sm-2 col-form-label text-right">Last name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="lastName" name="lastName">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label text-right">Address</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="address" name="address" rows="4"></textarea>
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