<?php
// models/Mesa.php
class Mesa {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAll() {
        // MODIFICADO: Agregamos el LEFT JOIN para traer el nombre del mesero asignado
        $sql = "SELECT m.*, u.nombre AS nombre_mesero 
                FROM mesas m 
                LEFT JOIN usuarios u ON m.mesero_id = u.id 
                WHERE m.activa = 1 
                ORDER BY m.numero_mesa";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalMesas() {
        $sql = "SELECT COUNT(*) as total FROM mesas WHERE activa = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    public function getMesasOcupadas() {
        $sql = "SELECT COUNT(*) as ocupadas FROM mesas WHERE estado = 'ocupada' AND activa = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? $result['ocupadas']; // Ajuste preventivo por consistencia
    }
    
    public function crear($data) {
        $sqlCheck = "SELECT id, activa FROM mesas WHERE numero_mesa = :numero_mesa";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bindValue(':numero_mesa', $data['numero_mesa'], PDO::PARAM_STR);
        $stmtCheck->execute();
        
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        // Manejo seguro del mesero_id (si viene vacío, se guarda como NULL en la BD)
        $mesero_id = !empty($data['mesero_id']) ? $data['mesero_id'] : null;
        
        if ($row) {
            if ($row['activa'] == 1) {
                return false;
            } else {
                // MODIFICADO: También actualizamos el mesero asignado al reactivar una mesa
                $sqlRe = "UPDATE mesas SET ubicacion = :ubicacion, mesero_id = :mesero_id, activa = 1, estado = 'libre' WHERE id = :id";
                $stmtRe = $this->db->prepare($sqlRe);
                $stmtRe->bindValue(':ubicacion', $data['ubicacion'], PDO::PARAM_STR);
                $stmtRe->bindValue(':mesero_id', $mesero_id, $mesero_id !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
                $stmtRe->bindValue(':id', $row['id'], PDO::PARAM_INT);
                return $stmtRe->execute();
            }
        }
        
        // MODIFICADO: Incluimos el campo mesero_id en el INSERT de la nueva mesa
        $sql = "INSERT INTO mesas (numero_mesa, ubicacion, mesero_id, activa, estado) VALUES (:numero_mesa, :ubicacion, :mesero_id, 1, 'libre')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':numero_mesa', $data['numero_mesa'], PDO::PARAM_STR);
        $stmt->bindValue(':ubicacion', $data['ubicacion'], PDO::PARAM_STR);
        $stmt->bindValue(':mesero_id', $mesero_id, $mesero_id !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        return $stmt->execute();
    }
    
    public function eliminar($id) {
        $sql = "UPDATE mesas SET activa = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>