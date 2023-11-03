<?php
include_once("header.php");
require_once 'includes/signup_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <title>Register and Login</title>
</head>
<body>

<div class="container">
    <h2 class="my-3">Register new account</h2>
    <form action="../process_registration.php" method="post">
        <!-- Select Account Type -->
        <div class="form-group row">
            <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
                    <label class="form-check-label" for="accountBuyer">Buyer</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
                    <label class="form-check-label" for="accountSeller">Seller</label>
                </div>
                <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>

        <!-- First Name -->
        <div class="form-group row">
            <label for="firstname" class="col-sm-2 col-form-label text-right">First Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name">
                <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>

        <!-- Last Name -->
        <div class="form-group row">
            <label for="lastname" class="col-sm-2 col-form-label text-right">Last Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name">
                <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>

        <!-- Email -->
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>

        <!-- Username -->
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>

        <div class="form-group row">
            <label for="address" class="col-sm-2 col-form-label text-right">Address</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="address" id="address" placeholder="Address">
                <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>

        <!-- Password -->
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>

        <div class="form-group row">
            <button type="submit" class="btn btn-primary form-control">Register</button>
        </div>
    </form>

    <div class="text-center mt-4">
        Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>
    </div>
</div>

<?php include_once("footer.php"); ?>

<script src="bootstrap/jquery.min.js"></script>
<script src="bootstrap/bootstrap.min.js"></script>
</body>
</html>
