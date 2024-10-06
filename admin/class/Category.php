<?php

class Category {
    private $pdo,$data;
    public function __construct($pdo, $input) {
        $this->pdo = $pdo;
        if (is_array($input)) {
            $this->data = $input;
        } else {
            try {
                $sql = "select * from tbl_category where id=:id";
                $stmt = $this->pdo->prepare ($sql);
                $stmt->bindValue (":id", $input, PDO::PARAM_INT);
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
    public function title () {
        return $this->data["title"];
    }
    public function img () {
        return $this->data["image_name"];
    }
    public function featured () {
        return $this->data["featured"];
    }
    public function active () {
        return $this->data["active"];
    }
    public function isValid () {
        return !empty ($this->data);
    }
}