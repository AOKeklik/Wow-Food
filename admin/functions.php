<?php 

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

class Constants {
    static $ROOT_URL="http://localhost/Wow-Food/";
}
class Functions {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    /* USERS */
    

    /* FORMS */
    public function formSanitizer ($input) {
        $input = trim ($input);
        $input = stripslashes ($input);
        $input = htmlspecialchars ($input);
        return $input;
    }
}






$set = new Functions ($pdo);


?>
