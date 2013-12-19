<?php
require_once '../config.php';

class DataAccess extends PDO {
    public function __construct() {
        try {
            parent::__construct('pgsql:host='.DB_HOST.';dbname='.DB_NAME.';port=5432', DB_LOGIN, DB_PASS);
        }
        catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit();
        }
    }
}

?>