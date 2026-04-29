<?php
// models/Venta.php
class Venta {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getVentasHoy() {
        $sql = "SELECT COALESCE(SUM(total), 0) as total FROM ventas WHERE DATE(fecha_pago) = CURDATE() AND estado = 'pagado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>