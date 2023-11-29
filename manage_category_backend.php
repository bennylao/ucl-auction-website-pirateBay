<?php

require_once "config_database.php";
require_once("utilities.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['$actionType'];
    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

    if (isset($actionType) && $actionType == "CreateNewCategory") {
        $newCategory = $_POST['newCategory'];

        $query = "INSERT INTO categories (category)
                VALUES ('$newCategory');";
        $result = mysqli_query($connection, $query);
        mysqli_close($connection);
        if($result){
            echo "New category named '$newCategory' is created";
            header("refresh:2;admin_management.php");
        }else{
            echo "Failed to create the new category";
            header("refresh:2;admin_management.php");
        }
    }

    if (isset($actionType) && $actionType == "EditCategory") {
        $newCategoryName = $_POST['newCategoryName'];
        $oldCategoryName = $_POST['$category'];

        $query = "UPDATE categories
                    SET category = '$newCategoryName'
                    WHERE category = '$oldCategoryName';";

        $result = mysqli_query($connection, $query);
        mysqli_close($connection);
        if($result){
            echo "'$oldCategoryName' has been renamed to '$newCategoryName'";
            header("refresh:2;admin_management.php");
        }else{
            echo "Failed to rename '$oldCategoryName'";
            header("refresh:2;admin_management.php");
        }
    }
}
