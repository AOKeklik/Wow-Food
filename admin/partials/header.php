<?php 
    require_once("../load.php"); 
    if (!isset($_SESSION["userLoggedIn"]))
        header ("Location: login");

    $userLoggedIn = $_SESSION["userLoggedIn"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="<?php echo Constants::$ROOT_URL?>css/admin.css">
</head>
<body>

<!-- menu -->
    <div class="menu">
        <div class="wrapper">
            <div class="flex gap-1 justify-between align-center">
                <ul class="flex gap-1 text-sky-400">
                    <li><a href="<?php echo Constants::$ROOT_URL?>admin/dashboard">Home</a></li>
                    <li><a href="<?php echo Constants::$ROOT_URL?>admin/manage">Admin</a></li>
                    <li><a href="<?php echo Constants::$ROOT_URL?>admin/category">Category</a></li>
                    <li><a href="<?php echo Constants::$ROOT_URL?>admin/food">Food</a></li>
                    <li><a href="<?php echo Constants::$ROOT_URL?>admin/order">Order</a></li>
                </ul>
                <a href="<?php echo Constants::$ROOT_URL?>admin/logout">
                    <i class="bi bi-box-arrow-right text-2 text-red-400"></i>   
                </a>
            </div>
        </div>
    </div>