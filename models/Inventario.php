<?php
// models/Inventario.php
class Inventario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM ingredientes WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStockBajo() {
        $sql = "SELECT * FROM ingredientes WHERE cantidad_actual <= cantidad_minima AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function actualizarCantidad($id, $cantidad) {
        $sql = "UPDATE ingredientes SET cantidad_actual = :cantidad WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function crear($data) {
        $sql = "INSERT INTO ingredientes (nombre, categoria, proveedor, unidad_medida, cantidad_minima, cantidad_actual, activo) VALUES (:nombre, :categoria, :proveedor, :unidad_medida, :cantidad_minima, :cantidad_actual, 1)";
        $stmt = $this->db->prepare($sql);
        $proveedor = $data['proveedor'] ?? 'Local';
        $cantidad_actual = $data['cantidad_actual'] ?? 0;
        $stmt->bindValue(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindValue(':categoria', $data['categoria'], PDO::PARAM_STR);
        $stmt->bindValue(':proveedor', $proveedor, PDO::PARAM_STR);
        $stmt->bindValue(':unidad_medida', $data['unidad_medida'], PDO::PARAM_STR);
        $stmt->bindValue(':cantidad_minima', $data['cantidad_minima'], PDO::PARAM_STR);
        $stmt->bindValue(':cantidad_actual', $cantidad_actual, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    public function getIngrediente($id) {
        $sql = "SELECT * FROM ingredientes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>