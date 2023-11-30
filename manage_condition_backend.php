<?php
include_once("header.php");
require_once "config_database.php";
require_once("utilities.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['actionType'];
    $connection = connect_to_database() or die('Error connecting to MySQL server.' . mysqli_connect_error());

    if (isset($actionType) && $actionType == "CreateNewCondition") {
        $newCondition = $_POST['newCondition'];
        $existingNameQuery = "SELECT * FROM conditions WHERE condDescript = '$newCondition';";
        $existingCondition = mysqli_query($connection, $existingNameQuery);
        if (mysqli_num_rows($existingCondition) > 0) {
            mysqli_close($connection);
            echo "<div style='text-align: center;'><br><h5>'$newCondition' already exists. Please use other name.";
            echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
        } else {
            $query = "INSERT INTO conditions (condDescript)
                VALUES ('$newCondition');";
            $result = mysqli_query($connection, $query);
            mysqli_close($connection);
            if ($result) {
                echo "<div style='text-align: center;'><br><h5>New condition named '$newCondition' is created</div>";
                header("refresh:3;admin_management.php");
            } else {
                echo "<div style='text-align: center;'><br><h5>Failed to create the new condition";
                echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
            }
        }
    }

    if (isset($actionType) && $actionType == "EditCondition") {
        $newConditionName = $_POST['newConditionName'];
        $oldConditionName = $_POST['condition'];

        $existingNameQuery = "SELECT * FROM conditions WHERE condDescript = '$newConditionName';";
        $existingCondition = mysqli_query($connection, $existingNameQuery);
        if (mysqli_num_rows($existingCondition) > 0) {
            mysqli_close($connection);
            echo "<div style='text-align: center;'><br><h5>'$newConditionName' already exists. Please use other name.";
            echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
        } else {
            $query = "UPDATE conditions
                    SET condDescript = '$newConditionName'
                    WHERE condDescript = '$oldConditionName';";

            $result = mysqli_query($connection, $query);
            mysqli_close($connection);
            if ($result) {
                echo "<div style='text-align: center;'><br><h5>'$oldConditionName' has been renamed to '$newConditionName'</div>";
                header("refresh:3;admin_management.php");
            } else {
                echo "<div style='text-align: center;'><br><h5>Failed to rename '$oldConditionName'";
                echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
            }
        }
    }

    if (isset($actionType) && $actionType == "DeleteCondition") {
        $conditionInfo = $_POST['conditionInfo'];
        $conditionInfoArray = explode('|', $conditionInfo);
        $conditionId = $conditionInfoArray[0];
        $conditionDescript = $conditionInfoArray[1];

        $existingItemQuery = "SELECT * FROM items WHERE conditions = $conditionId;";
        $existingCondition = mysqli_query($connection, $existingItemQuery);
        if (mysqli_num_rows($existingCondition) > 0) {
            mysqli_close($connection);

            echo "<div style='text-align: center;'><br><h5>Some auction items are classified as '$conditionDescript', Please update their condition before deleting</h5>";
            echo "<br><br><a href='admin_management.php' class='btn btn-outline-info'>Return</a></div>";
        } else {
            $query = "DELETE FROM conditions WHERE conditionId = $conditionId;";
            $result = mysqli_query($connection, $query);
            mysqli_close($connection);
            echo "<div style='text-align: center;'><br><h5>Condition '$conditionDescript' has been deleted.</div>";
            header("refresh:3;admin_management.php");
        }
    }
}
