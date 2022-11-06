<?php session_start();
require_once("../inc/header.php");

$selectDB = "SELECT * FROM `items` WHERE 1";
$dbQuery = mysqli_query($con, $selectDB);

while ($result = mysqli_fetch_assoc($dbQuery)) {
    $data[] = $result;
}
?>
<div class="container mt-10">

    <div class="row">
        <div class="input-group input-group-lg mt-5">
            <form action="../handlers/insert.php" method="POST" enctype="multipart/form-data">
                <?php if (isset($_SESSION["error"])) : ?>
                    <?php foreach ($_SESSION["error"] as $error) : ?>
                        <div class="alert alert-primary" role="alert"> <?php echo $error; ?> </div>
                    <?php unset($_SESSION["error"]);
                    endforeach; ?>
                <?php endif; ?>

                <?php if (isset($_SESSION["success"])) : ?>
                    <div class="alert alert-primary" role="alert"> <?php echo $_SESSION["success"]; ?> </div>
                <?php unset($_SESSION["success"]);
                endif; ?>
                <div class="alert alert-primary" role="alert">
                    <input type="text" name="name" class=" form-control" placeholder="name of product"><br />
                    <input type="file" name="image" class=" form-control">
                    <div class="col-12 ml-10 mt-3 auto">
                        <button type="submit" class="btn btn-primary col-12 p-3">Add</button>
                    </div>
            </form>



        </div>
        <table class="table ">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">task</th>
                    <th scope="col">image</th>
                    <th scope="col">control</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)) :  foreach ($data as $products) : ?>
                        <tr>
                            <th scope="row"><?php echo $products["id"]; ?></th>
                            <td><?php echo $products["name"]; ?></td>
                            <td><img src=<?php echo "../upload/" . $products["image"]; ?> width="50px" height="50px" alt="product" /></td>
                            <td><a href="../handlers/delete.php?id=<?php echo $products["id"]; ?>">
                                    <button type="submit" class="btn btn-primary ">delete</button>
                                </a>
                                <a href="./updateProduct.php?id=<?php echo $products["id"]; ?>">
                                    <button type="submit" class="btn btn-primary ">update</button>
                                </a>
                            </td>
                        </tr>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>
    </div>
</div>


<?php require_once("../inc/footer.php"); ?>