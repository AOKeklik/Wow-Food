<?php 
class Functions {
    private $pdo,$errors=[];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /* GENERALS */
    public function getAll ($table) {
        try {
            $sql = "select * from $table";
            $stmt = $this->pdo->prepare ($sql);
            $stmt->execute ();
            
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (strpos($table, "category"))
                    $results[] = new Category ($this->pdo, $row);
                else
                    $results[] = new User ($this->pdo, $row);
            }
            
            return $results;
        } catch (PDOException $err) {
            echo "User: ".$err->getMessage();
        }
    }
    public function create ($table, $data) {
        try {
            $data = $this->formSanitizer($data);
            $data = $this->hashPassword($data);
            $sql = "insert into $table (".implode(", ", array_keys($data)).")";
            $sql .= "values(:".implode(", :", array_keys($data)).")";
            
            $stmt = $this->pdo->prepare($sql);

            foreach ($data as $key => $val) {
                $stmt->bindValue (":$key", $val);
            }

            return $stmt->execute() ? $this->pdo->lastInsertId() : false;
        } catch (PDOException $err) {
            throw new Exception("create: " . $err->getMessage());
        }
    }
    public function delete ($table, $data) {
        try {
            $data = $this->formSanitizer($data);

            $sql = "delete from $table where id=:id";
            $stmt = $this->pdo->prepare ($sql);
            
            foreach ($data as $key => $val) {
                $stmt->bindValue (":$key", $val);
            }

            return $stmt->execute () ? true : false;
        } catch (PDOException $err) {
            echo "User: ".$err->getMessage();
        }
    }
    public function update ($table, $data, $id) {
        $sql = "update $table set ";

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

        $stmt->bindValue (":id", $id);

        return $stmt->execute() ? true : false;
    }

    /* USERS */
    public function updateUser ($data, $userId) {
        try {
            $data = $this->formSanitizer($data);
            $this->validateRequireFields($data);
            $data = $this->validateConfirmPassword($data);
            $data = $this->validateCurrentPassword($data);

            if (empty ($this->errors)) {
                $data = $this->hashPassword($data);

                $user = $this->update ("tbl_admin", $data, $userId);
                
                return $user;
            }

            return false;
        } catch (PDOException $err) {
            return "updateUser: ".$err->getMessage ();
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
            throw new Exception("getUserByUsername: " . $err->getMessage());
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

    /* CATEGORY */
    public function getCategoryByTitle ($title) {
        try {
            $sql = "select * from tbl_category where title=:title";
            $stmt = $this->pdo->prepare ($sql);
            $stmt->bindValue(":title", $title);
            $stmt->execute();
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            return  $category ? new Category ($this->pdo, $category) : false;
        } catch (PDOException $err) {
            throw new Exception("getCategoryByTitle: " . $err->getMessage());
        }
    }
    public function updateCategory ($data, $userId) {
        try {
            $data = $this->formSanitizer($data);
            $this->validateRequireFields($data);

            if (empty ($this->errors)) {
                $data = $this->hashPassword($data);

                $user = $this->update ("tbl_category", $data, $userId);
                
                return $user;
            }

            return false;
        } catch (PDOException $err) {
            return "updateUser: ".$err->getMessage ();
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
    public function sendResponse ($status, $msg, $rest=[]) {
        $response = [
            "status" => $status,
            "message" => $msg
        ];

        if (!empty ($rest)) {
            $response = array_merge($response, $rest);
        }

        echo json_encode($response);
        exit;
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

            if ($key === "active" || $key === "featured") {
                if (!isset($val) || !in_array($val, ["0", "1"], true))
                    array_push($this->errors, ucfirst($key) . " field is required and must be 1 or 0.");
            } else
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

