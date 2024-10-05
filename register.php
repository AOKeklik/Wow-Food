<?php require_once ("../load.php")?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?php echo Constants::$ROOT_URL?>css/admin.css">
</head>
<body>

    <div class="wrapper">
        <h2 class="mb-2">Register</h2>
        <p class="message"></p>
        <form action="" method="post" class="form">
            <label for="name">
                <span>Name</span>
                <input type="text" name="name" id="name">
            </label>
            <label for="username">
                <span>Username</span>
                <input type="text" name="username" id="username">
            </label>
            <label for="password">
                <span>Password</span>
                <input type="text" name="password" id="password">
            </label>
            <label for="confirmpassword">
                <span>Confirm Password</span>
                <input type="text" name="confirmpassword" id="confirmpassword">
            </label>
            <p class="btn btn-sky self-center">
                <span>Register</span>
            </p>
            <p class="">Already a member? <a class="bold hover:text-red-400 tr-3s tr-color" href="/Wow-Food/admin/login">Log in</a> here.</p>
        </form>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo Constants::$ROOT_URL?>js/main.js"></script>
</body>
</html>