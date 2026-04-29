<?php
// config/database.php
require_once __DIR__ . '/config.php';

class Database {
    private static $connection = null;
    
    public static function getConnection() {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                self::$connection = new PDO($dsn, DB_USER, DB_PASS);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Configurar zona horaria para timestamps
                self::$connection->exec("SET time_zone = '-05:00'");
            } catch(PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
}
?>