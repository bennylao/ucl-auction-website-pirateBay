<?php

require_once "config_database.php";
require_once("utilities.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = $_POST['$actionType'];

}

if (isset($actionType) && $actionType == "CreateNewCategory") {
    $newCategory = $_POST['newCategory'];
    echo $newCategory;
}