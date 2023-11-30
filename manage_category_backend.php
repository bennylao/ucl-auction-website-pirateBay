<?php
include_once("header.php");
require_once "config_database.php";
require_once("utilities.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['actionType'];
    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

    if (isset($actionType) && $actionType == "CreateNewCategory") {
        $newCategory = $_POST['newCategory'];
        $existingNameQuery = "SELECT * FROM categories WHERE category = '$newCategory';";
        $existingCategory = mysqli_query($connection, $existingNameQuery);
        if (mysqli_num_rows($existingCategory) > 0) {
            mysqli_close($connection);
            echo "<div style='text-align: center;'><br><h5>'$newCategory' already exists. Please use other name.";
            echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
        } else {
            $query = "INSERT INTO categories (category)
                VALUES ('$newCategory');";
            $result = mysqli_query($connection, $query);
            mysqli_close($connection);
            if ($result) {
                echo "<div style='text-align: center;'><br><h5>New category named '$newCategory' is created</div>";
                header("refresh:3;admin_management.php");
            } else {
                echo "<div style='text-align: center;'><br><h5>Failed to create the new category";
                echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
            }
        }
    }

    if (isset($actionType) && $actionType == "EditCategory") {
        $newCategoryName = $_POST['newCategoryName'];
        $oldCategoryName = $_POST['category'];

        $existingNameQuery = "SELECT * FROM categories WHERE category = '$newCategoryName';";
        $existingCategory = mysqli_query($connection, $existingNameQuery);
        if (mysqli_num_rows($existingCategory) > 0) {
            mysqli_close($connection);
            echo "<div style='text-align: center;'><br><h5>'$newCategoryName' already exists. Please use other name.";
            echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
        } else {
            $query = "UPDATE categories
                    SET category = '$newCategoryName'
                    WHERE category = '$oldCategoryName';";

            $result = mysqli_query($connection, $query);
            mysqli_close($connection);
            if ($result) {
                echo "<div style='text-align: center;'><br><h5>'$oldCategoryName' has been renamed to '$newCategoryName'</div>";
                header("refresh:3;admin_management.php");
            } else {
                echo "<div style='text-align: center;'><br><h5>Failed to rename '$oldCategoryName'";
                echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
            }
        }
    }

    if (isset($actionType) && $actionType == "DeleteCategory") {
        $categoryInfo = $_POST['categoryInfo'];
        $categoryInfoArray = explode('|', $categoryInfo);
        $categoryId = $categoryInfoArray[0];
        $categoryName = $categoryInfoArray[1];

        $existingItemQuery = "SELECT * FROM items WHERE category = $categoryId;";
        $existingCategory = mysqli_query($connection, $existingItemQuery);
        if (mysqli_num_rows($existingCategory) > 0) {
            mysqli_close($connection);

            echo "<div style='text-align: center;'><br><h5>Some auction items are categorised as '$categoryName', Please update their category before deleting</h5>";
            echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
        } else {
            $query = "DELETE FROM categories WHERE cateId = $categoryId;";
            $result = mysqli_query($connection, $query);
            mysqli_close($connection);
            echo "<div style='text-align: center;'><br><h5>Category '$categoryName' has been deleted.</div>";
            header("refresh:3;admin_management.php");
        }
    }
}
