<?php
require_once ("../admin/load.php");
$url = Constants::$ROOT_URL."admin/";
try {

    if (isset($_POST["exist-user"])) {
        if ($set->getUserByUsername($_POST["username"])) {
            echo "true";
            return;
        }
    }
    
    if (isset($_POST["add-user"])) {

        if ($set->getUserByUsername($_POST["username"])) {
            echo "exist user!";
            return;
        }

        if (!$userId=$set->createUser([
            "full_name" => $_POST["name"],
            "username" => $_POST["username"],
            "password" => $_POST["password"]
        ])) {
            echo "unable to create user!";
            return;
        }

        $user = new User ($pdo, $userId);
        $response = [];
        $response["status"] = "success";
        $response["message"] = "Successfully created!";
        $response["data"] = <<<HTML
            <tr>
                <td>{$user->id()}</td>
                <td>{$user->name()}</td>
                <td>{$user->username()}</td>
                <td>
                    <div class="nav-update">
                        <i class="nav-update-toggle bi bi-three-dots pointer text-3"></i>
                        <div class="nav-update-items">
                            <div onclick="deleteUser (event, {$user->id()})" class="nav-update-item">
                                <i class="bi bi-trash text-2 text-red-400"></i>
                                <span>Delete</span>
                            </div>
                            <a class="nav-update-item" href="{$url}userupdate/{$user->id()}">
                                <i class="bi bi-pencil-square text-2 text-red-400"></i>
                                <span>Update</span>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        HTML;
        echo json_encode($response);
        exit;
    }
    
    if (isset($_POST["delete-user"])) {
        $response = [];

        if (!$set->deleteUser ([ "id" => $_POST["userId"]])) {
            $response["status"] = "error";
            $response["message"] = "Fail deleting user!";
        }

        $response["status"] = "success";
        $response["message"] = "Successfully deleted.";
        $response["redirect"] = "{$url}/logout";
        
        echo json_encode($response);
        exit;
    }
    
} catch (ErrorException $err) {
    echo "user-add: ".$err->getMessage();
}

?>