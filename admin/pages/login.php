<?php 
require_once ("../load.php");

if (isset($_SESSION["userLoggedIn"]))
    header("Location: ".Constants::$ROOT_URL."admin/manage");

$message = "";

if (isset ($_POST["login-submit"])) {
    $data = [
        "username" => $_POST["username"],
        "password" => $_POST["password"],
    ];

    $user = $set->login($data);
    
    if (!$user) {
        $message = '<p class="message active">'.$set->getError ().'</p>';
    } else {
        $_SESSION["userLoggedIn"] = $user->username();
        header("Location: ".Constants::$ROOT_URL."admin/manage");
    }
}

$username = isset ($_POST["username"]) ? $_POST["username"] : "";
$password = isset ($_POST["password"]) ? $_POST["password"] : "";

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
        <h2 class="mb-2">Login</h2>
        <?php echo $message?>
        <form action="" method="post" class="form">
            <label for="username">
                <span>Username</span>
                <input type="text" name="username" id="username" value="<?php echo $username?>">
            </label>
            <label for="password">
                <span>Password</span>
                <input type="text" name="password" id="password">
            </label>
            <button type="submit" name="login-submit" class="btn btn-sky self-center">
                <span>Login</span>
            </button>
        </form>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo Constants::$ROOT_URL?>js/main.js"></script>
</body>
</html>