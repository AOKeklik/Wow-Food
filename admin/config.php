<?php

ob_start();
session_start();
date_default_timezone_set("Europe/Warsaw");


try {
    $host = "localhost";
    $database = "wowfood";
    $username = "root";
    $password = "";

    $pdo = new PDO ("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    die ("Connection: ".$err->getMessage());
}


?>