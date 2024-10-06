<?php 
require_once ("../load.php");

if (!isset($_SESSION["userLoggedIn"])) {
    header("Location: /Wow-Food/admin/category");
    die ();
}

$category = new Category ($pdo, $_POST["categoryId"]);
$message = "";

if (!isset($_POST["categoryId"]) || !$category->isValid()) {
    $_SESSION["message"]["update"] = '<p class="message active">Category not found!</p>';
    header("Location: ".Constants::$ROOT_URL."admin/category");
    die ();
}



$title = isset ($_POST["title"]) ? $_POST["title"] : $category->title();
$fileName = $category->img();
$featured = $category->featured() == 1 ? "checked" : "";
$active = $category->active() == 1 ? "checked" : "";


if (isset ($_POST["category-submit"])) {

    if (isset ($_FILES["image_name"]) && $_FILES["image_name"]["name"]) {
        $fileName = "images/category/cat_".time().".".pathinfo($_FILES["image_name"]["name"], PATHINFO_EXTENSION);

        $root = $_SERVER["DOCUMENT_ROOT"]."/Wow-Food/";

        $sourcePath = $_FILES["image_name"]["tmp_name"];
        $destinationPath = $root.$fileName;

        $existFile = $root.$category->img();
    
        if (file_exists($existFile)) {
            unlink($existFile);
        }
    
        move_uploaded_file($sourcePath, $destinationPath);
    }

    if (isset($_POST["featured"])) {
        $featured = "checked";
    } else {
        $featured = "";
    } 
    
    if (isset($_POST["active"])) {
        $active = "checked";
    } else {
        $active = "";
    } 

    $data = [
        "title" => $_POST["title"],
        "image_name" => $fileName,
        "featured" => $featured == "checked" ? 1 : 0,
        "active" => $active == "checked" ? 1 : 0,
    ];

    // print_r($data);
    
    if (!$set->updateCategory($data, $category->id())) 
        $message = '<p class="message active">'.$set->getError ().'</p>';
    else {
        $_SESSION["message"]["update"] = '<p class="message message-info active">Category successfully updated!</p>';
        header("Location: ".Constants::$ROOT_URL."admin/category");
        die ();
    }   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Update</title>
    <link rel="stylesheet" href="<?php echo Constants::$ROOT_URL?>css/admin.css">
</head>
<body>

    <div class="wrapper">
        <h2 class="mb-2">Update Category</h2>
        <?php echo $message?>
        <form action="" method="post" class="form" enctype="multipart/form-data">
            <label for="username">
                <span>Title</span>
                <input type="text" name="title" id="title" value="<?php echo $title?>">
            </label>
            <label for="image_name" class="text-center">
                <img src="<?php echo Constants::$ROOT_URL.$fileName?>" alt="" class="wh-20 obj-cover">
                <input type="file" name="image_name" id="image_name" class="none">
            </label>          
            <label for="featured" role="checkbox">
                <span>Featured</span>
                <input type="checkbox" name="featured" id="featured" <?php echo $featured?>>
                <i></i>
            </label>
            <label for="active" role="checkbox">
                <span>Active</span>
                <input type="checkbox" name="active" id="active" <?php echo $active?>>
                <i></i>
            </label>
            <button type="submit" name="category-submit" class="btn btn-sky self-center">
                <span>Update</span>
            </button>
            <input type="hidden" name="categoryId" value="<?php echo $category->id()?>">
        </form>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo Constants::$ROOT_URL?>js/main.js"></script>
</body>
</html>