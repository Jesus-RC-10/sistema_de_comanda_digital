<?php
require_once __DIR__ . '/config.php';

class DatabasePDO {
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
                                  DB_USER, DB_PASS);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión PDO: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>