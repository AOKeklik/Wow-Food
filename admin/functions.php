<?php 
class User {
    private $pdo,$data;
    public function __construct($pdo, $input) {
        $this->pdo = $pdo;
        if (is_array($input)) {
            $this->data = $input;
        } else {
            try {
                $sql = "select * from tbl_admin where id=:id";
                $stmt = $pdo->prepare ($sql);
                $stmt->bindValue (":id", $input);
                $stmt->execute ();
                $this->data = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $err) {
                echo "User: ".$err->getMessage();
            }
        }
    }
    public function id () {
        return $this->data["id"];
    }
    public function name () {
        return $this->data["full_name"];
    }
    public function username () {
        return $this->data["username"];
    }
    public function pass () {
        return $this->data["password"];
    }
    public function role () {
        return $this->data["role"];
    }
}
class Functions {
    private $pdo,$errors=[];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /* USERS */
    public function getAllUser () {
        try {
            $sql = "select * from tbl_admin";
            $stmt = $this->pdo->prepare ($sql);
            $stmt->execute ();
            
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $results[] = new User ($this->pdo, $row);
            }
            
            return $results;
        } catch (PDOException $err) {
            echo "User: ".$err->getMessage();
        }
    }
    public function createUser ($data) {
        try {
            $data = $this->formSanitizer($data);
            $data = $this->hashPassword($data);
            $sql = "insert into tbl_admin (".implode(", ", array_keys($data)).")";
            $sql .= "values(:".implode(", :", array_keys($data)).")";
            
            $stmt = $this->pdo->prepare($sql);

            foreach ($data as $key => $val) {
                $stmt->bindValue (":$key", $val);
            }

            $stmt->execute();

            return $this->pdo->lastInsertId();
        } catch (PDOException $err) {
            return "createUser: ".$err->getMessage ();
        }
    }
    public function updateUser ($data, $userId) {
        try {
            $data = $this->formSanitizer($data);
            $this->validateRequireFields($data);
            $data = $this->validateConfirmPassword($data);
            $data = $this->validateCurrentPassword($data);

            if (empty ($this->errors)) {
                $data = $this->hashPassword($data);

                $sql = "update tbl_admin set ";

                foreach ($data as $key => $val) {
                    if (array_key_last($data) == $key)
                        $sql .= "$key=:$key ";
                    else
                        $sql .= "$key=:$key,";
                }

                $sql .= "where id=:id";
                
                $stmt = $this->pdo->prepare($sql);

                foreach ($data as $key => $val) {
                    $stmt->bindValue (":$key", $val);
                }

                $stmt->bindValue (":id", $userId);
                
                return $stmt->execute();
            }

            return false;
        } catch (PDOException $err) {
            return "createUser: ".$err->getMessage ();
        }
    }
    public function deleteUser ($data) {
        try {
            $data = $this->formSanitizer($data);

            $sql = "delete from tbl_admin where id=:id";
            $stmt = $this->pdo->prepare ($sql);
            
            foreach ($data as $key => $val) {
                $stmt->bindValue (":$key", $val);
            }

            return $stmt->execute ();
        } catch (PDOException $err) {
            echo "User: ".$err->getMessage();
        }
    }
    public function getUserByUsername ($username) {
        try {
            $sql = "select * from tbl_admin where username=:username";
            $stmt = $this->pdo->prepare ($sql);
            $stmt->bindValue(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? new User ($this->pdo, $user) : false;
        } catch (PDOException $err) {
            echo "User: ".$err->getMessage();
        }
    }
    public function login ($data) {
        try {
            $data = $this->formSanitizer($data);
            $this->validateRequireFields($data);
            $this->validateExistUser($data);

            if (empty ($this->errors)) {
                $data = $this->hashPassword($data);
                
                $sql = "select * from tbl_admin where username=:username and password=:password";
                $stmt = $this->pdo->prepare ($sql);

                foreach ($data as $key => $val) {
                    $stmt->bindValue (":$key", $val);
                }

                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                return new User ($this->pdo, $user);
            }   

            return false;

        } catch (ErrorException $err) {
            echo "login: ".$err->getMessage();
        }
    }

    /* HELPERS */
    public function getError () {
        return $this->errors[0];
    }
    public function isAdmin () {
        if (isset ($_SESSION["userLoggedIn"])) {
            if ($user = $this->getUserByUsername($_SESSION["userLoggedIn"])) {
                if ($user->role() == "admin") {
                    return true;
                }
            }

        }

        return false;
    }

    /* FORMS */
    private function formSanitizer ($inputs) {
        $sanitizedInputs = [];

        foreach ($inputs as $key=>$val) {
            $input = trim ($val);
            $input = stripslashes ($input);
            $input = htmlspecialchars ($input);

            $sanitizedInputs[$key] = $input;
        }

        return $sanitizedInputs;
    }
    private function hashPassword ($inputs) {
        if (isset($inputs["password"]) && !empty($inputs["password"])) {
            $hashedPass = hash("sha512", $inputs["password"]);
            $inputs["password"] = $hashedPass;
        }

        return $inputs;
    }
    private function validateRequireFields ($inputs) {
        foreach ($inputs as $key => $val) {
            if (empty ($val)) {
                array_push($this->errors, "$key field is require");
            }
        }   
    }
    private function validateConfirmPassword ($inputs) {
        if (isset($inputs["password"]) && !empty($inputs["password"])) {
            if (isset($inputs["confirmpassword"]) && !empty($inputs["confirmpassword"])) {
                if ($inputs["password"] !== $inputs["confirmpassword"]) 
                    array_push($this->errors, "Passwords do not match!");
                else
                    unset($inputs["confirmpassword"]);
            }
        }
            
        return $inputs;
    }
    private function validateCurrentPassword ($inputs) {
        try {
            if (isset($inputs["currentpassword"]) && !empty($inputs["currentpassword"])) {
                if (isset($inputs["username"]) && !empty($inputs["username"])) {

                    $user = $this->getUserByUsername ($inputs["username"]);
                    $currentPass = hash("sha512", $inputs["currentpassword"]);
                    
                    if ($currentPass !== $user->pass()) 
                        array_push($this->errors, "Incorrect Password!");
                    else {
                        unset($inputs["currentpassword"]);
                    }
                }
            }
                
            return $inputs;
        } catch (PDOException $err) {
            echo "User: ".$err->getMessage();
        }
    }
    private function validateExistUser ($inputs) {
        try {
            if (isset($inputs["username"]) && !empty($inputs["username"])) {    
                $user = $this->getUserByUsername ($inputs["username"]);
                if (!$user || hash("sha512", $inputs["password"]) !== $user->pass())
                    array_push($this->errors, "User not found!");
            }
        } catch (PDOException $err) {
            echo "User: ".$err->getMessage();
        }
    }
}

