<?php 
require_once ("../load.php");

if (!isset($_SESSION["userLoggedIn"]))
    header("Location: /Wow-Food/admin/manage");

if (!isset($_GET["userId"]))
    header("Location: /Wow-Food/admin/manage");

$user = new User ($pdo, $_GET["userId"]);
$message = "";

if (isset ($_POST["login-submit"])) {
    $data = [
        "full_name" => $_POST["full_name"],
        "username" => $_POST["username"],
        "currentpassword" => $_POST["currentpassword"],
        "password" => $_POST["password"],
        "confirmpassword" => $_POST["confirmpassword"],
    ];
    
    if (!$set->updateUser($data, $user->id())) 
        $message = '<p class="message active">'.$set->getError ().'</p>';
    else {
        $_SESSION["message"]["update"] = '<p class="message message-info active">User successfully updated!</p>';
        header("Location: /Wow-Food/admin/manage");
    }
        
}

$name = isset ($_POST["full_name"]) ? $_POST["full_name"] : $user->name();
$username = isset ($_POST["username"]) ? $_POST["username"] : $user->username();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo Constants::$ROOT_URL?>css/admin.css">
</head>
<body>

    <div class="wrapper">
        <h2 class="mb-2">Update User</h2>
        <?php echo $message?>
        <form action="" method="post" class="form">
            <label for="full_name">
                <span>Name</span>
                <input type="text" name="full_name" id="full_name" value="<?php echo $name?>">
            </label>
            <label for="username">
                <span>Username</span>
                <input type="text" name="username" id="username" value="<?php echo $username?>">
            </label>
            <label for="currentpassword">
                <span>Current Password</span>
                <input type="text" name="currentpassword" id="currentpassword">
            </label>
            <label for="password">
                <span>Password</span>
                <input type="text" name="password" id="password">
            </label>
            <label for="confirmpassword">
                <span>Confirm Password</span>
                <input type="text" name="confirmpassword" id="confirmpassword">
            </label>
            <button type="submit" name="login-submit" class="btn btn-sky self-center">
                <span>Update</span>
            </button>
        </form>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo Constants::$ROOT_URL?>js/main.js"></script>
</body>
</html>