<?php
// models/Pedido.php
class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getPedidosActivos() {
        $sql = "SELECT COUNT(*) as total FROM pedidos WHERE estado IN ('pendiente', 'confirmado', 'en_preparacion')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>