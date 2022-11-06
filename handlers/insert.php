<?php
session_start();
require_once("../inc/header.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = htmlentities(htmlspecialchars($_POST["name"]));
    $productImage = $_FILES["image"];
    $productImage_name = $_FILES["image"]["name"];
    $productImage_type = $_FILES["image"]["type"];
    $productImage_tmp_name = $_FILES["image"]["tmp_name"];
    $productImage_tmp_size = $_FILES["image"]["size"];
    $productImage_tmp_error = $_FILES["image"]["error"];
    $productImage_extension = pathinfo($productImage_name)["extension"];
    $extensions = ["jpg", "png", "jpeg"];
    $errors = [];

    if (empty($productName)) {
        $errors[] = "product name is required";
    } elseif (strlen($productName) < 3) {
        $errors[] = "product name must be greater than 3 char";
    } elseif (strlen($productName) > 30) {
        $errors[] = "product name must be less than 30 char";
    }

    if ($productImage_tmp_error !== 0) {
        $errors[] = "image not upload pease try again";
    } elseif (!in_array($productImage_extension, $extensions)) {
        $errors[] = "extension of your image not allowed";
    } elseif ($productImage_tmp_size >= 3000000) {
        $errors[] = "product image must be less than 3 MegaBytes";
    }

    if (!empty($errors)) {
        $_SESSION["error"] = $errors;
        header("location:../pages/homePage.php");
    } else {
        unset($_SESSION["error"]);

        $createDB = "CREATE DATABASE IF NOT EXISTS `products` CHARACTER SET utf8";
        mysqli_query($con, $createDB);


        $tableDB = "CREATE TABLE IF NOT EXISTS items (
            id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
            name VARCHAR(30) NOT NULL,
            image VARCHAR(200) NOT NULL
        )";
        mysqli_query($con, $tableDB);


        $newName = uniqid("", true) . "." . $productImage_extension;
        $distention =  "../upload/" . $newName;

        move_uploaded_file($productImage_tmp_name, $distention);
        $insertDB = "INSERT INTO `items` (`name`,`image`)  VALUES('$productName' , '$newName') ";
        mysqli_query($con, $insertDB);


        $_SESSION["success"] = "image uploaded successfully";


        header("location:../pages/homePage.php");
    }
} else {
    header("location:../pages/homePage.php");
}
