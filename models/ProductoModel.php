<?php
require_once __DIR__ . '/../config/database.php';

class ProductoModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function obtenerProductosActivos() {
        $sql = "SELECT p.*, c.nombre AS categoria 
                FROM productos p 
                JOIN categorias_menu c ON p.categoria_id = c.id
                WHERE p.activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>