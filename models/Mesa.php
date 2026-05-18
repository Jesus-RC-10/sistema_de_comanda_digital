<?php
// models/Mesa.php
class Mesa {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM mesas WHERE activa = 1 ORDER BY numero_mesa";
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
        return $result['ocupadas'];
    }
    
    public function crear($data) {
        $sqlCheck = "SELECT id, activa FROM mesas WHERE numero_mesa = :numero_mesa";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bindValue(':numero_mesa', $data['numero_mesa'], PDO::PARAM_STR);
        $stmtCheck->execute();
        
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            if ($row['activa'] == 1) {
                return false;
            } else {
                $sqlRe = "UPDATE mesas SET ubicacion = :ubicacion, activa = 1, estado = 'libre' WHERE id = :id";
                $stmtRe = $this->db->prepare($sqlRe);
                $stmtRe->bindValue(':ubicacion', $data['ubicacion'], PDO::PARAM_STR);
                $stmtRe->bindValue(':id', $row['id'], PDO::PARAM_INT);
                return $stmtRe->execute();
            }
        }
        
        $sql = "INSERT INTO mesas (numero_mesa, ubicacion, activa, estado) VALUES (:numero_mesa, :ubicacion, 1, 'libre')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':numero_mesa', $data['numero_mesa'], PDO::PARAM_STR);
        $stmt->bindValue(':ubicacion', $data['ubicacion'], PDO::PARAM_STR);
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