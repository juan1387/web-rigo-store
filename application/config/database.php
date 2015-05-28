<?php

class ClassDatabase {

    public $con;

    public function __construct() {
        try {
            $this->con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',DB_USER, DB_PASS);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            return "ERROR: " . $e->getMessage();
        }
    }
   

}
