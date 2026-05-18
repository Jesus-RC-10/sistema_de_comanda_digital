<?php
// models/Producto.php
class Producto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAllActive() {
        $sql = "SELECT p.*, c.nombre as categoria FROM productos p 
                LEFT JOIN categorias_menu c ON p.categoria_id = c.id 
                WHERE p.activo = 1 ORDER BY p.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($data, $imagen = null) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id, stock, imagen) VALUES (:nombre, :descripcion, :precio, :categoria_id, :stock, :imagen)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindValue(':precio', $data['precio'], PDO::PARAM_STR);
        $stmt->bindValue(':categoria_id', $data['categoria_id'], PDO::PARAM_INT);
        $stmt->bindValue(':stock', $data['stock'], PDO::PARAM_INT);
        $stmt->bindValue(':imagen', $imagen, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function agregarIngredienteReceta($producto_id, $ingrediente_id, $cantidad) {
        $sql = "INSERT INTO recetas_producto (producto_id, ingrediente_id, cantidad) VALUES (:producto_id, :ingrediente_id, :cantidad)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindValue(':ingrediente_id', $ingrediente_id, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    public function eliminar($id) {
        $sql = "UPDATE productos SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>