<?php
require_once ("../admin/load.php");
$url = Constants::$ROOT_URL;

try {
    if (isset($_POST["exist-category"])) {
        if ($set->getCategoryByTitle($_POST["title"]))
            $set->sendResponse ("error","already exist!");
    }

    if (isset($_POST["add-category"])) {

        if ($set->getCategoryByTitle($_POST["title"]))
            throw new Exception("Title must be unique!");

        if (0 < $_FILES["file"]["error"]) 
            throw new Exception("File upload error: ".$_FILES["file"]["error"]);
        else {
            $rootDirectory = $_SERVER["DOCUMENT_ROOT"]."/Wow-Food/";
            $pathDirectory = $rootDirectory."images/category/";

            if (!file_exists($pathDirectory) && !is_dir($pathDirectory))
                mkdir ($pathDirectory, 0777, true);

            $filename = "images/category/cat_".time().basename($_POST["fileName"]);

            if (file_exists( $rootDirectory.$filename))
                unlink($rootDirectory.$filename);
            
            move_uploaded_file ($_FILES["file"]["tmp_name"], $rootDirectory.$filename);
        } 

        if (!$categoryId=$set->create("tbl_category", [
            "title" => $_POST["title"],
            "image_name" => $filename,
            "featured" => $_POST["featured"],
            "active" => $_POST["active"],
        ])) throw new Exception("Failed to create record in category");

        $category = new Category ($pdo, $categoryId);

        $set->sendResponse (
            "success",
            "Successfully created.",
            ["data" => <<<HTML
                <tr>
                    <td>{$category->id()}</td>
                    <td>{$category->title()}</td>
                    <td class="text-center"><img src="{$url}{$category->img()}" alt="" class="h-7 w-100% obj-cover" /></td>
                    <td>{$category->featured()}</td>
                    <td>{$category->active()}</td>
                    <td>
                        <form class="nav-update" action="{$url}admin/categoryupdate" method="post">
                            <i class="nav-update-toggle bi bi-three-dots pointer text-3"></i>
                            <div class="nav-update-items">
                                <div onclick="deleteCategory (event, {$category->id()})" class="nav-update-item">
                                    <i class="bi bi-trash text-2 text-red-400"></i>
                                    <span>Delete</span>
                                </div>
                                <button type="submit" class="nav-update-item">
                                    <i class="bi bi-pencil-square text-2 text-red-400"></i>
                                    <span>Update</span>
                                </button>
                            </div>
                            <input type="hidden" name="categoryId" value="{$category->id()}">
                        </form>
                    </td>
                </tr>
            HTML]);
    }

    if (isset($_POST["delete-category"])) {
        
        $category = new Category ($pdo, $_POST["categoryId"]);  
        $img = $_SERVER["DOCUMENT_ROOT"]."/Wow-Food/".$category->img();

        if (file_exists($img)) unlink ($img);

        if (!$set->delete ("tbl_category", [ "id" => $category->id()])) return;

            
        $set->sendResponse ("success","Successfully deleted.");
    }

} catch (PDOException $err) {
    echo "category: ".$err->getMessage();
}

