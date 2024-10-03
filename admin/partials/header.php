<?php require_once("../functions.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo Constants::$ROOT_URL?>css/admin.css">
</head>
<body>

<!-- menu -->
    <div class="menu">
        <div class="wrapper">
            <ul class="flex justify-end gap-1 text-red-400">
                <li><a href="<?php echo Constants::$ROOT_URL?>admin/dashboard">Home</a></li>
                <li><a href="<?php echo Constants::$ROOT_URL?>admin/manage">Admin</a></li>
                <li><a href="<?php echo Constants::$ROOT_URL?>admin/category">Category</a></li>
                <li><a href="<?php echo Constants::$ROOT_URL?>admin/food">Food</a></li>
                <li><a href="<?php echo Constants::$ROOT_URL?>admin/order">Order</a></li>
            </ul>
        </div>
    </div>