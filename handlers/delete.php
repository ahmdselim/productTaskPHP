<?php
require_once("../inc/header.php");
session_start();
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $selectDB = "SELECT * FROM `items` WHERE id = '$id'";
    $queryDB = mysqli_query($con, $selectDB);
    $result = mysqli_fetch_row($queryDB);
    $imageName = $result[2];

    $deleteDB = "DELETE  FROM `items` WHERE id = '$id'";
    mysqli_query($con, $deleteDB);

    unlink("../upload/$imageName");
    $_SESSION["success"] = "deleted success";
    header("location:../pages/homePage.php");
} else {
    header("location:../pages/homePage.php");
}
