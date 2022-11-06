<?php
require_once("../inc/header.php");
session_start();
if (isset($_POST["id"])) {
    $id = $_POST["id"];
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
    echo "<pre>";

    if (empty($productName)) {
        $errors[] = "product name is required";
    } elseif (strlen($productName) < 3) {
        $errors[] = "product name must be greater than 3 char";
    } elseif (strlen($productName) > 30) {
        $errors[] = "product name must be less than 30 char";
    }

    if ($productImage_tmp_error !== 0) {
        $errors[] = "image not upload please try again";
    } elseif (!in_array($productImage_extension, $extensions)) {
        $errors[] = "extension of your image not allowed";
    } elseif ($productImage_tmp_size >= 3000000) {
        $errors[] = "product image must be less than 3 MegaBytes";
    }
    echo $productName;

    if (!empty($errors)) {
        $_SESSION["error"] = $errors;
        header("location:../pages/updateProduct.php?id=$id");
    } else {
        unset($_SESSION["error"]);
        $selectDB = "SELECT * FROM `items` WHERE id = $id";
        $queryDB = mysqli_query($con, $selectDB);
        $result = mysqli_fetch_assoc($queryDB);
        $imageName = $result["image"];
        unlink("../upload/" . $imageName);

        $newName = uniqid("", true) . "." . $productImage_extension;
        $distention =   "../upload/" . $newName;
        move_uploaded_file($productImage_tmp_name, $distention);

        $insertDB = "UPDATE `items` SET `name`='$productName',`image`='$newName' WHERE `id` ='$id'";
        mysqli_query($con, $insertDB);

        $_SESSION["success"] = "product updated successfully";
        header("location:../pages/homePage.php");
    }
} else {

    header("location:../pages/homePage.php");
}
