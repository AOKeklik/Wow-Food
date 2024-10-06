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